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

    protected $queryString = ['currentRequestId'];

    public function mount($id = null)
    {
        $this->loadIncidentClosedDetails();
        $this->loadLogs();

        if ($id) {
            $this->currentRequestId = $id;
        }

        if ($this->currentRequestId) {
            $this->viewincidentDetails($this->currentRequestId);
        }
    }

    public function updatedCurrentRequestId($id)
    {
        $this->viewincidentDetails($id);
    }

    public function viewincidentDetails($id)
    {

        try {
            $this->incidentRequest = IncidentRequest::with('emp')->find($id);
            $this->selectedAssigne = '';
            $this->selectedStatus = '';
            $this->comments = '';

            if (!$this->incidentRequest) {
                abort(404, 'Request not found');
            }

            $this->incidentRequestDetails = true;

            $requestedBy = EmployeeDetails::where('emp_id', $this->incidentRequest->emp_id)->first();
            $fullName = ucwords(strtolower($requestedBy->first_name . ' ' . $requestedBy->last_name));

            ActivityLog::create([
                'impact' => 'High',
                'opened_by' => $fullName,
                'priority' => $this->incidentRequest->priority,
                'state' => "Open",
                'performed_by' => $fullName,
                'request_type' => 'Incident Request',
                'request_id' => $this->incidentRequest->snow_id,
            ]);
        } catch (\Exception $e) {
            Log::error("Error occurred in viewincidentDetails method", [
                'exception' => $e,
                'index' => $id,
            ]);
            FlashMessageHelper::flashError("An error occurred while viewing the request.");
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
        return redirect()->route('incidentRequests');
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

    public function SelectedAssigne()
    {
        if ($this->selectedAssigne) {
            FlashMessageHelper::flashSuccess("Assignee selected: {$this->selectedAssigne}");
        }
    }

    public function SelectedStatus()
    {
         if ($this->selectedStatus === '5') {

            $this->modalPurpose = 'Pending';
            $this->showStatusModal = true;

                }elseif ($this->selectedStatus === '16') {

                    FlashMessageHelper::flashSuccess("Status has been set to Inprogress");
                }
                 elseif ($this->selectedStatus === '11') {
                    $this->modalPurpose = 'Completed';
                    $this->showStatusModal = true;

                } elseif ($this->selectedStatus === '15') {
                    $this->modalPurpose = 'Cancel';
                    $this->showStatusModal = true;

                }

                $this->updateCounts();
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
        if ($this->selectedStatus ){
            $this->updateStatus($requestId);
        }
    }




    public $pendingReason;
    public $modalPurpose = '';
    public $showStatusModal = false;
    public $pendingRequestId = '';

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->reset(['pendingReason', 'pendingRequestId','modalPurpose']);
    }




    public function submitStatusReason($requestId)
    {
        $this->validate([
        'pendingReason' => 'required|string|max:255',
        ]);
        // Update the request status and reason
        $this->pendingRequestId = $requestId;
        $task = IncidentRequest::find($this->pendingRequestId);

        if ($task && $this->modalPurpose) {

        if ($this->modalPurpose === 'Pending') {

        $task->update([
        'inc_pending_notes' => $this->pendingReason
        ]);

        $employee = auth()->guard('it')->user();

        $assignedAssigne = EmployeeDetails::where('emp_id' , $task->emp_id )->get();

        $employeeEmail = $assignedAssigne[0]->email;

        $employeeName = $task->emp->first_name . ' ' . $task->emp->last_name;

        $requestId = $task->request_id;
        $shortDescription = $task->description; // Assuming this field exists
        $pendingReason = $this->pendingReason;

        // Send Pending email
        Mail::to($employeeEmail)->send(new statusRequestMail(
        $employeeName,
        $requestId,
        $pendingReason,
        $shortDescription,
        $task->category,
        'Pending' // Passing a flag for Pending
        ));

        $activityDetails = '';
        // Flash a success message based on the selected status
        $activityDetails = "Work in Progress was Pending for Request ID - {$task->snow_id}";
        FlashMessageHelper::flashSuccess("Status has been set to Pending");


        $assigneName = $employee->employee_name;
        ActivityLog::create([
        'action' => 'State',
        'details' => $activityDetails,
        'performed_by' => $assigneName,
        'request_type' => 'Incident Request',
        'request_id' => $task->snow_id,
        ]);

        }

        elseif ($this->modalPurpose === 'Cancel') {
        // Logic for Cancel
        $cancelRequest = IncidentRequest::with('emp')->where('id', $this->pendingRequestId)->first();

        $task->update(['inc_cancel_notes' => $this->pendingReason,
                        'inc_end_date' => now()]);

        $employee = auth()->guard('it')->user();

        $assignedAssigne = EmployeeDetails::where('emp_id' , $task->emp_id )->get();

        $employeeEmail = $assignedAssigne[0]->email;

        $rejectionReason = $this->pendingReason;
        $Id = $cancelRequest->snow_id;

        $activityDetails = '';
        // Flash a success message based on the selected status


        $activityDetails = "Work in Progress was Cancelled for Request ID - {$task->snow_id}";

        $employee = auth()->guard('it')->user();
        $assigneName = $employee->employee_name;
        ActivityLog::create([
        'action' => 'State',
        'details' => $activityDetails,
        'performed_by' => $assigneName,
        'request_type' => 'Incident Request',
        'request_id' => $task->snow_id,
        ]);

        FlashMessageHelper::flashSuccess("Status has been set to Cancelled");

        } elseif ($this->modalPurpose === 'Completed') {


        $task->update([
        'inc_completed_notes' => $this->pendingReason,
        'inc_end_date' => now(),
        ]);

        $employee = auth()->guard('it')->user();

        $assignedAssigne = EmployeeDetails::where('emp_id' , $task->emp_id )->get();

        $employeeEmail = $assignedAssigne[0]->email;

        $employeeName = $task->emp->first_name . ' ' . $task->emp->last_name;

        $requestId = $task->request_id;
        $shortDescription = $task->description; // Assuming this field exists
        $pendingReason = $this->pendingReason;


        $activityDetails = '';
        // Flash a success message based on the selected status
        $activityDetails = "Work in Progress was Completed for Request ID - {$task->snow_id}";
        FlashMessageHelper::flashSuccess("Status has been set to Completed");


        $assigneName = $employee->employee_name;
        ActivityLog::create([
        'action' => 'State',
        'details' => $activityDetails,
        'performed_by' => $assigneName,
        'request_type' => 'Incident Request',
        'request_id' => $task->snow_id,
        ]);

        }

        ActivityLog::create([
        'action' => $this->modalPurpose,
        'details' => $this->pendingReason,
        'performed_by' => $employee->employee_name,
        'request_type' => 'Incident Request',
        'request_id' => $task->snow_id,
        ]);

        $this->closeStatusModal();

        } else {
        // Handle case where the task was not found or no status is selected
        FlashMessageHelper::flashError("Task not found or invalid status.");
        }
    }


    public function activeIncidentSubmit($taskId)
    {

        $this->validate([
            'selectedStatus' => 'required',

            'selectedAssigne' => 'required',
        ], [
            'selectedStatus.required' => 'Status is required.',
            'selectedAssigne.required' => 'Assign to is required.',
        ]);


        $task = IncidentRequest::find($taskId);
        $employee = auth()->guard('it')->user();
        $snowId = $task->snow_id;

        if ($this->selectedAssigne) {
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
        $requestId =  $snowId;
        $shortDescription = $task->description; // Assuming this field exists
        $assigneName = $employee->employee_name;


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

            // Optionally, you can add a success message here
            // session()->flash('message', 'Task assigned successfully!');
        } else {
            // Handle case where task was not found or no assignee selected
            FlashMessageHelper::flashError("Task not found or invalid assignee.");
        }



        if ($this->selectedStatus) {

            // Update the task status
            $task->update(['status_code' => $this->selectedStatus]);

            $activityDetails = '';
            // Flash a success message based on the selected status
            if ($this->selectedStatus === '5') {
                $activityDetails = "Work in Progress was Pending for Incident ID - {$task->snow_id}";
            }elseif ($this->selectedStatus === '16') {

                $task->in_progress_since = now();  // Set the current timestamp
                $task->save();
                $activityDetails = "Work in Progress was Inprogress for Incident ID - {$task->snow_id}";

            }
             elseif ($this->selectedStatus === '11') {

            } elseif ($this->selectedStatus === '15') {

            }


        $assigneName = $employee->employee_name;
        ActivityLog::create([
            'action' => 'State',
            'details' => $activityDetails,
            'performed_by' => $assigneName,
            'request_type' => 'Incident Request',
            'request_id' => $task->snow_id,
        ]);

            $this->updateCounts();
        } else {
            // Handle case where the task was not found or no status is selected
            FlashMessageHelper::flashError("Task not found or invalid status.");
        }


        FlashMessageHelper::flashSuccess("Request submitted successfully");
        $this->viewingDetails = false;
        $this->viewincidentRequests = true;
        $this->incidentRequestDetails = false;
        $this->reset(['selectedStatus', 'selectedAssigne']);
        $this->resetErrorBag();
        $this->updateCounts();
        return redirect()->route('incidentRequests');
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





        public function selectedInprogress($taskId)
            {
                $this->selectedTaskId = $taskId;

                $this->showInprogressModal =true;
                $this->updateCounts();
            }

    public $showInprogressModal = false;


    public function closeInprogressModal()
    {
        $this->showInprogressModal = false;
        $this->reset(['pendingReason', 'pendingRequestId','modalPurpose']);
    }




        public $selectedTaskId;
        public function inprogressForDesks()
        {

            $this->validate([
                'pendingReason' => 'required|string|max:255',
            ]);

            try {

                $task = IncidentRequest::find($this->selectedTaskId);

                if (!$task) {
                    throw new \Exception('Task not found');
                }

                $employee = auth()->guard('it')->user();

                if ($this->pendingReason) {
                    ActivityLog::create([
                        'action' => "Inprogress Notes",
                        'details' => $this->pendingReason,
                        'performed_by' => $employee->employee_name,
                        'request_type' => 'Incident Request',
                        'request_id' => $task->snow_id,
                    ]);
                }

                if ($task) {
                    if ($task->in_progress_since === null) {
                        // If it's the first time switching to InProgress, set the current time
                        $task->in_progress_since = now();
                    }

                    $task->update([
                        'inc_inprogress_notes' => $this->pendingReason,
                        'status_code' => 16,
                    ]);


                    $employee = auth()->guard('it')->user();

                    $activityDetails = '';
                    // Flash a success message based on the selected status
                    $activityDetails = "Work in Progress was Inprogress for Request ID - {$task->snow_id}";
                    FlashMessageHelper::flashSuccess("Status has been set to Inprogress");

                    $assigneName = $employee->employee_name;
                    ActivityLog::create([
                        'action' => 'State',
                        'details' => $activityDetails,
                        'performed_by' => $assigneName,
                        'request_type' => 'Incident Request',
                        'request_id' => $task->snow_id,
                    ]);

                    $this->closeInprogressModal();
                    $this->updateCounts();
                }
            } catch (\Exception $e) {
                // Log the error or handle it as needed
                Log::error("Error in inprogressForDesks method: " . $e->getMessage());

                // Optionally flash an error message
                FlashMessageHelper::flashError("An error occurred while updating the task status.");
            }
        }




        public function selectedPending($taskId)
        {
            $this->selectedTaskId = $taskId;
            $this->showPendingModal =true;
            $this->updateCounts();
        }

        public $showPendingModal = false;


        public function closePendingModal()
        {
            $this->showPendingModal = false;
            $this->reset(['pendingReason', 'pendingRequestId','modalPurpose']);
        }



        public function pendingForDesks($taskId)
        {

            $this->validate([
                'pendingReason' => 'required|string|max:255',
                ]);


            $task = IncidentRequest::find( $this->selectedTaskId );

            $employee = auth()->guard('it')->user();

            if($this->pendingReason){

                ActivityLog::create([
                    'action' => "Pending Notes",
                    'details' => $this->pendingReason,
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Incident Request',
                    'request_id' => $task->snow_id,
                    ]);

            }

            if ($task) {
                $elapsedTime = \Carbon\Carbon::parse($task->in_progress_since)->diffInMinutes(now());

                // Update the total in-progress time and set status to Pending
                $task->total_in_progress_time += $elapsedTime;  // Add elapsed time to total

                $task->update([
                    'inc_pending_notes' => $this->pendingReason,
                    'status_code' => 5,
                    'in_progress_since' => null,
                    ]);

                    $employee = auth()->guard('it')->user();

                    $activityDetails = '';
                    // Flash a success message based on the selected status
                    $activityDetails = "Work in Progress was Pending for Request ID - {$task->snow_id}";
                    FlashMessageHelper::flashSuccess("Status has been set to Pending");


                    $assigneName = $employee->employee_name;
                    ActivityLog::create([
                    'action' => 'State',
                    'details' => $activityDetails,
                    'performed_by' => $assigneName,
                    'request_type' => 'Incident Request',
                    'request_id' => $task->snow_id,
                    ]);

                $this->closePendingModal();
                $this->updateCounts();
            }


        }



        public function selectedClosed($taskId)
        {
            $this->selectedTaskId = $taskId;
            $this->showClosedModal =true;
            $this->updateCounts();
        }

        public $showClosedModal = false;


        public function closeClosedModal()
        {
            $this->showClosedModal = false;
            $this->reset(['pendingReason', 'pendingRequestId','modalPurpose','customerVisibleNotes']);
        }


        public $customerVisibleNotes;
        public function closeForDesks($taskId)
        {


            $this->validate([
                'pendingReason' => 'required|string|max:255',
                'customerVisibleNotes' => 'required|string|max:255',
                ]);


            $task = IncidentRequest::find( $this->selectedTaskId );

            $employee = auth()->guard('it')->user();

            if($this->pendingReason && $this->customerVisibleNotes){

                ActivityLog::create([
                    'action' => "Closed Notes",
                    'details' => "Closed Notes: {$this->pendingReason} ||| Customer Visible Notes: {$this->customerVisibleNotes}",
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Incident Request',
                    'request_id' => $task->snow_id,
                    ]);

            }

            if ($task) {

                $totalElapsedMinutes = 0;

                if ($task->in_progress_since) {
                    $totalElapsedMinutes = \Carbon\Carbon::parse($task->in_progress_since)->diffInMinutes(now());
                }

                // Add the previously tracked progress time if exists
                if (isset($task->total_in_progress_time)) {
                    $totalElapsedMinutes += $task->total_in_progress_time;
                }

                $task->update([
                    'status_code' => '11', // Closed
                    'total_in_progress_time' => $totalElapsedMinutes, // Store the total progress time
                    'in_progress_since' => null, // Reset the progress start time
                    'inc_end_date' => now(),
                    'inc_completed_notes' => $this->pendingReason,
                    'inc_customer_visible_notes' => $this->customerVisibleNotes,
                    ]);

                    $employee = auth()->guard('it')->user();

                    $activityDetails = '';
                    // Flash a success message based on the selected status
                    $activityDetails = "Work in Progress was Closed for Request ID - {$task->snow_id}";
                    FlashMessageHelper::flashSuccess("Status has been set to Closed");


                    $assigneName = $employee->employee_name;
                    ActivityLog::create([
                    'action' => 'State',
                    'details' => $activityDetails,
                    'performed_by' => $assigneName,
                    'request_type' => 'Incident Request',
                    'request_id' => $task->snow_id,
                    ]);

                $this->closeClosedModal();
                $this->updateCounts();
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




public $showViewImageDialog = false;
public $currentIncidentId;
public $showViewFileDialog = false;
public function closeViewFile()
{
    $this->showViewFileDialog = false;
}
public function showViewImage($incidentId)
{
    $this->currentIncidentId = $incidentId;
    $this->showViewImageDialog = true;
}


public function showViewFile($incidentId)
{
    $this->currentIncidentId = $incidentId;
    $this->showViewFileDialog = true;
}


public function closeViewImage()
{
    $this->showViewImageDialog = false;
}


public function downloadImages($incidentId)
{
    try {
        $incident = collect($this->allIncidentDetails)->firstWhere('id', $incidentId);

        if (!$incident) {
            // incident not found
            return response()->json(['message' => 'incident not found'], 404);
        }

        $fileDataArray = is_string($incident->file_paths)
            ? json_decode($incident->file_paths, true)
            : $incident->file_paths;

        // Filter images
        $images = array_filter(
            $fileDataArray,
            function ($fileData) {
                // Ensure 'mime_type' key exists and is valid
                return isset($fileData['mime_type']) && strpos($fileData['mime_type'], 'image') !== false;
            }
        );

        // If only one image, provide direct download
        if (count($images) === 1) {
            $image = reset($images); // Get the single image
            $base64File = $image['data'];
            $mimeType = $image['mime_type'];
            $originalName = $image['original_name'];

            // Decode base64 content
            $fileContent = base64_decode($base64File);

            // Return the image directly
            return response()->stream(
                function () use ($fileContent) {
                    echo $fileContent;
                },
                200,
                [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'attachment; filename="' . $originalName . '"',
                ]
            );
        }

        // If multiple images, create a ZIP file
        if (count($images) > 1) {
            $zipFileName = 'images.zip';
            $zip = new \ZipArchive();
            $zip->open(storage_path($zipFileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            foreach ($images as $image) {
                $base64File = $image['data'];
                $mimeType = $image['mime_type'];
                $extension = explode('/', $mimeType)[1];
                $imageName = uniqid() . '.' . $extension;

                $zip->addFromString($imageName, base64_decode($base64File));
            }

            $zip->close();

            return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
        }

        // If no images, return an appropriate response
        return response()->json(['message' => 'No images found'], 404);
    } catch (\Exception $e) {
        // Handle any exception that occurs and return a proper response
        return response()->json(['message' => 'An error occurred while processing the images', 'error' => $e->getMessage()], 500);
    }
}



    public $incidentOpenCount;
    public $incidentPendingCount;
    public $incidentprogressCount;
    public $incidentClosedCount;
    public $allIncidentDetails;

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


        private function formatElapsedTime($days, $hours, $minutes)
    {
        if ($days > 0) {
            return "{$days} days {$hours} hours {$minutes} minutes";
        } elseif ($hours > 0) {
            return "{$hours} hours {$minutes} minutes";
        } else {
            return "{$minutes} minutes";
        }
    }

    public function render()
    {


        $this->allIncidentDetails =IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Incident Request')
        ->get();

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

        // $this->incidentClosedDetails = IncidentRequest::with('emp')
        // ->orderBy('created_at', 'desc')
        // ->where('category', 'Incident Request')
        // ->whereIn('status_code', ['11','15'])
        // ->get();


        $this->updateCounts();
        $this->itData = IT::with('empIt')->get();

        return view('livewire.incident-requests',[
            'incidentRequestDetails' => $this->incidentRequestDetails,
        ]);
    }


}
