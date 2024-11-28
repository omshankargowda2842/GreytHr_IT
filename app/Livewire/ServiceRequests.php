<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Mail\assigneRequestMail;
use App\Models\EmployeeDetails;
use App\Models\IncidentRequest;
use App\Models\IT;
use App\Models\ServiceRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ServiceRequests extends Component
{
    public $loading = false;
    public $serviceRequestDetails = false;
    public $serviceRequest;
    public $currentRequestId;
    public $viewingDetails = false;
    public $viewServiceRequest = false;
    public $viewEmpRequest = false;
    public $serviceDetails;
    public $servicePendingDetails;
    public $serviceClosedDetails;
    public $serviceInprogressDetails;
    public $selectedAssigne;
    public $itData;
    public $selectedStatus;
    public $comments;
    public $assignTo;
    public  $remarks = [];

    protected $rules = [
        'request.assignTo' => 'required',
        'comments' => 'required',
        'request.status' => 'required',
        'remarks' => 'required',
        'selectedStatus' => 'required',
        'selectedAssigne' => 'required',
    ];



    public function viewServiceDetails($index)
    {

        try {
            $this->serviceRequest = $this->serviceDetails->get($index);
            $this->selectedAssigne = '';
            $this->selectedStatus = '';
            $this->comments = '';
            // Check if the selected request exists
            if (!$this->serviceRequest) {
                abort(404, 'Request not found');
            }

            $this->serviceRequestDetails = true;
            $this->currentRequestId = $this->serviceRequest->id;

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in viewserviceDetails method", [
                'exception' => $e,
                'index' => $index,
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while viewing the rejected request.");

            // Optionally, reset properties in case of error
            $this->serviceRequestDetails = false;
            $this->currentRequestId = null;
        }
    }



    public function closeServiceDetails()
    {

        $this->viewingDetails = false;
        $this->viewServiceRequest = true;
        $this->serviceRequestDetails = false;


        $this->viewEmpRequest = false;

        // $this->selectedRequest = true;
    }


    public $activeTab = 'active';
    public function setActiveTab($tab)
    {

        $this->activeTab = $tab;
        $this->viewingDetails = false;
        $this->selectedStatus = '';
        $this->selectedAssigne = '';

    }

    public function updateAssigne($taskId)
    {
        try {
            // Validate the selected assignee
            // $this->validateOnly('selectedAssigne');
            // $this->resetErrorBag('selectedAssigne');
            // Find the task by ID
            $task = IncidentRequest::find($taskId);
            // Check if the task exists and a valid assignee is selected
            if ($task && $this->selectedAssigne) {
                // Update the task with the selected assignee

                $fullNameAndEmpId = $this->selectedAssigne;

                // Split the string by space
                $parts = explode(' ', $fullNameAndEmpId);

                // Extract emp_id (last element in the array)
                $empId = array_pop($parts);

                // Join the remaining parts to get the full name
                $fullName = implode(' ', $parts);

                $employee = auth()->guard('it')->user();

             $assignedAssigne = EmployeeDetails::where('emp_id' ,   $empId )->get();

                $fullName = $assignedAssigne[0]->first_name . ' ' . $assignedAssigne[0]->last_name;  // Concatenate first and last name
                $email = $assignedAssigne[0]->email;


            $employeeName = $fullName;
            $requestId = $task->request_id;
            $shortDescription = $task->description; // Assuming this field exists
            $assigneName = $employee->employee_name;


                // Send Pending email
                Mail::to($email)->send(new assigneRequestMail(
                    $assigneName,
                    $requestId,
                    $shortDescription,
                    $task->category,

                ));


                $task->update(['ser_assign_to' => $this->selectedAssigne]);


                FlashMessageHelper::flashSuccess("Task assigned successfully, and email has been sent!");
                // Optionally, you can add a success message here
                // session()->flash('message', 'Task assigned successfully!');
            } else {
                // Handle case where task was not found or no assignee selected
                FlashMessageHelper::flashError("Task not found or invalid assignee.");
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in updateAssigne method", [
                'exception' => $e,
                'taskId' => $taskId,
                'selectedAssigne' => $this->selectedAssigne,
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while assigning the task.");
        }
    }

    public function updateStatus($taskId)
    {

        // $this->validateOnly('selectedStatus');

        try {



            $this->resetErrorBag('selectedStatus');

            // Find the task by ID
            $task = IncidentRequest::find($taskId);


            // Check if the task exists and a valid status is selected
            if ($task && $this->selectedStatus) {

                // Update the task status
                $task->update(['status_code' => $this->selectedStatus]);

                // Flash a success message based on the selected status
                if ($this->selectedStatus === '5') {
                    FlashMessageHelper::flashSuccess("Status has been set to Pending, and email has been sent!");
                }elseif ($this->selectedStatus === '16') {

                    $task->ser_progress_since = now();  // Set the current timestamp
                    $task->save();
                    FlashMessageHelper::flashSuccess("Status has been set to Inprogress, and email has been sent!");
                }
                 elseif ($this->selectedStatus === '11') {
                    FlashMessageHelper::flashSuccess("Status has been set to Completed, and email has been sent!");
                } else {
                    FlashMessageHelper::flashSuccess("Status Updated successfully!");
                }
            } else {
                // Handle case where the task was not found or no status is selected
                FlashMessageHelper::flashError("Task not found or invalid status.");
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in updateStatus method", [
                'exception' => $e,
                'taskId' => $taskId,
                'selectedStatus' => $this->selectedStatus,
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while updating the task status.");
        }
    }



    public function getTimeInProgress($taskId)
    {
        $task = IncidentRequest::find($taskId);

        if ($task && $task->status_code === '16' && $task->ser_progress_since) {
            // Get the difference between the current time and the time the task started being in progress
            $start = Carbon::parse($task->ser_progress_since);
            $elapsedTime = $start->diffForHumans(null, true); // human-readable difference
            return $elapsedTime;
        }

        return null;
    }


    public function handleStatusChange($requestId)
    {
        // Check which status is selected
        if ($this->selectedStatus == '15') {
            $this->cancelModal($requestId);
        } else {

            $this->updateStatus($requestId);
        }
    }

    public function redirectBasedOnStatus()
    {

        $this->validate([
            'selectedStatus' => 'required',

            'selectedAssigne' => 'required',
        ], [
            'selectedStatus.required' => 'Status is required.',
            'selectedAssigne.required' => 'Assign to is required.',
        ]);


        if ($this->selectedStatus === '5') {

            $this->setActiveTab('pending');

        } elseif ($this->selectedStatus === '16') {

            $this->setActiveTab('inprogress');

        }
        elseif ($this->selectedStatus === ['11','15']) {

            $this->setActiveTab('closed');

        }
        $this->reset(['selectedStatus', 'selectedAssigne']);
        $this->resetErrorBag();

    }



    public function postComment($taskId)
{
    try {
        // Find the task by taskId
        $task = IncidentRequest::find($taskId);

        // Check if task exists and a comment is provided
        if ($task && $this->comments) {

            // Update the task with the comment
            $task->update(['active_ser_comment' => $this->comments]);

            // Flash a success message
            FlashMessageHelper::flashSuccess("Comment posted successfully!");
        } else {
            // Handle case where task not found or no comment provided
            FlashMessageHelper::flashError("Task not found or no comment provided.");
        }
    } catch (\Exception $e) {
        // Log the exception for debugging
        Log::error("Error occurred while posting comment", [
            'exception' => $e,
            'taskId' => $taskId,
            'comments' => $this->comments,
        ]);

        // Flash an error message
        FlashMessageHelper::flashError("An error occurred while posting the comment.");
    }
}

public function postRemarks($taskId)
{
    try {

        // Find the task by taskId
        $task = IncidentRequest::find($taskId);

        if ($task) {
            // Validate remarks to make sure it is not empty
            if (empty($this->remarks)) {
                FlashMessageHelper::flashError("Remarks cannot be empty.");
                return;
            }

            // Update the task with the new remarks
            $task->update(['ser_pending_remarks' => $this->remarks]);

            // Flash a success message
            FlashMessageHelper::flashSuccess("Remarks posted successfully!");

            // Clear the remarks after posting
            $this->remarks = '';
        } else {
            // Handle case where task not found
            FlashMessageHelper::flashError("Task not found.");
        }
    } catch (\Exception $e) {
        // Log the exception for debugging
        Log::error("Error occurred while posting remarks", [
            'exception' => $e,
            'taskId' => $taskId,
            'remarks' => $this->remarks,
        ]);

        // Flash an error message
        FlashMessageHelper::flashError("An error occurred while posting the remarks.");
    }
}



public function postInprogressRemarks($taskId)
{
    try {

        // Find the task by taskId
        $task = IncidentRequest::find($taskId);

        if ($task) {
            // Validate remarks to make sure it is not empty
            if (empty($this->remarks)) {
                FlashMessageHelper::flashError("Remarks cannot be empty.");
                return;
            }

            // Update the task with the new remarks
            $task->update(['ser_inprogress_remarks' => $this->remarks]);

            // Flash a success message
            FlashMessageHelper::flashSuccess("Remarks posted successfully!");

            // Clear the remarks after posting
            $this->remarks = '';
        } else {
            // Handle case where task not found
            FlashMessageHelper::flashError("Task not found.");
        }
    } catch (\Exception $e) {
        // Log the exception for debugging
        Log::error("Error occurred while posting remarks", [
            'exception' => $e,
            'taskId' => $taskId,
            'remarks' => $this->remarks,
        ]);

        // Flash an error message
        FlashMessageHelper::flashError("An error occurred while posting the remarks.");
    }
}


        public $sortColumn = 'emp_id'; // default sorting column
        public $sortDirection = 'asc'; // default sorting direction

        public function toggleSortOrder($column)
        {
            try {
            if ($this->sortColumn == $column) {
                // If the column is the same, toggle the sort direction
                $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            } else {

                // If a different column is clicked, set it as the new sort column and default to ascending order
                $this->sortColumn = $column;
                $this->sortDirection = 'asc';
            }

        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error in toggleSortOrder: ' . $e->getMessage());

            // Optionally, set default sort direction or handle the error gracefully
            $this->sortColumn = 'emp_id'; // Example default sort column
            $this->sortDirection = 'asc'; // Example default sort direction

            // You may want to display an error message to the user, if needed
            session()->flash('error', 'An error occurred while changing the sort order.');
        }

        }

        public function inprogressForDesks($taskId)
        {
            $task = IncidentRequest::find($taskId);

            if ($task) {
                if ($task->ser_progress_since === null) {
                    // If it's the first time switching to InProgress, set the current time
                    $task->ser_progress_since = now();
                }

                $task->status_code = '16';  // Set status to InProgress
                $task->save();

                FlashMessageHelper::flashSuccess("Status Changed to inprogress!");

            }

        }

        public function pendingForDesks($taskId)
        {

            $task = IncidentRequest::find($taskId);

            if ($task) {
               // Calculate elapsed time since "InProgress" state started
        $elapsedTime = \Carbon\Carbon::parse($task->ser_progress_since)->diffInMinutes(now());

        // Update the total in-progress time and set status to Pending
        $task->total_ser_progress_time += $elapsedTime;  // Add elapsed time to total
        $task->update([
            'status_code' => '5',  // Set status to Pending
            'ser_progress_since' => null,  // Stop the timer
        ]);
                FlashMessageHelper::flashSuccess("Status Changed to pending!");

            }

        }

        public function closeForDesks($taskId)
        {
            $task = IncidentRequest::find($taskId);

            if ($task) {

                $totalElapsedMinutes = 0;

                if ($task->ser_progress_since) {
                    $totalElapsedMinutes = \Carbon\Carbon::parse($task->ser_progress_since)->diffInMinutes(now());
                }

                // Add the previously tracked progress time if exists
                if (isset($task->total_ser_progress_time)) {
                    $totalElapsedMinutes += $task->total_ser_progress_time;
                }

                $task->update([
                    'status_code' => '11', // Closed
                    'total_ser_progress_time' => $totalElapsedMinutes, // Store the total progress time
                    'ser_progress_since' => null // Reset the progress start time
                ]);
                FlashMessageHelper::flashSuccess("Status Changed to closed!");

            }

        }



        public $selectedRecord = null;
        public $showModal = false;

        public function viewRecord($id)
        {

            $this->selectedRecord = IncidentRequest::with('emp')
                ->where('id', $id)
                ->first();
            $this->showModal = true;


        }

        public function closeModal()
            {
                $this->showModal = false; // Hide the modal
                $this->selectedRecord = null; // Reset the selected record
            }


        public function mount(){

            $this->loadServiceClosedDetails();
        }

        public $statusFilter = '';


    public function loadServiceClosedDetails()
{

    try {
        // Start the query to load Service closed details
        $query = IncidentRequest::with('emp')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->where('category', 'Service Request');  // Ensure we are only getting Service Requests


        // Apply status filter if it's selected
        if ($this->statusFilter) {
            if ($this->statusFilter == '11') {
                // Filter by completed status
                $query->where('status_code', 11);
            } elseif ($this->statusFilter == '15') {
                // Filter by cancelled status
                $query->where('status_code', 15);
            }
        } else {
            // If no filter is selected, default to showing completed or cancelled records
            $query->whereIn('status_code', [11, 15]);
        }

        // Execute the query and fetch the records
        $this->serviceClosedDetails = $query->get();

    } catch (\Exception $e) {
        // Log any error that occurs during the query execution
        Log::error("Error loading Service closed details: " . $e->getMessage(), [
            'exception' => $e,
        ]);
        FlashMessageHelper::flashError('An error occurred while loading Service details.');
        $this->serviceClosedDetails = collect(); // Set to an empty collection in case of an error
    }
}



    public function render()
    {
        $this->serviceDetails = IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Service Request')
        ->where('status_code', 10)
        ->get();

        $this->servicePendingDetails = IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Service Request')
        ->where('status_code', 5)
        ->get();

        $this->serviceInprogressDetails = IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Service Request')
        ->where('status_code', 16)
        ->get();

        $this->itData = IT::with('empIt')->get();
        return view('livewire.service-requests');
    }

}
