<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use Livewire\Component;
use App\Models\IT;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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

    public function mount()
    {
        $this->itMembers = EmployeeDetails::where('sub_dept_id', '9915')->get();
        $this->itRelatedEmye =IT ::all();

    }

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



    public function submit()
    {
        $this->validate([
            'employeeName' => 'required|string|max:255',
            'employeeId' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:512',
            'phoneNumber' => 'required|numeric',
            'email' => 'required|email',
            'dateOfBirth' => 'required|date',
        ]);


        if($this->image) {
            if ($this->image->getSize() > 200 * 1024) {
                dd();
                session()->flash('message', 'Image size should be less than 200KB.');
                return;
            }
            $imageData = file_get_contents($this->image->getRealPath());
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
                    'image' => $imageData,
                ]);
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
        }

        $this->resetForm();
        session()->flash('message', $this->editMode ? 'IT member updated successfully!' : 'IT member added successfully!');
    }

    public function cancel(){
        $this->showAddIt = false;
        $this->editMode = false;
        $this->showEditDeleteIt = true;
    }
    public function cancelLogout()
    {
        $this->showLogoutModal = true;
    }

    public function delete($id)
    {
        IT::destroy($id);
        session()->flash('message', 'IT member deleted successfully!');
        $this->showLogoutModal = false;
        $this->itRelatedEmye = IT::all();
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
        // Find the employee based on the selected ID
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
        $this->itRelatedEmye = IT::all();
        return view('livewire.it-add-member',['itRelatedEmye' => $this->itRelatedEmye->map(function ($item) {
                $item->formatted_date_of_birth = $this->formatDate($item->date_of_birth);
                return $item;
            }),
        ]);
    }
}
