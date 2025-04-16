<?php

namespace App\Livewire;

use App\Exports\ServiceExport;
use App\Helpers\FlashMessageHelper;
use App\Mail\assigneRequestMail;
use App\Mail\cancelRequestMail;
use App\Mail\statusRequestMail;
use App\Models\ActivityLog;
use App\Models\EmployeeDetails;
use App\Models\IncidentRequest;
use App\Models\IT;
use App\Models\ServiceRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ServiceRequests extends Component
{
    use WithFileUploads;
    public $loading = false;
    public $employeeInitials;
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
    public $selectedStatus ='';
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


    protected $queryString = ['currentRequestId'];

    public function mount($id = null){

        $this->loadClosedRecordsByAssigne();
        $this->loadInprogessRecordsByAssigne();

        $this->loadPendingRecordsByAssigne();
        $this->loadLogs();


        if ($id) {
            $this->currentRequestId = $id;
        }

        if ($this->currentRequestId) {
            $this->viewServiceDetails($this->currentRequestId);
        }
    }



    public $exportFormat = 'excel'; // Default format
    public $requestId=''; // Filter for a specific request ID
    public $assignee = '';  // Filter by assignee
    public $category;


    public function  exportRequests($statusCodes)
    {
        $statusCodes = explode(',', $statusCodes);
        // Build the query with mandatory filters
        $query = IncidentRequest::query()
            ->where('category', 'Service Request') // Mandatory filter: Category
            ->whereIn('status_code', $statusCodes); // Mandatory filter: Status Code

        // Apply optional filters for request ID and assignee
        if ($this->requestId) {
            $query->where('snow_id', $this->requestId); // Filter by Request ID (assuming 'snow_id' is correct field)
        }

        if ($this->assignee) {
            $query->where('ser_assign_to', $this->assignee); // Filter by Assignee
        }

        // Retrieve the filtered records
        $records = $query->get();

        // Check if records exist
        if ($records->isEmpty()) {
            FlashMessageHelper::flashError('No data found ');
            return;
        }

        // Export data based on selected format
        if (in_array($this->exportFormat, ['excel', 'csv'])) {
            return Excel::download(new ServiceExport($records), "services." . ($this->exportFormat === 'excel' ? 'xlsx' : 'csv'));
        }

        if ($this->exportFormat === 'pdf') {
            $pdf = Pdf::loadView('exports.services', ['records' => $records]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'services.pdf');
        }

        $this->reset(['requestId', 'assignee', 'exportFormat']);
    }



    public function clearFilters()
    {

        $this->reset(['exportFormat', 'requestId', 'assignee']);
    }


    public function bulkSubmitStatusReason()
    {

        if (count($this->selectedRequests) === 0) {
            FlashMessageHelper::flashError( 'No requests selected.');
            return;
        }

        $this->validate([
            'pendingReason' => 'required|string|max:255',
        ]);

        foreach ($this->selectedRequests as $requestId) {
            $task = IncidentRequest::find($requestId);

            if ($task && $this->modalPurpose) {
                if ($this->modalPurpose === 'Pending') {
                    $task->update(['ser_pending_notes' => $this->pendingReason]);

                    $employee = auth()->guard('it')->user();
                    $assignedAssignee = EmployeeDetails::where('emp_id', $task->emp_id)->first();
                    $employeeEmail = $assignedAssignee->email;
                    $employeeName = $task->emp->first_name . ' ' . $task->emp->last_name;
                    $shortDescription = $task->description;

                    Mail::to($employeeEmail)->send(new statusRequestMail(
                        $employeeName,
                        $task->request_id,
                        $this->pendingReason,
                        $shortDescription,
                        $task->category,
                        'Pending'
                    ));

                    $activityDetails = "Work in Progress was Pending for Request ID - {$task->snow_id}";

                    ActivityLog::create([
                        'action' => 'State',
                        'details' => $activityDetails,
                        'performed_by' => $employee->employee_name,
                        'request_type' => 'Incident Request',
                        'request_id' => $task->snow_id,
                    ]);
                } elseif ($this->modalPurpose === 'Cancel') {
                    $task->update([
                        'ser_cancel_notes' => $this->pendingReason,
                        'ser_end_date' => now(),
                    ]);

                    $employee = auth()->guard('it')->user();
                    $assignedAssignee = EmployeeDetails::where('emp_id', $task->emp_id)->first();
                    $activityDetails = "Work in Progress was Cancelled for Request ID - {$task->snow_id}";

                    ActivityLog::create([
                        'action' => 'State',
                        'details' => $activityDetails,
                        'performed_by' => $employee->employee_name,
                        'request_type' => 'Incident Request',
                        'request_id' => $task->snow_id,
                    ]);

                } elseif ($this->modalPurpose === 'Completed') {
                    $task->update([
                        'ser_completed_notes' => $this->pendingReason,
                        'ser_end_date' => now(),
                    ]);

                    $employee = auth()->guard('it')->user();
                    $assignedAssignee = EmployeeDetails::where('emp_id', $task->emp_id)->first();

                    $activityDetails = "Work in Progress was Completed for Request ID - {$task->snow_id}";

                    ActivityLog::create([
                        'action' => 'State',
                        'details' => $activityDetails,
                        'performed_by' => $employee->employee_name,
                        'request_type' => 'Incident Request',
                        'request_id' => $task->snow_id,
                    ]);
                }

                // Common Activity Log for all statuses
                ActivityLog::create([
                    'action' => $this->modalPurpose,
                    'details' => $this->pendingReason,
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Incident Request',
                    'request_id' => $task->snow_id,
                ]);
            } else {
                FlashMessageHelper::flashError("Task not found or invalid status for Request ID: $requestId");
            }
        }

        if ($this->modalPurpose === 'Pending') {
            FlashMessageHelper::flashSuccess("Status has been set to Pending");

        }elseif ($this->modalPurpose === 'Completed') {

            FlashMessageHelper::flashSuccess("Status has been set to Completed");

        }elseif ($this->modalPurpose === 'Cancel') {
            FlashMessageHelper::flashSuccess("Status has been set to Cancelled");

        }

        $this->closeStatusModal1();
    }


      // ACTIVE TAB............................


        public $selectedRequests = []; // Stores selected request IDs
        public $bulkAssignee = '';   // Tracks selected Assignee
        public $bulkStatus = null;     // Tracks selected Status

        public $checkboxModal = false;
        public $checkboxPendingModal = false;
        public $checkboxClosingModal = false;
        public function checkboxMultiSelection(){

            $this->checkboxModal = count($this->selectedRequests) > 0;
        }

        public function checkboxPendingMultiSelection(){

            $this->checkboxPendingModal = count($this->selectedRequests) > 0;
        }

        public function checkboxClosingMultiSelection(){

            $this->checkboxClosingModal = count($this->selectedRequests) > 0;
        }


        public function applyBulkActions()
    {

        if (count($this->selectedRequests) === 0) {
            FlashMessageHelper::flashError( 'No requests selected.');
            return;
        }


        if (!$this->bulkAssignee) {
            FlashMessageHelper::flashError( 'Please select an assignee .');
            return;
        }
        if (!$this->selectedStatus) {
            FlashMessageHelper::flashError( 'Please select an status.');
            return;
        }

        $this->loading = true;

        try {
            // Perform bulk operations here (e.g., updating the database)
            foreach ($this->selectedRequests as $requestId) {
                $request = IncidentRequest::find($requestId); // Replace with your model

                if ($this->bulkAssignee) {
                    $request->inc_assign_to = $this->bulkAssignee;
                }
                if ($this->selectedStatus) {
                    $request->status_code = $this->selectedStatus;
                }
                $request->save();


            }

            $this->selectedAssigne ='';
            $this->selectedRequests = [];
                $this->selectedStatus ='';
                $this->checkboxModal = false;
            FlashMessageHelper::flashSuccess('Requests updated successfully.');
            $this->resetSelection();
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('Failed to update requests: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    public function resetSelection()
    {
        $this->selectedRequests = [];
        $this->selectedStatus = null;
        $this->bulkStatus = null;
    }

      // PENDING TAB............................

    public function bulkSelectedInprogress()
    {


        $this->showBulkInprogressModal =true;
        $this->updateCounts();
    }

    public function closeBulkInprogressModal()
    {
        $this->showBulkInprogressModal = false;
        $this->reset(['pendingReason', 'pendingRequestId','modalPurpose']);
    }

    public function bulkSubmitReason()
    {

        if (count($this->selectedRequests) === 0) {
            FlashMessageHelper::flashError( 'No requests selected.');
            return;
        }

        $this->validate([
            'pendingReason' => 'required|string|max:255',
            ]);


            foreach ($this->selectedRequests as $requestId) {

                $task = IncidentRequest::find($requestId);

                if (!$task) {
                    continue; // Skip this iteration if task is not found
                }

                $employee = auth()->guard('it')->user();

                if($this->pendingReason){

                    ActivityLog::create([
                        'action' => "Inprogress Notes",
                        'details' => $this->pendingReason,
                        'performed_by' => $employee->employee_name,
                        'request_type' => 'Service Request',
                        'request_id' => $task->snow_id,
                        ]);

                }

                if ($task) {
                    if ($task->ser_progress_since === null) {
                        // If it's the first time switching to InProgress, set the current time
                        $task->ser_progress_since = now();
                    }

                    $task->update([
                        'ser_inprogress_notes' => $this->pendingReason,
                        'status_code' => 16
                        ]);

                        $employee = auth()->guard('it')->user();

                        $activityDetails = '';
                        // Flash a success message based on the selected status
                        $activityDetails = "Work in Progress was Inprogress for Request ID - {$task->snow_id}";

                        $assigneName = $employee->employee_name;
                        ActivityLog::create([
                        'action' => 'State',
                        'details' => $activityDetails,
                        'performed_by' => $assigneName,
                        'request_type' => 'Service Request',
                        'request_id' => $task->snow_id,
                        ]);
                    }
                }

                if ($task) {
                    FlashMessageHelper::flashSuccess("Status has been set to Inprogress");

                }

                $this->selectedStatus='';
                $this->selectedRequests = [];
                $this->checkboxPendingModal = false;
                $this->closeBulkInprogressModal();
                $this->updateCounts();

    }


    // INPROGRESS TAB............................

    public $showBulkPendingModal = false;
    public $showBulkClosedModal = false;

    public function handleBulkInprogressStatus($value){


        if($value == 5){

        $this->showBulkPendingModal = true;

        }
        elseif($value == 11){
            $this->showBulkClosedModal = true;

        }
    }

    public function closeBulkPendingModal()
    {
        $this->showBulkPendingModal = false;
        $this->reset(['pendingReason', 'pendingRequestId','modalPurpose']);
    }

    public function closeBulkClosedModal()
    {
        $this->showBulkClosedModal = false;
        $this->reset(['pendingReason', 'pendingRequestId','modalPurpose','customerVisibleNotes']);
    }

    public function bulkPendingForDesks()
        {

            if (count($this->selectedRequests) === 0) {
                FlashMessageHelper::flashError( 'No requests selected.');
                return;
            }

            $this->validate([
                'pendingReason' => 'required|string|max:255',
                ]);

        foreach ($this->selectedRequests as $requestId) {
            $task = IncidentRequest::find($requestId);

            $employee = auth()->guard('it')->user();

            if($this->pendingReason){

                ActivityLog::create([
                    'action' => "Pending Notes",
                    'details' => $this->pendingReason,
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Service Request',
                    'request_id' => $task->snow_id,
                    ]);

            }

            if ($task) {
                $elapsedTime = \Carbon\Carbon::parse($task->ser_progress_since)->diffInMinutes(now());

                // Update the total in-progress time and set status to Pending
                $task->total_ser_progress_time += $elapsedTime;  // Add elapsed time to total

                $task->update([
                    'ser_pending_notes' => $this->pendingReason,
                    'status_code' => 5,
                    'ser_progress_since' => null,
                    ]);

                    $employee = auth()->guard('it')->user();

                    $activityDetails = '';
                    // Flash a success message based on the selected status
                    $activityDetails = "Work in Progress was Pending for Request ID - {$task->snow_id}";

                    $assigneName = $employee->employee_name;
                    ActivityLog::create([
                    'action' => 'State',
                    'details' => $activityDetails,
                    'performed_by' => $assigneName,
                    'request_type' => 'Service Request',
                    'request_id' => $task->snow_id,
                    ]);


            }
        }
        $this->checkboxModal = false;
        $this->selectedRequests = [];
        $this->selectedRecord=[];
        $this->checkboxClosingModal = false;

        $this->closeBulkPendingModal();
        $this->updateCounts();

        if($task){
            FlashMessageHelper::flashSuccess("Status has been set to Pending");
        }


    }

    public function bulkCloseForDesks()
    {

        if (count($this->selectedRequests) === 0) {
            FlashMessageHelper::flashError( 'No requests selected.');
            return;
        }

            $this->validate([
                'pendingReason' => 'required|string|max:255',
                'customerVisibleNotes' => 'required|string|max:255',
                ]);
        foreach ($this->selectedRequests as $requestId) {

            $task = IncidentRequest::find($requestId);

            $employee = auth()->guard('it')->user();

            if($this->pendingReason && $this->customerVisibleNotes){

                ActivityLog::create([
                    'action' => "Closed Notes",
                    'details' => "Closed Notes: {$this->pendingReason} ||| Customer Visible Notes: {$this->customerVisibleNotes}",
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Service Request',
                    'request_id' => $task->snow_id,
                    ]);

            }

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
                    'ser_progress_since' => null, // Reset the progress start time
                    'ser_end_date' => now(),
                    'ser_completed_notes' => $this->pendingReason,
                    'ser_customer_visible_notes' => $this->customerVisibleNotes,
                    ]);

                    $employee = auth()->guard('it')->user();

                    $activityDetails = '';
                    // Flash a success message based on the selected status
                    $activityDetails = "Work in Progress was Closed for Request ID - {$task->snow_id}";


                    $assigneName = $employee->employee_name;
                    ActivityLog::create([
                    'action' => 'State',
                    'details' => $activityDetails,
                    'performed_by' => $assigneName,
                    'request_type' => 'Service Request',
                    'request_id' => $task->snow_id,
                    ]);

            }
        }
        $this->selectedRecord ='';
        $this->selectedRequests = [];
        $this->checkboxClosingModal = false;
        $this->closeBulkClosedModal();
        $this->updateCounts();

            if($task){
                FlashMessageHelper::flashSuccess("Status has been set to Closed");
            }


    }



    public function updatedCurrentRequestId($id)
    {
        $this->viewServiceDetails($id);
    }



    public function viewServiceDetails($id)
    {

        try {
            $this->serviceRequest =  IncidentRequest::with('emp')->find($id);
            $this->selectedAssigne = '';
            $this->selectedStatus = '';
            $this->comments = '';
            // Check if the selected request exists
            if (!$this->serviceRequest) {
                abort(404, 'Request not found');
            }

            $this->serviceRequestDetails = true;

         $requestedBy= EmployeeDetails::where('emp_id' ,  $this->serviceRequest->emp_id)->first();
         $fullName = ucwords(strtolower($requestedBy->first_name . ' ' . $requestedBy->last_name));
            ActivityLog::create([
                'impact' => 'High',
                'opened_by' =>  $fullName ,
                'priority' =>  $this->serviceRequest->priority,
                'state' => "Open",
                'performed_by' =>  $fullName,
                'request_type' => 'Service Request',
                'request_id' => $this->serviceRequest->snow_id,
            ]);

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in viewserviceDetails method", [
                'exception' => $e,
                'index' => $id,
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
        return redirect()->route('serviceRequests');
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
    public $snowId;
    public $filterType;
    public $activityLogs;

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

    // Filter logs based on type
    public function filterLogs($type)
    {
        $this->filterType = $type;
        $this->loadLogs($this->snowId); // Re-load logs with the new filter
    }

    public $showPopup = false;
    public $serviceIDHeader = '';

    public function loadLogs($snowId = null)
    {

        if ($snowId) {

            $this->snowId = $snowId;
            $this->serviceIDHeader =   $this->snowId;
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

    public function closePopup()
{
    $this->showPopup = false;
    $this->activityLogs = null; // Clear logs if needed
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
        $this->reset(['pendingReason', 'pendingRequestId','modalPurpose','selectedStatus']);
        // $this->selectedStatus = '';
    }

    public function closeStatusModal1()
    {
        $this->showStatusModal = false;
        $this->reset(['pendingReason', 'pendingRequestId','modalPurpose']);
        // $this->selectedStatus = '';
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
        'ser_pending_notes' => $this->pendingReason
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
        'request_type' => 'Service Request',
        'request_id' => $task->snow_id,
        ]);

        }

        elseif ($this->modalPurpose === 'Cancel') {
        // Logic for Cancel
        $cancelRequest = IncidentRequest::with('emp')->where('id', $this->pendingRequestId)->first();

        $task->update(['ser_cancel_notes' => $this->pendingReason,
                        'ser_end_date' => now()]);

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
        'request_type' => 'Service Request',
        'request_id' => $task->snow_id,
        ]);

        FlashMessageHelper::flashSuccess("Status has been set to Cancelled");

        } elseif ($this->modalPurpose === 'Completed') {


        $task->update([
        'ser_completed_notes' => $this->pendingReason,
        'ser_end_date' => now(),
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
        'request_type' => 'Service Request',
        'request_id' => $task->snow_id,
        ]);

        }



        ActivityLog::create([
        'action' => $this->modalPurpose,
        'details' => $this->pendingReason,
        'performed_by' => $employee->employee_name,
        'request_type' => 'Service Request',
        'request_id' => $task->snow_id,
        ]);


        $this->closeStatusModal1();


        } else {
        // Handle case where the task was not found or no status is selected
        FlashMessageHelper::flashError("Task not found or invalid status.");
        }



    }







    public function activeServiceSubmit($taskId)
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


            $task->update(['ser_assign_to' => $this->selectedAssigne]);


            ActivityLog::create([
                'action' => 'Assigned to',
                'details' => "{$fullName}",
                'performed_by' => $assigneName , // Assuming user is logged in
                'request_type' => 'Service Request',
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
                $activityDetails = "Work in Progress was Pending for Service ID - {$task->snow_id}";
            }elseif ($this->selectedStatus === '16') {

                $task->ser_progress_since = now();  // Set the current timestamp
                $task->save();
                $activityDetails = "Work in Progress was Inprogress for Service ID - {$task->snow_id}";

            }
             elseif ($this->selectedStatus === '11') {
                $task->update([
                    'ser_end_date' => now(), // Update the end date
                ]);
            } elseif ($this->selectedStatus === '15') {
                $task->update([
                    'ser_end_date' => now(), // Update the end date
                ]);
            }


        $assigneName = $employee->employee_name;
        ActivityLog::create([
            'action' => 'State',
            'details' => $activityDetails,
            'performed_by' => $assigneName,
            'request_type' => 'Service Request',
            'request_id' => $task->snow_id,
        ]);

            $this->updateCounts();
        } else {
            // Handle case where the task was not found or no status is selected
            FlashMessageHelper::flashError("Task not found or invalid status.");
        }


        FlashMessageHelper::flashSuccess("Request submitted successfully");
        $this->viewingDetails = false;
        $this->viewServiceRequest = true;
        $this->serviceRequestDetails = false;
        $this->reset(['selectedStatus', 'selectedAssigne']);
        $this->resetErrorBag();
        $this->updateCounts();
        return redirect()->route('serviceRequests');

    }



    public function postComment($taskId)
{
    $this->validate([
        'comments' => 'required|string|max:500',
    ]);
    try {
        // Find the task by taskId
        $task = IncidentRequest::find($taskId);

        // Check if task exists and a comment is provided
        if ($task && $this->comments) {

            // Update the task with the comment
            $task->update(['active_ser_comment' => $this->comments]);

            // Flash a success message
            $employee = auth()->guard('it')->user(); // Get the logged-in user
            ActivityLog::create([
                'action' => 'Active Comment',
                'details' => "$this->comments",
                'performed_by' => $employee->employee_name,
                'request_type' => 'Service Request',
                'request_id' => $task->snow_id,
            ]);

            FlashMessageHelper::flashSuccess("Comment posted successfully!");
            $this->resetValidation();
        }  else {
            FlashMessageHelper::flashError("Task not found.");
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
    public $showBulkInprogressModal = false;


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


            $task = IncidentRequest::find($this->selectedTaskId);

            $employee = auth()->guard('it')->user();

            if($this->pendingReason){

                ActivityLog::create([
                    'action' => "Inprogress Notes",
                    'details' => $this->pendingReason,
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Service Request',
                    'request_id' => $task->snow_id,
                    ]);

            }

            if ($task) {
                if ($task->ser_progress_since === null) {
                    // If it's the first time switching to InProgress, set the current time
                    $task->ser_progress_since = now();
                }

                $task->update([
                    'ser_inprogress_notes' => $this->pendingReason,
                    'status_code' => 16
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
                    'request_type' => 'Service Request',
                    'request_id' => $task->snow_id,
                    ]);

                $this->closeInprogressModal();
                $this->updateCounts();
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
            $this->reset(['pendingReason', 'pendingRequestId','modalPurpose','selectedStatus']);
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
                    'request_type' => 'Service Request',
                    'request_id' => $task->snow_id,
                    ]);

            }

            if ($task) {
                $elapsedTime = \Carbon\Carbon::parse($task->ser_progress_since)->diffInMinutes(now());

                // Update the total in-progress time and set status to Pending
                $task->total_ser_progress_time += $elapsedTime;  // Add elapsed time to total

                $task->update([
                    'ser_pending_notes' => $this->pendingReason,
                    'status_code' => 5,
                    'ser_progress_since' => null,
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
                    'request_type' => 'Service Request',
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
                    'request_type' => 'Service Request',
                    'request_id' => $task->snow_id,
                    ]);

            }

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
                    'ser_progress_since' => null, // Reset the progress start time
                    'ser_end_date' => now(),
                    'ser_completed_notes' => $this->pendingReason,
                    'ser_customer_visible_notes' => $this->customerVisibleNotes,
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
                    'request_type' => 'Service Request',
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




            public $statusFilter = '';



    public $statusPenFilterAssigne = '';
    public $statusInproFilterAssigne = '';
    public $statusClsdFilterAssigne = '';
    public $itAssigneMemebers = [];



    public function loadPendingRecordsByAssigne()
    {
        // Fetch the assignee members based on department and status


        try {
            // Start the query to load incident details with status code 16
            $query = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Service Request')
            ->where('status_code', 5);


            // Apply filter by the selected assignee if provided
            if ($this->statusPenFilterAssigne) {

                $query->where('ser_assign_to', $this->statusPenFilterAssigne); // Filter by the selected Employee ID
            } else {
                // If no filter is selected, default to showing records with status code 16
                $query->where('status_code', 5);
            }

            // Execute the query and fetch the records
            $this->servicePendingDetails = $query->get();

        } catch (\Exception $e) {
            // Log any error that occurs during the query execution
            Log::error("Error loading incident details: " . $e->getMessage(), ['exception' => $e]);
            FlashMessageHelper::flashError('An error occurred while loading incident details.');
            $this->servicePendingDetails = collect(); // Set to an empty collection in case of an error
        }
    }



    public function loadClosedRecordsByAssigne()
    {
        try {
            Log::info("Status Filter: " . $this->statusFilter);  // Debugging
            Log::info("Assignee Filter: " . $this->statusClsdFilterAssigne);  // Debugging

            // Start building the query to load incident closed details
            $query = IncidentRequest::with('emp')
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->where('category', 'Service Request')
                ->whereIn('status_code', [11, 15]); // Default to completed and cancelled status

            // Apply the status filter if selected
            if ($this->statusFilter) {
                if ($this->statusFilter == '11') {
                    $query->where('status_code', 11); // Completed
                } elseif ($this->statusFilter == '15') {
                    $query->where('status_code', 15); // Cancelled
                }
            }

            // Apply the assignee filter if selected
            if ($this->statusClsdFilterAssigne) {

                $query->where('ser_assign_to', $this->statusClsdFilterAssigne); // Filter by employee ID
            }

            // Execute the query and fetch the records
            $this->serviceClosedDetails = $query->get();

        } catch (\Exception $e) {
            Log::error("Error loading incident closed details: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            FlashMessageHelper::flashError('An error occurred while loading incident details.');
            $this->serviceClosedDetails = collect(); // Set to an empty collection in case of an error
        }
    }




    public function loadInprogessRecordsByAssigne()
{
    // Fetch the assignee members based on department and status

    try {
        // Start the query to load incident details with status code 16
        $query = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Service Request')
            ->where('status_code', 16);


        // Apply filter by the selected assignee if provided
        if ($this->statusInproFilterAssigne) {

            $query->where('ser_assign_to', $this->statusInproFilterAssigne); // Filter by the selected Employee ID
        } else {
            // If no filter is selected, default to showing records with status code 16
            $query->where('status_code', 16);
        }

        // Execute the query and fetch the records
        $this->serviceInprogressDetails = $query->get();

    } catch (\Exception $e) {
        // Log any error that occurs during the query execution
        Log::error("Error loading incident details: " . $e->getMessage(), ['exception' => $e]);
        FlashMessageHelper::flashError('An error occurred while loading incident details.');
        $this->serviceInprogressDetails = collect(); // Set to an empty collection in case of an error
    }
}




public function handleSelectedAssigneChange()
{
    // Reset validation for the field
    $this->resetValidationForField('selectedAssigne');

    // Call the SelectedAssigne method
    $this->SelectedAssigne();
}


public function handleSelectedStatusChange()
{
    // Reset validation for the field
    $this->resetValidationForField('selectedStatus');

    // Call the SelectedStatus method
    $this->SelectedStatus();
}

public function resetValidationForField($field)
{
    // Reset error for the specific field when typing
    $this->resetErrorBag($field);
}


    public $serviceOpenCount;
    public $servicePendingCount;
    public $serviceInprogressCount;
    public $serviceClosedCount;

    public function updateCounts()
    {
        try {
            // Fetch categories for IT requests

            $this->serviceOpenCount = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Service Request')
            ->where('status_code', 10)
            ->count();

            $this->servicePendingCount = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Service Request')
            ->where('status_code', 5)
            ->count();

            $this->serviceInprogressCount = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Service Request')
            ->where('status_code', 16)
            ->count();

            $this->serviceClosedCount = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Service Request')
            ->whereIn('status_code',['11','15'])
            ->count();


        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error("Error occurred while updating counts", [
                'exception' => $e,
            ]);

            // Optionally, set all counts to zero or handle the error gracefully
            $this->serviceOpenCount = 0;
            $this->servicePendingCount = 0;
            $this->serviceInprogressCount = 0;
            $this->serviceClosedCount = 0;
            // Flash an error message to inform the user
            FlashMessageHelper::flashError("An error occurred while updating the request counts.");
        }
    }




public $showViewImageDialog = false;
public $currentServiceId;
public $showViewFileDialog = false;

public $allServiceDetails;


public function closeViewFile()
{
    $this->showViewFileDialog = false;
}
public function showViewImage($serviceId)
{
    $this->currentServiceId = $serviceId;
    $this->showViewImageDialog = true;
}


public function showViewFile($serviceId)
{
    $this->currentServiceId = $serviceId;
    $this->showViewFileDialog = true;
}


public function closeViewImage()
{
    $this->showViewImageDialog = false;
}



public $showViewEmpFileDialog = false;
public $showViewEmpImageDialog = false;


public function showViewEmpImage($serviceId)
{
    $this->currentServiceId = $serviceId;
    $this->showViewEmpImageDialog = true;
}



public function showViewEmpFile($serviceId)
{
    $this->currentServiceId = $serviceId;
    $this->showViewEmpFileDialog = true;
}


public function closeViewEmpImage()
{
    $this->showViewEmpImageDialog = false;
}

public function closeViewEmpFile()
{
    $this->showViewEmpFileDialog = false;
}



public function downloadITImages($serviceId)
{

    try {
        $service = collect($this->allServiceDetails)->firstWhere('id', $serviceId);

        if (!$service) {
            // service not found
            return response()->json(['message' => 'Service not found'], 404);
        }

        $fileDataArray = is_string($service->it_file_paths)
            ? json_decode($service->it_file_paths, true)
            : $service->it_file_paths;

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





public $previews=[];
public $all_files = [];
public $it_file_paths = [];
public $showSuccessMsg=false;

public function updatedItFilePaths($value, $recordId)
{

// Assuming it_file_paths is an array of files for a specific request ID
foreach ($this->it_file_paths[$recordId] as $file) {
    // Ensure no duplicate files are added
    $existingFileNames = array_map(function ($existingFile) {
        return $existingFile->getClientOriginalName();
    }, $this->all_files);

    // Check if the file is already in the list
    if (!in_array($file->getClientOriginalName(), $existingFileNames)) {
        // Append only new files to all_files
        $this->all_files[] = $file;

        try {
            // Generate previews only for the new file
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'])) {
                $base64Image = base64_encode(file_get_contents($file->getRealPath()));
                $this->previews[] = [
                    'url' => 'data:' . $file->getMimeType() . ';base64,' . $base64Image,
                    'type' => 'image',
                    'name' => $file->getClientOriginalName(),
                ];
            } else {
                $this->previews[] = [
                    'type' => 'file',
                    'name' => $file->getClientOriginalName(),
                ];
            }
        } catch (\Throwable $th) {
            Log::error('Error generating preview:', [
                'file' => $file->getClientOriginalName(),
                'error' => $th->getMessage(),
            ]);
        }
    }
}

$this->showSuccessMsg = true;
$this->showFilePreviewModal = true;
$this->selectedRecordId = $recordId;

}

public $showFilePreviewModal = false;



    public function hideFilePreviewModal()
{
    // Hide the modal by setting the property to false
    $this->showFilePreviewModal = false;
}

public function  hideSuccessMsg(){
    $this->showSuccessMsg=false;
}

public function removeFile($index)
{
    if (isset($this->all_files[$index])) {
        unset($this->all_files[$index]);
        unset($this->previews[$index]);
        $this->all_files = array_values($this->all_files);
        $this->previews = array_values($this->previews);
    }

}


public $selectedRecordId = null;  // Add this to store the selected record's ID

// When opening the file preview modal


public function uploadFiles($selectedRecordId)
{

    $this->it_file_paths = $this->all_files;

    $attachments = IncidentRequest::find($selectedRecordId);

    // Initialize fileDataArray
    $fileDataArray = [];


    if ($this->it_file_paths) {
        foreach ($this->it_file_paths as $file) {
            $mimeType = $file->getMimeType();

            // Check if the file is an image
            if (strpos($mimeType, 'image/') === 0) {
                // Validate image file size
                if ($file->getSize() > 1024 * 1024) { // 1 MB in bytes
                    FlashMessageHelper::flashError("The image {$file->getClientOriginalName()} exceeds the 1 MB size limit.");
                    return; // Stop further processing
                }
            } else {
                // Validate non-image file size
                if ($file->getSize() >  100 * 1024 * 1024) { // 500 MB in bytes
                    FlashMessageHelper::flashError("The file {$file->getClientOriginalName()} exceeds the 500 MB size limit.");
                    return; // Stop further processing
                }
            }
        }

        // Validate files based on their types
        $this->validate([
            'it_file_paths.*' => [
                'nullable',
                'file',
                'mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif',
                'max:512000', // Maximum size in KB (500 MB for general validation)
            ],
        ]);
    }



    if ($this->it_file_paths) {
        // Validate files
        $this->validate([
            'it_file_paths.*' => 'nullable|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif|max:40960',
        ]);


        // Process each file
        foreach ($this->it_file_paths as $file) {
            try {
                if ($file->isValid()) {
                    $fileContent = file_get_contents($file->getRealPath());
                    $mimeType = $file->getMimeType();
                    $base64File = base64_encode($fileContent);

                    // Add new file to the array
                    $fileDataArray[] = [
                        'data' => $base64File,
                        'mime_type' => $mimeType,
                        'original_name' => $file->getClientOriginalName(),
                    ];
                } else {
                    Log::error('File is not valid:', ['file' => $file->getClientOriginalName()]);
                }
            } catch (\Exception $e) {
                Log::error('Error processing file:', [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ]);
            }
        }
    }


    // If the record exists, update the file paths

    if ($attachments) {
        try {
            // Log before update
            Log::info('Attempting to update attachments', [
                'attachment_id' => $attachments->id ?? null, // Log the ID if available
                'existing_it_file_paths' => $attachments->it_file_paths, // Log existing file paths
                'new_it_file_paths' => $fileDataArray, // Log the new file data array
            ]);

            // Perform the update
            // dd(json_encode($fileDataArray));

            $existingFiles = json_decode($attachments->it_file_paths, true) ?? [];
            $allFiles = array_merge($existingFiles, $fileDataArray);

            $attachments->it_file_paths=json_encode($allFiles);
            $attachments->save();


            $employee = auth()->guard('it')->user();
                $assigneName = $employee->employee_name;
                ActivityLog::create([
                    'request_id' => $attachments->snow_id, // Assuming this is the request ID
                    'description' => "Uploaded file: {$file->getClientOriginalName()}",
                    'performed_by' => $assigneName,
                    'attachments' => json_encode($fileDataArray)
                ]);


            // Log successful update
            Log::info('Attachments updated successfully', [
                'attachment_id' => $attachments->id ?? null,
                'updated_it_file_paths' => json_encode($fileDataArray),
            ]);

            $this->previews=[];
            $this->all_files = [];
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Error updating attachments', [
                'error_message' => $e->getMessage(),
                'attachment_id' => $attachments->id ?? null,
                'new_it_file_paths' => $fileDataArray,
            ]);

            // Optionally, rethrow the exception if needed
            throw $e;
        }
    }



    // Optional: return a success message or redirect
    FlashMessageHelper::flashSuccess("Files uploaded successfully!");
    $this->showFilePreviewModal = false;

}




public function downloadImages($serviceId)
{
    try {
        $service = collect($this->allServiceDetails)->firstWhere('id', $serviceId);

        if (!$service) {
            // service not found
            return response()->json(['message' => 'service not found'], 404);
        }

        $fileDataArray = is_string($service->file_paths)
            ? json_decode($service->file_paths, true)
            : $service->file_paths;

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





    public function render()
    {

        $this->loadClosedRecordsByAssigne();
        $this->loadInprogessRecordsByAssigne();

        $this->loadPendingRecordsByAssigne();

        $this->itAssigneMemebers = EmployeeDetails::where('sub_dept_id', '9915')
        ->where('dept_id', '8803')
        ->where('status', 1)
        ->orderBy('first_name', 'asc')
        ->get();


        $this->allServiceDetails =IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Service Request')
        ->get();


        $this->serviceDetails = IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Service Request')
        ->where('status_code', 10)
        ->get();

        // $this->servicePendingDetails = IncidentRequest::with('emp')
        // ->orderBy('created_at', 'desc')
        // ->where('category', 'Service Request')
        // ->where('status_code', 5)
        // ->get();

        // $this->serviceInprogressDetails = IncidentRequest::with('emp')
        // ->orderBy('created_at', 'desc')
        // ->where('category', 'Service Request')
        // ->where('status_code', 16)
        // ->get();

        // $this->serviceClosedDetails = IncidentRequest::with('emp')
        // ->orderBy('created_at', 'desc')
        // ->where('category', 'Service Request')
        // ->whereIn('status_code',['11','15'])
        // ->get();

        $this->updateCounts();
        $this->itData = IT::with('empIt')->get();
        return view('livewire.service-requests');
    }

}
