<?php


namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Mail\assigneRequestMail;
use App\Mail\statusRequestMail;
use App\Models\ActivityLog;
use App\Models\EmployeeDetails;
use App\Models\HelpDesks;
use App\Models\IncidentRequest;
use App\Models\IncidentRequests as ModelsIncidentRequest;
use App\Models\IT;
use App\Models\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class IncidentRequests extends Component
{

    public $loading = false;
    public $incidentRequestDetails = false;
    public $incidentRequest;
    public $currentRequestId;
    public $viewingDetails = false;
    public $viewincidentRequests = false;
    public $viewEmpRequest = false;
    public $incidentDetails;
    public $incidentPendingDetails;
    public $incidentClosedDetails;
    public $incidentInprogressDetails;
    public $selectedAssigne;
    public $itData;
    public $selectedStatus;
    public $comments;
    public $assignTo;
    public  $remarks = [];
    public $showPopup = false;
    public $incidentIDHeader = '';
    public $snowId;

    public $filterType;
    public $activityLogs;
    public $employeeInitials;

    protected $rules = [
        'request.assignTo' => 'required',
        'comments' => 'required',
        'request.status' => 'required',
        'remarks' => 'required',
        'selectedStatus' => 'required',
        'selectedAssigne' => 'required',
    ];



    public function viewincidentDetails($index)
    {
        try {
            $this->incidentRequest = $this->incidentDetails->get($index);
            $this->selectedAssigne = '';
            $this->selectedStatus = '';
            $this->comments = '';
            // Check if the selected request exists
            if (!$this->incidentRequest) {
                abort(404, 'Request not found');
            }

            $this->incidentRequestDetails = true;
            $this->currentRequestId = $this->incidentRequest->id;

            $requestedBy= EmployeeDetails::where('emp_id' ,  $this->incidentRequest->emp_id)->first();
            $fullName = ucwords(strtolower($requestedBy->first_name . ' ' . $requestedBy->last_name));
               ActivityLog::create([
                   'impact' => 'High',
                   'opened_by' =>  $fullName ,
                   'priority' =>  $this->incidentRequest->priority,
                   'state' => "Open",
                   'performed_by' =>  $fullName,
                   'request_type' => 'Incident Request',
                   'request_id' => $this->incidentRequest->snow_id,
               ]);

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in viewincidentDetails method", [
                'exception' => $e,
                'index' => $index,
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while viewing the rejected request.");

            // Optionally, reset properties in case of error
            $this->incidentRequestDetails = false;
            $this->currentRequestId = null;
        }
    }



    public function closeincidentDetails()
    {

        $this->viewingDetails = false;
        $this->viewincidentRequests = true;
        $this->incidentRequestDetails = false;


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
            $snowId = $task->snow_id;


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
            $requestId = $task->snow_id;
            $shortDescription = $task->description; // Assuming this field exists
            $assigneName = ucwords(strtolower( $employee->employee_name));


                // Send Pending email
                Mail::to($email)->send(new assigneRequestMail(
                    $assigneName,
                    $requestId,
                    $shortDescription,
                    $task->category,

                ));


                $task->update(['inc_assign_to' => $this->selectedAssigne]);

                ActivityLog::create([
                    'action' => 'Assigned to',
                    'details' => "{$fullName}",
                    'performed_by' => $assigneName , // Assuming user is logged in
                    'request_type' => 'Incident Request',
                    'request_id' =>   $snowId ,
                ]);


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
            $employee = auth()->guard('it')->user();

            // Check if the task exists and a valid status is selected
            if ($task && $this->selectedStatus) {

                // Update the task status
                $task->update(['status_code' => $this->selectedStatus]);
                $activityDetails = '';
                // Flash a success message based on the selected status
                if ($this->selectedStatus === '5') {
                    $activityDetails = "Work in Progress was Pending for Service ID - {$task->snow_id}";
                    FlashMessageHelper::flashSuccess("Status has been set to Pending, and email has been sent!");
                }elseif ($this->selectedStatus === '16') {

                    $task->in_progress_since = now();  // Set the current timestamp
                    $task->save();
                    $activityDetails = "Work in Progress was Inprogress for Service ID - {$task->snow_id}";

                    FlashMessageHelper::flashSuccess("Status has been set to Inprogress, and email has been sent!");
                }
                 elseif ($this->selectedStatus === '11') {
                    $activityDetails = "Work in Progress was Completed for Service ID - {$task->snow_id}";

                    FlashMessageHelper::flashSuccess("Status has been set to Completed, and email has been sent!");
                } elseif ($this->selectedStatus === '15') {
                    $activityDetails = "Work in Progress was Completed for Service ID - {$task->snow_id}";
                    FlashMessageHelper::flashSuccess("Status has been set to Completed, and email has been sent!");
                }
            } else {
                // Handle case where the task was not found or no status is selected
                FlashMessageHelper::flashError("Task not found or invalid status.");
            }

            $assigneName =  ucwords(strtolower($employee->employee_name));
            ActivityLog::create([
                'action' => 'State',
                'details' => $activityDetails,
                'performed_by' => $assigneName,
                'request_type' => 'Incident Request',
                'request_id' => $task->snow_id,
            ]);
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

        if ($task && $task->status_code === '16' && $task->in_progress_since) {
            // Get the difference between the current time and the time the task started being in progress
            $start = Carbon::parse($task->in_progress_since);
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
            $task->update(['active_inc_comment' => $this->comments]);


            $employee = auth()->guard('it')->user(); // Get the logged-in user
            ActivityLog::create([
                'action' => 'Active Comment',
                'details' => "$this->comments",
                'performed_by' => $employee->employee_name,
                'request_type' => 'Incident Request',
                'request_id' => $task->snow_id,
            ]);

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
            $task->update(['inc_pending_remarks' => $this->remarks]);

            $employee = auth()->guard('it')->user(); // Get the logged-in user
            ActivityLog::create([
                'action' => 'Pending Comment',
                'details' => "$this->remarks",
                'performed_by' => $employee->employee_name,
                'request_type' => 'Incident Request',
                'request_id' => $task->snow_id,
            ]);
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
            $task->update(['inc_inprogress_remarks' => $this->remarks]);

            $employee = auth()->guard('it')->user(); // Get the logged-in user
            ActivityLog::create([
                'action' => 'Inprogress Comment',
                'details' => "$this->remarks",
                'performed_by' => $employee->employee_name,
                'request_type' => 'Incident Request',
                'request_id' => $task->snow_id,
            ]);
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
                if ($task->in_progress_since === null) {
                    // If it's the first time switching to InProgress, set the current time
                    $task->in_progress_since = now();
                }

                $task->status_code = '16';  // Set status to InProgress
                $task->save();

                $activityDetails = "Work in Progress was Inprogress for Service ID - {$task->snow_id}";

                $employee = auth()->guard('it')->user(); // Get the logged-in user
                ActivityLog::create([
                    'action' => 'State',
                    'details' => "$activityDetails",
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Incident Request',
                    'request_id' => $task->snow_id,
                ]);

                FlashMessageHelper::flashSuccess("Status Changed to inprogress!");

            }

        }

        public function pendingForDesks($taskId)
        {

            $task = IncidentRequest::find($taskId);

            if ($task) {
               // Calculate elapsed time since "InProgress" state started
        $elapsedTime = \Carbon\Carbon::parse($task->in_progress_since)->diffInMinutes(now());

        // Update the total in-progress time and set status to Pending
        $task->total_in_progress_time += $elapsedTime;  // Add elapsed time to total
        $task->update([
            'status_code' => '5',  // Set status to Pending
            'in_progress_since' => null,  // Stop the timer
        ]);

        $employee = auth()->guard('it')->user(); // Get the logged-in user
        ActivityLog::create([
            'action' => 'State',
            'details' => "Work in Progress was changed from Inprogress to Pending for Service ID - {$task->snow_id}",
            'performed_by' => $employee->employee_name,
            'request_type' => 'Incident Request',
            'request_id' => $task->snow_id,
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

                $employee = auth()->guard('it')->user(); // Get the logged-in user
                ActivityLog::create([
                    'action' => 'State',
                    'details' => "Work in Progress was Closed for Service ID - {$task->snow_id}",
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Incident Request',
                    'request_id' => $task->snow_id,
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

            $this->loadIncidentClosedDetails();
            $this->loadLogs();
        }


        public function filterLogs($type)
        {
            $this->filterType = $type;
            $this->loadLogs($this->snowId); // Re-load logs with the new filter
        }



        public function loadLogs($snowId = null)
        {

            if ($snowId) {

                $this->snowId = $snowId;
                $this->incidentIDHeader =   $this->snowId;
                $query = ActivityLog::where('request_id', $this->snowId)
                ->when($this->filterType, function ($q) {
                    return $q->where('created_at', $this->filterType);
                })
                ->orderBy('created_at', 'desc');

                $this->showPopup = true;


                // Apply filter if filterType is set
                if ($this->filterType) {
                    $query->where('type', $this->filterType);
                }

                // Fetch activity logs sorted by created_at in descending order
                $this->activityLogs = $query->get();

                $this->employeeInitials = [];
                foreach ($this->activityLogs as $log) {
                    $this->employeeInitials[] = $this->getInitials($log->performed_by);
                }
            }
        }


        private function getInitials($name)
        {
            $nameParts = explode(' ', $name);

            if (count($nameParts) < 2) {
                // If the name has less than 2 parts, just return the first letter of the first part
                return strtoupper(substr($nameParts[0], 0, 1));
            }

            // Extract the first letter of the first part and the last part
            $firstInitial = strtoupper(substr($nameParts[0], 0, 1));
            $lastInitial = strtoupper(substr($nameParts[count($nameParts) - 1], 0, 1));

            return $firstInitial . $lastInitial;
        }


        public function closePopup()
    {
        $this->showPopup = false;
        $this->activityLogs = null; // Clear logs if needed
    }



        public $statusFilter = '';


    public function loadIncidentClosedDetails()
{

    try {
        // Start the query to load incident closed details
        $query = IncidentRequest::with('emp')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->where('category', 'Incident Request');  // Ensure we are only getting Incident Requests


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
        $this->incidentClosedDetails = $query->get();

    } catch (\Exception $e) {
        // Log any error that occurs during the query execution
        Log::error("Error loading incident closed details: " . $e->getMessage(), [
            'exception' => $e,
        ]);
        FlashMessageHelper::flashError('An error occurred while loading incident details.');
        $this->incidentClosedDetails = collect(); // Set to an empty collection in case of an error
    }
}




    public $incidentOpenCount;
    public $incidentPendingCount;
    public $incidentprogressCount;
    public $incidentClosedCount;

    public function updateCounts()
    {
        try {
            // Fetch categories for IT requests

            $this->incidentOpenCount = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Incident Request')
            ->where('status_code', 10)
            ->count();

            $this->incidentPendingCount = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Incident Request')
            ->where('status_code', 5)
            ->count();

            $this->incidentprogressCount = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Incident Request')
            ->where('status_code', 16)
            ->count();

            $this->incidentClosedCount = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Incident Request')
            ->where('status_code',['11','15'])
            ->count();


        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error("Error occurred while updating counts", [
                'exception' => $e,
            ]);

            // Optionally, set all counts to zero or handle the error gracefully
            $this->incidentOpenCount = 0;
            $this->incidentPendingCount = 0;
            $this->incidentprogressCount = 0;
            $this->incidentClosedCount = 0;
            // Flash an error message to inform the user
            FlashMessageHelper::flashError("An error occurred while updating the request counts.");
        }
    }



    public function render()
    {


        $this->incidentDetails = IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Incident Request')
        ->where('status_code', 10)
        ->get();

        $this->incidentPendingDetails = IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Incident Request')
        ->where('status_code', 5)
        ->get();

        $this->incidentInprogressDetails = IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Incident Request')
        ->where('status_code', 16)
        ->get();

        $this->incidentClosedDetails = IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Incident Request')
        ->where('status_code', ['11','15'])
        ->get();


        $this->updateCounts();
        $this->itData = IT::with('empIt')->get();

        return view('livewire.incident-requests',[
            'incidentRequestDetails' => $this->incidentRequestDetails,
        ]);
    }


}
