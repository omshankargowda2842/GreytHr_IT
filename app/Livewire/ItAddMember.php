<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use Livewire\Component;
use App\Models\IT;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ItAddMember extends Component
{

    use WithFileUploads;
    public $employeeName;
    public $employeeId;
    public $image;
    public $phoneNumber;
    public $email;
    public $dateOfBirth;
    public $itMembers = [];
    public $itRelatedEmye = [];
    public $showAddIt = false;
    public $showEditDeleteIt = true;
    public $editMode = false;
    public $selectedItId;
    public $showLogoutModal =false;
    public $dateFromDatabase;
    public $imageData;
    public $reason =[];
    public $loading = false;


    public function mount()
    {
        $this->itMembers = EmployeeDetails::where('sub_dept_id', '9915')->get();
        $this->itRelatedEmye = IT::where('is_active', 1)->get();

    }

    protected $rules = [
        'employeeName' => 'required|string|max:255',
        'employeeId' => 'required',
        'phoneNumber' => 'required|numeric',
        'email' => 'required|email',
        'dateOfBirth' => 'required|date',
    ];

    protected $messages = [
        'employeeName.required' => 'Employee name is required.',
        'employeeId.required' => 'Employee ID is required.',

        'phoneNumber.required' => 'Phone number is required.',
        'email.required' => 'Email is required.',
        'dateOfBirth.required' => 'Date of birth is required.',
    ];




    public function showAddItMember()
    {
        $this->resetForm();
        $this->showAddIt = true;
        $this->showEditDeleteIt = false;
        $this->editMode = false;
    }

    public function formatDate($date)
    {
        return Carbon::parse($date)->format('d-m-Y');
    }




    public function showEditItMember($id)
    {
        $this->resetForm();
        $this->resetErrorBag();
        $itMember = IT::find($id);
        if ($itMember) {

            $this->selectedItId = $id;
            $this->employeeId = $itMember->emp_id;
            $this->employeeName = $itMember->employee_name;
            $this->dateOfBirth = Carbon::parse($itMember->date_of_birth)->format('Y-m-d');
            $this->phoneNumber = $itMember->phone_number;
            $this->email = $itMember->email;
            $this->image = $itMember->image ? 'data:image/jpeg;base64,' . base64_encode($itMember->image) : null;
            $this->showAddIt = true;
            $this->showEditDeleteIt = false;
            $this->editMode = true;
        }
    }
    public function validateEmail()
    {
        $this->validate([
            'email' => ['required', 'email', function($attribute, $value, $fail) {
            $allowedExtensions = ['payg.in', 'paygdigitals.com'];
            $domain = substr(strrchr($value, "@"), 1);
            if (!in_array($domain, $allowedExtensions)) {
            $fail('Email address must use one of these domains:: ' . implode(', ', $allowedExtensions));
            }
        }],
        ]);
    }


    public function validateField($propertyName)
    {
        // Apply validation rules dynamically
        $rules = $this->rules;

        // Apply image validation only if a new image is uploaded
        if ($propertyName === 'image') {
        $rules['image'] = $this->isImageChanged ? 'nullable|image|max:512' : 'nullable';
        }

        $this->validateOnly($propertyName, $rules);

        $this->resetErrorBag();
    }
    public function validateEmployeeId()
    {
        $existingRecord = IT::where('emp_id', $this->employeeId)
        ->where('id', '!=', $this->selectedItId)
        ->exists();

        if ($existingRecord) {

        $this->addError('employeeId', 'An IT member with this Employee ID already exists.');
        }
    }



    public function submit()
    {
        $this->resetErrorBag('employeeId');

        $this->validateEmployeeId();
        if ($this->getErrorBag()->has('employeeId')) {
        return;
        }
        $this->validate();
        $this->validateEmail();
        // $this->resetErrorBag('email');

        $rules = $this->rules;

        $imageData = null;


        if ($this->image) {
        // If image is base64 data
        if (strpos($this->image, 'data:image/jpeg;base64,') === 0) {
        $imageData = base64_decode(substr($this->image, strpos($this->image, ',') + 1));

        }
        // If image is an instance of UploadedFile
        elseif ($this->image instanceof \Illuminate\Http\UploadedFile) {
        if ($this->image->getSize() > 500 * 1024) { // 500KB
        $this->addError('image', 'Image size should be less than 500KB.');
        return;
        }
        $imageData = file_get_contents($this->image->getRealPath());
        } else {
        // Handle the case where $this->image is not valid
        $this->addError('image', 'Invalid image file.');
        return;
        }
        }

        if ($this->editMode) {
        // Update existing record
        $data = IT::find($this->selectedItId);
        if ($data) {

        $data->update([
        'emp_id' => $this->employeeId,
        'employee_name' => $this->employeeName,
        'date_of_birth' => $this->dateOfBirth,
        'phone_number' => $this->phoneNumber,
        'email' => $this->email,
        'image' => $imageData ?? $data->image,

        ]);
        session()->flash('updateMessage', 'IT member updated successfully!');

        }
        } else {
        // Create new record
        IT::create([
        'emp_id' => $this->employeeId,
        'employee_name' => $this->employeeName,
        'date_of_birth' => $this->dateOfBirth,
        'phone_number' => $this->phoneNumber,
        'email' => $this->email,
        'image' => $imageData,
        'is_active' => true,
        ]);

        session()->flash('createMessage', 'IT member added successfully!');

        }

        $this->resetForm();
        // session()->flash('message', $this->editMode ? 'IT member updated successfully!' : 'IT member added successfully!');

    }

    public function cancel(){
        $this->showAddIt = false;
        $this->editMode = false;
        $this->showEditDeleteIt = true;
        $this->showLogoutModal = false;
        $this->resetErrorBag();

    }
    public function cancelLogout()
    {
         $this->showLogoutModal = true;
    }

    public $recordId;
    public function confirmDelete($id)
{
    $this->recordId = $id;
    $this->showLogoutModal = true;
}

public function delete()
{
    $this->validate([

        'reason' => 'required|string|max:255', // Validate the remark input
    ], [
        'reason.required' => 'Reason is required.',
    ]);
    $this->resetErrorBag();
    $itMember = IT::find($this->recordId);

    if ($itMember) {
        $itMember->update([
            'delete_itmember_reason' => $this->reason,
            'is_active' => 0
        ]);

        session()->flash('deactivationMessage', 'IT member deactivated successfully!');
        $this->showLogoutModal = false;
        $this->itRelatedEmye = IT::where('is_active', 1)->get();
        // Reset the recordId and reason after processing
        $this->recordId = null;
        $this->reason = '';
    }
}




    private function resetForm()
    {
        $this->employeeName = '';
        $this->employeeId = '';
        $this->image = null;
        $this->phoneNumber = '';
        $this->email = '';
        $this->dateOfBirth = '';
        $this->selectedItId = null;
        $this->editMode = false;
        $this->showAddIt = false;
        $this->showEditDeleteIt = true;
    }




    public function updateEmployeeName()
    {
        $this->resetErrorBag();
        $this->validateEmployeeId();
        $member = EmployeeDetails::with('personalInfo')->where('emp_id', $this->employeeId)->first();

        if ($member) {

        $this->employeeName = "{$member->first_name} {$member->last_name}";

        $this->dateOfBirth = $member->personalInfo->date_of_birth ?? '';
        $this->phoneNumber = $member->personalInfo->mobile_number ?? '';
        $this->email = $member->personalInfo->email ?? '';


        } else {
        $this->employeeName = '';
        $this->dateOfBirth = '';
        $this->phoneNumber = '';
        $this->email = '';
        }
    }

    public function render()
    {
        $this->itRelatedEmye = IT::where('is_active', 1)->get();
        return view('livewire.it-add-member');
    }

}
