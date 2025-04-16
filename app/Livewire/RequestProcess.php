<?php

namespace App\Livewire;

use App\Exports\CatalogExport;
use App\Helpers\FlashMessageHelper;
use App\Mail\ApproveRequestMail;
use App\Mail\assigneRequestMail;
use App\Mail\cancelRequestMail;
use App\Mail\RejectRequestMail;
use App\Mail\statusRequestMail;
use App\Models\ActivityLog;
use App\Models\EmployeeDetails;
use App\Models\HelpDesks;
use App\Models\HolidayCalendar;
use App\Models\Request;
use App\Models\IT;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Client\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\TextUI\Help;

use function Termwind\render;

class RequestProcess extends Component
{
    use WithFileUploads;
    public $activeTab = 'active';
    public $requests = [];
    public $viewingDetails = false;
    public $recentrequestDetails = false;
    public $rejectedrequestDetails = false;
    public $viewRecentRequests = true;
    public $viewRejectedRequests = false;
    public $viewEmpRequest = false;
    public $showPopup = false;
    public $assignTo;
    public $comments;
    public $remarks =[];
    public $request;
    public $selectedRequest;
    public $recentRequest;
    public $cancelRequest;
    public $rejectedRequest;
    public $showOverview = false;
    public $showRejectionModal = false;
    public $showRejectionBulkRqsModal = false;
    public $showCancelModal = false;
    public $attachments;
    public $currentRequestId;
    public $currentCatalogId;
    public $newRequestCount;
    public $newRejectionCount;
    public $activeCount;
    public $pendingCount;
    public $inprogressCount;
    public $closedCount;
    public $file_path;
    public $pendingReason;
    public $selectedStatus;
    public $selectedAssigne;


    protected $queryString = ['currentCatalogId'];


    protected $rules = [
        'request.assignTo' => 'required',
        'comments' => 'required',
        'request.status' => 'required',
        'remarks' => 'required',
        'selectedStatus' => 'required',
        'selectedAssigne' => 'required',
    ];


    public function setActiveTab($tab)
    {

        $this->activeTab = $tab;
        $this->viewingDetails = false;
        $this->recentrequestDetails = false;
        $this->rejectedrequestDetails = false;

        $this->selectedStatus = '';
        $this->selectedAssigne = '';
        $this->updateCounts();
    }

    public $employee ;

    public function mount($id = null)
    {

        try {
            // Get the authenticated user
            $employee = auth()->user();

            if ($id) {
                $this->currentCatalogId = $id;
            }

            if ($this->currentCatalogId) {
                $this->viewApproveDetails($this->currentCatalogId);
            }

            // Set flags based on user role
            if (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))) {
                // Admin or super admin user settings
                $this->viewRecentRequests = true; // User can view recent requests
                $this->viewRejectedRequests = false; // User can view recent requests
            } else {
                // Non-admin user settings
                $this->viewRecentRequests = false; // User cannot view recent requests
                $this->viewRejectedRequests = false; // User cannot view rejected requests
                $this->recentrequestDetails = false; // No request details available
                $this->rejectedrequestDetails = false; // No request details available
                $this->viewEmpRequest = true; // User can view their own requests
            }

            // Initialize other properties
            $this->selectedStatus = '';
            $this->selectedAssigne = '';

            // Update counts
            $this->updateCounts();

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in mount method", [
                'exception' => $e,
                'user' => auth()->check() ? auth()->user()->id : 'Guest',
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while initializing the request details.");

            // Optionally, reset or set default values in case of an error
            $this->viewRecentRequests = false;
            $this->viewRejectedRequests = false;
            $this->recentrequestDetails = false;
            $this->rejectedrequestDetails = false;
            $this->viewEmpRequest = true; // Default to showing employee requests
        }
    }



    public $exportFormat = 'excel'; // Default format
    public $requestId=''; // Filter for a specific request ID
    public $assignee = '';  // Filter by assignee
    public $category;


    public function  exportRequests($statusCodes)
    {

        $requestCategories = Request::select('Request', 'category')
        ->where('Request', 'IT') // Adjust this to match the condition for IT requests
        ->pluck('category');

    // Fetch HelpDesk records based on the category and companyId

        $statusCodes = explode(',', $statusCodes);
        // Build the query with mandatory filters
        $query = HelpDesks::query()
            ->whereIn('category',  $requestCategories)
            ->whereIn('status_code', $statusCodes); // Mandatory filter: Status Code

        // Apply optional filters for request ID and assignee
        if ($this->requestId) {
            $query->where('request_id', $this->requestId); // Filter by Request ID (assuming 'snow_id' is correct field)
        }

        if ($this->assignee) {
            $query->where('assign_to', $this->assignee); // Filter by Assignee
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
            return Excel::download(new CatalogExport($records), "catalog." . ($this->exportFormat === 'excel' ? 'xlsx' : 'csv'));
        }

        if ($this->exportFormat === 'pdf') {
            $pdf = Pdf::loadView('exports.catalog', ['records' => $records]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'catalog.pdf');
        }

        $this->reset(['requestId', 'assignee', 'exportFormat']);
    }


    public function clearFilters()
    {
        $this->reset(['exportFormat', 'requestId', 'assignee']);
    }


    public $selectedRequests = []; // Stores selected request IDs
    public $bulkAssignee = '';   // Tracks selected Assignee
    public $bulkStatus = null;     // Tracks selected Status

    public $checkboxModal = false;
    public $checkboxNewRqsModal = false;
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

    public function checkboxNewRqsMultiSelection(){

        $this->checkboxNewRqsModal = count($this->selectedRequests) > 0;
    }




        public function handleBulkNewRqsStatusChange()
    {
        if ($this->selectedStatus == '10') {
            $this->approveBulkNewRqsStatus();
        } elseif ($this->selectedStatus == '3') {
            $this->rejectionBulkRqsModal();
        }
    }



    public function rejectBulkRqsStatus()
{
    $this->validate([
        'reason' => 'required|string|max:255', // Validate the remark input
    ], [
        'reason.required' => 'Reason is required.',
    ]);

    try {
        foreach ($this->selectedRequests as $requestId) {
            $recentRequest = HelpDesks::with('emp')->where('id', $requestId)->first();

            if ($recentRequest) {
                // Update the request with rejection reason and status
                $recentRequest->update([
                    'rejection_reason' => $this->reason,
                    'status_code' => '3'
                ]);

                $employee = auth()->guard('it')->user();
                $employeeEmail = $recentRequest->mail;

                $employeeName = $recentRequest->emp->first_name . ' ' . $recentRequest->emp->last_name;
                $shortDescription = $recentRequest->description;
                $RejetedEmployeeName = ucwords(strtolower($employee->employee_name));

                // Send rejection email
                Mail::to($employeeEmail)->send(new RejectRequestMail(
                    $employeeName,
                    $this->reason,
                    $recentRequest->request_id,
                    $shortDescription,
                    $RejetedEmployeeName,
                    $recentRequest->category
                ));

                // Log the activity
                $activityDetails = "Request was Rejected by {$RejetedEmployeeName} for Request ID - {$recentRequest->request_id}";
                FlashMessageHelper::flashSuccess("Request {$recentRequest->request_id} has been rejected, and email has been sent!");

                ActivityLog::create([
                    'action' => 'State',
                    'details' => $activityDetails,
                    'performed_by' => $RejetedEmployeeName,
                    'request_type' => 'Catalog Request',
                    'request_id' => $recentRequest->request_id,
                ]);

                // Update counts after each iteration
                $this->updateCounts();
            } else {
                // Handle case when the request is not found
                FlashMessageHelper::flashError("Request with ID {$requestId} not found.");
            }
        }

        // Reset the selection and reason after processing
        $this->selectedRequests = [];
        $this->selectedStatus = '';
        $this->reason = '';
        $this->updateCounts();
        $this->showRejectionBulkRqsModal = false;
    } catch (\Exception $e) {
        // Log the exception for debugging
        Log::error("Error occurred in rejectBulkRqsStatus method", [
            'exception' => $e,
        ]);

        // Flash an error message for the user
        FlashMessageHelper::flashError("An error occurred while rejecting the requests.");
    }
}


    public function rejectionBulkRqsModal()
    {

        $this->showRejectionBulkRqsModal =true;

    }


    public function approveBulkNewRqsStatus()
{
    if (empty($this->selectedRequests)) {
        FlashMessageHelper::flashError("No requests selected for approval.");
        return back()->withErrors(['error' => 'No requests selected for approval.']);
    }



    foreach ($this->selectedRequests as $requestId) {
        // Fetch the task with its associated employee
        $task = HelpDesks::with('emp')->find($requestId);

        if (!$task) {
            Log::error("Request with ID {$requestId} not found.");
            continue; // Skip this iteration if task not found
        }

        // Fetch admin details
        $admindetails = EmployeeDetails::with('its')
            ->whereHas('its', function ($query) {
                $query->where('role', 'admin');
            })
            ->get();

        if ($admindetails->isEmpty()) {
            Log::error("No admin details found for request ID: {$requestId}");
            FlashMessageHelper::flashError("No admin details available for some requests.");
            continue; // Skip this iteration if no admin details found
        }

        // Validate admin email
        $adminEmail = preg_replace('/\s+/', '', $admindetails[0]->email);
        if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            Log::error("Invalid admin email address for request ID: {$requestId}");
            FlashMessageHelper::flashError("Invalid email address for some requests.");
            continue; // Skip this iteration if invalid email
        }

        // Construct email details
        $employeeName = $task->emp->first_name . ' ' . $task->emp->last_name;
        $employee = auth()->guard('it')->user();
        $requestId = $task->request_id;
        $category = $task->category;
        $shortDescription = $task->description ?? 'N/A';
        $approvedBy = ucwords(strtolower($employee->employee_name));

        // Send approval email
        try {
            Mail::to($adminEmail)->send(new ApproveRequestMail(
                $employeeName,
                $approvedBy,
                $requestId,
                $shortDescription,
                $category
            ));
        } catch (\Exception $e) {
            Log::error("Failed to send approval email for request ID: {$requestId}. Error: {$e->getMessage()}");
            FlashMessageHelper::flashError("Failed to send approval email for some requests.");
            continue; // Skip this iteration if email fails
        }

        // Update request status and log activity
        $task->update(['status_code' => '10']); // Status 10 for Approved
        $activityDetails = "Request was Approved by {$approvedBy} for Request ID - {$task->request_id}";
        ActivityLog::create([
            'action' => 'State',
            'details' => $activityDetails,
            'performed_by' => $approvedBy,
            'request_type' => 'Catalog Request',
            'request_id' => $task->request_id,
        ]);
    }

    // Update counts and reset selected requests
    $this->updateCounts();
    $this->selectedRequests = [];

    FlashMessageHelper::flashSuccess("Selected requests have been approved successfully!");
    return redirect()->route('requests');
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
            $task = HelpDesks::find($requestId);

            if ($task && $this->modalPurpose) {
                if ($this->modalPurpose === 'Pending') {
                    $task->update(['pending_notes' => $this->pendingReason]);

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

                    $activityDetails = "Work in Progress was Pending for Request ID - {$task->request_id}";

                    ActivityLog::create([
                        'action' => 'State',
                        'details' => $activityDetails,
                        'performed_by' => $employee->employee_name,
                        'request_type' => 'Catalogue Request',
                        'request_id' => $task->request_id,
                    ]);
                } elseif ($this->modalPurpose === 'Cancel') {
                    $task->update([
                        'cancel_notes' => $this->pendingReason,
                        'req_end_date' => now(),
                    ]);

                    $employee = auth()->guard('it')->user();
                    $assignedAssignee = EmployeeDetails::where('emp_id', $task->emp_id)->first();
                    $activityDetails = "Work in Progress was Cancelled for Request ID - {$task->request_id}";

                    ActivityLog::create([
                        'action' => 'State',
                        'details' => $activityDetails,
                        'performed_by' => $employee->employee_name,
                        'request_type' => 'Catalogue Request',
                        'request_id' => $task->request_id,
                    ]);

                } elseif ($this->modalPurpose === 'Completed') {
                    $task->update([
                        'closed_notes' => $this->pendingReason,
                        'req_end_date' => now(),
                    ]);

                    $employee = auth()->guard('it')->user();
                    $assignedAssignee = EmployeeDetails::where('emp_id', $task->emp_id)->first();

                    $activityDetails = "Work in Progress was Completed for Request ID - {$task->request_id}";

                    ActivityLog::create([
                        'action' => 'State',
                        'details' => $activityDetails,
                        'performed_by' => $employee->employee_name,
                        'request_type' => 'Catalogue Request',
                        'request_id' => $task->request_id,
                    ]);
                }

                // Common Activity Log for all statuses
                ActivityLog::create([
                    'action' => $this->modalPurpose,
                    'details' => $this->pendingReason,
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Catalogue Request',
                    'request_id' => $task->request_id,
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
        $this->updateCounts();
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
            $request = HelpDesks::find($requestId); // Replace with your model

            if ($this->bulkAssignee) {
                $request->assign_to = $this->bulkAssignee;
            }
            if ($this->selectedStatus) {
                $request->status_code = $this->selectedStatus;
            }
            $request->save();


        }
        $this->selectedAssigne ='';
        $this->selectedStatus ='';
        $this->selectedRecord=[];
        $this->selectedRequests = [];
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

    public $showBulkInprogressModal = false;

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

                $task = HelpDesks::find($requestId);

                if (!$task) {
                    continue; // Skip this iteration if task is not found
                }

                $employee = auth()->guard('it')->user();
                Log::info("Found Task for Request ID: {$requestId}, Pending Reason: {$this->pendingReason}");


                if ($task) {

                    if ($task->cat_progress_since === null) {
                        // If it's the first time switching to InProgress, set the current time
                        $task->cat_progress_since = now();
                    }

                    $updated = $task->update([
                        'inprogress_notes' => $this->pendingReason,
                        'status_code' => 16,
                    ]);

                    Log::info("Update Result for Request ID {$task->request_id}: " . ($updated ? 'Success' : 'Failed'));

                    $task->refresh();
                    Log::info("Inprogress Notes after Refresh for Request ID {$task->request_id}: {$task->inprogress_notes}");


                        ActivityLog::create([
                            'action' => "Inprogress Notes",
                            'details' =>  $task->inprogress_notes,
                            'performed_by' => $employee->employee_name,
                            'request_type' => 'Catalogue Request',
                            'request_id' => $task->request_id,
                            ]);

                        $employee = auth()->guard('it')->user();

                        $activityDetails = '';
                        // Flash a success message based on the selected status
                        $activityDetails = "Work in Progress was Inprogress for Request ID - {$task->request_id}";

                        $assigneName = $employee->employee_name;
                        ActivityLog::create([
                        'action' => 'State',
                        'details' => $activityDetails,
                        'performed_by' => $assigneName,
                        'request_type' => 'Catalogue Request',
                        'request_id' => $task->request_id,
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
            $task = HelpDesks::find($requestId);

            $employee = auth()->guard('it')->user();

            if($this->pendingReason){

                ActivityLog::create([
                    'action' => "Pending Notes",
                    'details' => $this->pendingReason,
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Catalogue Request',
                    'request_id' => $task->request_id,
                    ]);

            }

            if ($task) {
                $elapsedTime = \Carbon\Carbon::parse($task->cat_progress_since)->diffInMinutes(now());

                // Update the total in-progress time and set status to Pending
                $task->total_cat_progress_time += $elapsedTime;  // Add elapsed time to total

                $task->update([
                    'pending_notes' => $this->pendingReason,
                    'status_code' => 5,
                    'cat_progress_since' => null,
                    ]);

                    $employee = auth()->guard('it')->user();

                    $activityDetails = '';
                    // Flash a success message based on the selected status
                    $activityDetails = "Work in Progress was Pending for Request ID - {$task->request_id}";

                    $assigneName = $employee->employee_name;
                    ActivityLog::create([
                    'action' => 'State',
                    'details' => $activityDetails,
                    'performed_by' => $assigneName,
                    'request_type' => 'Catalogue Request',
                    'request_id' => $task->request_id,
                    ]);

            }
        }
        $this->checkboxPendingModal = false;
        $this->selectedRecord ='';
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

            $task = HelpDesks::find($requestId);

            $employee = auth()->guard('it')->user();

            if($this->pendingReason && $this->customerVisibleNotes){

                ActivityLog::create([
                    'action' => "Closed Notes",
                    'details' => "Closed Notes: {$this->pendingReason} ||| Customer Visible Notes: {$this->customerVisibleNotes}",
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Catalogue Request',
                    'request_id' => $task->request_id,
                    ]);

            }

            if ($task) {

                $totalElapsedMinutes = 0;

                if ($task->cat_progress_since) {
                    $totalElapsedMinutes = \Carbon\Carbon::parse($task->cat_progress_since)->diffInMinutes(now());
                }

                // Add the previously tracked progress time if exists
                if (isset($task->total_cat_progress_time)) {
                    $totalElapsedMinutes += $task->total_cat_progress_time;
                }

                $task->update([
                    'status_code' => '11', // Closed
                    'total_cat_progress_time' => $totalElapsedMinutes, // Store the total progress time
                    'cat_progress_since' => null, // Reset the progress start time
                    'req_end_date' => now(),
                    'closed_notes' => $this->pendingReason,
                    'customer_visible_notes' => $this->customerVisibleNotes,
                    ]);

                    $employee = auth()->guard('it')->user();

                    $activityDetails = '';
                    // Flash a success message based on the selected status
                    $activityDetails = "Work in Progress was Closed for Request ID - {$task->request_id}";


                    $assigneName = $employee->employee_name;
                    ActivityLog::create([
                    'action' => 'State',
                    'details' => $activityDetails,
                    'performed_by' => $assigneName,
                    'request_type' => 'Catalogue Request',
                    'request_id' => $task->request_id,
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


    // END INPROGRESS............................................


    public $statusFilter = '';



    public $catalogPendingDetails = '';
    public $catalogInprogressDetails = '';
    public $catalogClosedDetails = '';
    public $statusPenFilterAssigne = '';
    public $statusInproFilterAssigne = '';
    public $statusClsdFilterAssigne = '';
    public $itAssigneMemebers = [];





    public function loadPendingRecordsByAssigne()
    {
        // Fetch the assignee members based on department and status

        try {

            $requestCategories = Request::select('Request', 'category')
            ->where('Request', 'IT') // Adjust this to match the condition for IT requests
            ->pluck('category');

            // Fetch IT data (empIt related data)
            $this->itData = IT::with('empIt')->get();

            // Start the query to load Catalogue details with status code 16

                $query = HelpDesks::with('emp')
                    ->where('status_code', '5')
                    ->whereIn('category', $requestCategories)
                    ->orderBy($this->sortColumn, $this->sortDirection)
                    ->orderBy('created_at', 'desc');


            // Apply filter by the selected assignee if provided
            if ($this->statusPenFilterAssigne) {

                $query->where('assign_to', $this->statusPenFilterAssigne); // Filter by the selected Employee ID
            } else {
                // If no filter is selected, default to showing records with status code 16
                $query->where('status_code', 5);
            }

            // Execute the query and fetch the records
            $this->catalogPendingDetails = $query->get();

        } catch (\Exception $e) {
            // Log any error that occurs during the query execution
            Log::error("Error loading Catalog details: " . $e->getMessage(), ['exception' => $e]);
            FlashMessageHelper::flashError('An error occurred while loading Catalog details.');
            $this->catalogPendingDetails = collect(); // Set to an empty collection in case of an error
        }
    }


    public function loadInprogessRecordsByAssigne()
    {
        // Fetch the assignee members based on department and status

        try {

            $requestCategories = Request::select('Request', 'category')
            ->where('Request', 'IT') // Adjust this to match the condition for IT requests
            ->pluck('category');

            // Fetch IT data (empIt related data)
            $this->itData = IT::with('empIt')->get();

            // Start the query to load Catalogue details with status code 16

                $query =  $this->forIT = HelpDesks::with('emp')
                ->where('status_code', '16')
                ->whereIn('category', $requestCategories)
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->orderBy('created_at', 'desc');



            // Apply filter by the selected assignee if provided
            if ($this->statusInproFilterAssigne) {

                $query->where('assign_to', $this->statusInproFilterAssigne); // Filter by the selected Employee ID
            } else {
                // If no filter is selected, default to showing records with status code 16
                $query->where('status_code', 16);
            }

            // Execute the query and fetch the records
            $this->catalogInprogressDetails = $query->get();

        } catch (\Exception $e) {
            // Log any error that occurs during the query execution
            Log::error("Error loading Catalog details: " . $e->getMessage(), ['exception' => $e]);
            FlashMessageHelper::flashError('An error occurred while loading Catalog details.');
            $this->catalogInprogressDetails = collect(); // Set to an empty collection in case of an error
        }
    }



    public function loadClosedRecordsByAssigne()
    {
        // Fetch the assignee members based on department and status

        try {

            $requestCategories = Request::select('Request', 'category')
            ->where('Request', 'IT') // Adjust this to match the condition for IT requests
            ->pluck('category');

            // Fetch IT data (empIt related data)
            $this->itData = IT::with('empIt')->get();

            // Start the query to load Catalogue details with status code 16

                $query = HelpDesks::with('emp')
                ->whereIn('status_code', ['11', '15'])
                    ->whereIn('category',  $requestCategories)
                    ->orderBy($this->sortColumn, $this->sortDirection)
                    ->orderBy('created_at', 'desc')
                   ;


                   if ($this->statusFilter) {
                    if ($this->statusFilter == '11') {
                        $query->where('status_code', 11); // Completed
                    } elseif ($this->statusFilter == '15') {
                        $query->where('status_code', 15); // Cancelled
                    }
                }


            // Apply filter by the selected assignee if provided
            if ($this->statusClsdFilterAssigne) {
                $query->where('assign_to', $this->statusClsdFilterAssigne); // Filter by the selected Employee ID
            }

            // Execute the query and fetch the records
            $this->catalogClosedDetails = $query->get();

        } catch (\Exception $e) {
            // Log any error that occurs during the query execution
            Log::error("Error loading Catalog details: " . $e->getMessage(), ['exception' => $e]);
            FlashMessageHelper::flashError('An error occurred while loading Catalog details.');
            $this->catalogClosedDetails = collect();  // Set to an empty collection in case of an error
        }
    }





    public function updatedCurrentCatalogId($id)
    {
        $this->viewApproveDetails($id);
    }

    public function viewApproveDetails($id)
    {
        try {
            $this->comments = '';
            $this->recentRequest = HelpDesks::find($id);

            // Check if the selected request exists
            if (!$this->recentRequest) {
                abort(404, 'Request not found');
            }

            $this->recentrequestDetails = true;

            $requestedBy= EmployeeDetails::where('emp_id' ,  $this->recentRequest->emp_id)->first();
            $fullName = ucwords(strtolower($requestedBy->first_name . ' ' . $requestedBy->last_name));
               ActivityLog::create([
                   'impact' => 'High',
                   'opened_by' =>  $fullName ,
                   'priority' =>  $this->recentRequest->priority,
                   'state' => "Recent",
                   'performed_by' =>  $fullName,
                   'request_type' => $this->recentRequest->category,
                   'request_id' => $this->recentRequest->request_id,
               ]);


        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in viewApproveDetails method", [
                'exception' => $e,
                'index' => $id,
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while viewing the approved request.");

            // Optionally, reset properties in case of error
            $this->recentrequestDetails = false;
            $this->currentCatalogId = null;
        }
    }





    public function showAllRequest() {
        $this->viewRecentRequests = false;
        $this->viewRejectedRequests = false;
        $this->viewEmpRequest = true;
    }

    public function showRejectedRequest() {
        $this->viewRecentRequests = false;
        $this->viewRejectedRequests = true;
        $this->viewEmpRequest = false;
    }

    public function showRecentRequest() {
        $this->viewRecentRequests = true;
        $this->viewRejectedRequests = false;
        $this->viewEmpRequest = false;
    }

    public function showAttachments($requestId)
    {
        $request = collect($this->requests)->firstWhere('id', $requestId);
        $this->attachments = explode(',', $request['attach_files']);
    }

    public function getInProgressRequestsProperty()
    {
        return array_filter($this->requests, function ($request) {

            return $request['status_code'] == '5';
        });
    }
    public function getClosedRequestsProperty()
    {
        return array_filter($this->requests, function ($request) {
            return $request['status_code'] == '11';
        });
    }

    public function viewRejectDetails($index)
    {
        try {
            $this->comments = '';
            $this->rejectedRequest = $this->rejectDetails->where('status_code', '3')->values()->get($index);

            // Check if the selected request exists
            if (!$this->rejectedRequest) {
                abort(404, 'Request not found');
            }

            $this->rejectedrequestDetails = true;
            $this->currentRequestId = $this->rejectedRequest->id;

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in viewRejectDetails method", [
                'exception' => $e,
                'index' => $index,
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while viewing the rejected request.");

            // Optionally, reset properties in case of error
            $this->rejectedrequestDetails = false;
            $this->currentRequestId = null;
        }
    }




    public function viewDetails($index)
    {
        try {
            $this->comments = '';
            $this->selectedAssigne = '';
            $this->selectedStatus = '';
            $this->selectedRequest = $this->forIT->where('status_code', '10')->values()->get($index);
            $this->viewingDetails = true;

            // Check if the selected request exists
            if (!$this->selectedRequest) {
                abort(404, 'Request not found');
            }

            $this->currentRequestId = $this->selectedRequest->id;
            $file_path = $this->selectedRequest->file_path;

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in viewDetails method", [
                'exception' => $e,
                'index' => $index,
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while viewing the request details.");

            // Optionally, reset properties in case of error
            $this->viewingDetails = false;
            $this->currentRequestId = null;
        }
    }


    public function toggleOverview()
    {
        $this->showOverview = !$this->showOverview;
    }


    public function closeDetailsBack()
    {
        $this->viewingDetails = false;
        $this->viewRecentRequests = false;
        $this->recentrequestDetails = false;

        // $this->mount();
        // $this->selectedRequest = true;
    }

    public function closeDetails()
    {
        $this->viewingDetails = false;
        $this->recentrequestDetails = false;

        $this->mount();
        return redirect()->route('requests');
        // $this->selectedRequest = true;
    }

    public function closeRejectDetails()
    {

        $this->viewingDetails = false;
        $this->viewRejectedRequests = true;
        $this->rejectedrequestDetails = false;


        $this->viewEmpRequest = false;

        $this->mount();
        // $this->selectedRequest = true;
    }


    public function openForDesks($taskId)
    {
        $task = HelpDesks::find($taskId);

        if ($task) {
            $task->update(['status_code' => '11',
                  'req_end_date' => now(),
                ]);
            FlashMessageHelper::flashSuccess("Status Closed successfully!");

            $employee = auth()->guard('it')->user(); // Get the logged-in user
    ActivityLog::create([
        'action' => 'State',
        'details' => "Work in Progress was Closed for Request ID - {$task->request_id}",
        'performed_by' => $employee->employee_name,
        'request_type' => 'Catalog Request',
        'request_id' => $task->request_id,
    ]);

        }

        $this->updateCounts();
    }
    public $error = '';
    public $loading = false;

    public function approveStatus($taskId)
    {

        $task = HelpDesks::with('emp')->where('id', $taskId)->find($taskId);

        $admindetails = EmployeeDetails::with('its')
        ->whereHas('its', function ($query) {
            $query->where('role', 'admin');
        })
        ->get();

        if ($admindetails->isEmpty()) {
            Log::error("No admin details found.");
            FlashMessageHelper::flashError("No admin details available. ");

            return back()->withErrors(['error' => 'No admin details available.']);
        }

        $adminEmail = $admindetails[0]->email;

        $adminEmail = preg_replace('/\s+/', '', $adminEmail); // Removes all whitespace characters
        if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            // Log or handle the invalid email scenario
            FlashMessageHelper::flashError("Invalid email address: ");
            Log::error("Invalid email address: " . $adminEmail);
            return back()->withErrors(['error' => 'Invalid email address.']);
        }


        if (empty($adminEmail)) {
            Log::error("No email address provided for request ID: " . $admindetails[0]->request_id);
            return back()->withErrors(['error' => 'No email address associated with this request.']);
        }


        $employeeName = $task ->emp->first_name . ' ' . $task ->emp->last_name;

        $employee = auth()->guard('it')->user();
        $requestId = $task->request_id;

        $category = $task->category;
        $shortDescription = $task->description; // Assuming this field exists
        $RejetedEmployeeName =  ucwords(strtolower($employee->employee_name));
    // Send rejection email
       Mail::to($adminEmail)->send(new ApproveRequestMail(
        $employeeName,
        $RejetedEmployeeName ,
        $requestId,
        $shortDescription,
        $category,

    ));


        if ($task) {
            $activityDetails = '';
            // Set the status to "Open" when approving
            $task->update(['status_code' => '10']);
            $activityDetails = "Request was Approved by {$RejetedEmployeeName} for Request ID - {$task->request_id}";
            FlashMessageHelper::flashSuccess("Request has been approved, and email has been sent!");
            ActivityLog::create([
                'action' => 'State',
                'details' => $activityDetails,
                'performed_by' => $RejetedEmployeeName,
                'request_type' => 'Catalog Request',
                'request_id' => $task->request_id,
            ]);
            $this->updateCounts();
        }
        return redirect()->route('requests');

    }

    public $recordId;
    public $reason = [];

    public function rejectionModal($taskId)
    {
        $this->recordId = $taskId;
        $this->showRejectionModal =true;

    }

    public function cancelModal($taskId)
    {
        $this->recordId = $taskId;
        $this->showCancelModal =true;

    }

    public $priority;

    public function selectPriority($id, $value)
{
    try {
        // Find the request by ID
        $this->recentRequest = HelpDesks::findOrFail($id);

        // Update the priority field in the database
        $this->recentRequest->priority = $value;
        $this->recentRequest->save();

        // Log the activity
        $employee = auth()->guard('it')->user();
        $RejetedEmployeeName = ucwords(strtolower($employee->employee_name));

        $activityDetails = "Priority has been changed to {$value} for Request ID - {$this->recentRequest->request_id}";
        ActivityLog::create([
            'action' => 'State',
            'details' => $activityDetails,
            'performed_by' => $RejetedEmployeeName,
            'request_type' => 'Catalog Request',
            'priority' => $value,
            'request_id' => $this->recentRequest->request_id,
        ]);

        // Flash a success message
        FlashMessageHelper::flashSuccess("Priority updated successfully!");
    } catch (\Exception $e) {
        FlashMessageHelper::flashError("Failed to update priority.");
        Log::error("Error updating priority: " . $e->getMessage());
    }
}



         public $showPendingModal = false;
         public $pendingRequestId = '';
        public function handleStatusChange($requestId)
    {
        if ($this->selectedStatus == '5') { // Pending
            $this->showPendingModal = true;
            $this->pendingRequestId = $requestId;

        } elseif ($this->selectedStatus == '15') { // Cancel
            $this->cancelModal($requestId);
        } else {
            $this->updateStatus($requestId);
        }
    }





    public function cancelStatus()
    {
        $this->validate([

            'reason' => 'required|string|max:255', // Validate the remark input
        ], [
            'reason.required' => 'Reason is required.',
        ]);


        try {

            $cancelRequest = HelpDesks::with('emp')->where('id', $this->recordId)->first();

            if ($cancelRequest) {

                if ($cancelRequest) {
                    $cancelRequest->update([
                        'status_code' => '15',
                        'rejection_reason' => $this->reason,
                        'req_end_date' => now(),

                    ]);
                }


                // Set the status to "Reject" when rejecting the request

                $employee = auth()->guard('it')->user();
                $employeeEmail = $cancelRequest->mail;  // The input string
                $rejectionReason = $this->reason;
                $Id = $cancelRequest->request_id;
            // Send rejection email
               Mail::to($employeeEmail)->send(new cancelRequestMail(
               $cancelRequest,
               $Id,
               $employee,
               $rejectionReason,
            ));
            FlashMessageHelper::flashSuccess("Request has been canceled, and email has been sent!");

                $this->updateCounts();

                $this->showCancelModal = false;

                // Reset the recordId and reason after processing
                $this->recordId = null;
                $this->reason = '';
            } else {
                // Handle case when the request is not found
                FlashMessageHelper::flashError("Request not found.");
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in rejectStatus method", [
                'exception' => $e,
                'recordId' => $this->recordId,
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while rejecting the request.");
        }
    }

    public function rejectStatus()
    {
        $this->validate([

            'reason' => 'required|string|max:255', // Validate the remark input
        ], [
            'reason.required' => 'Reason is required.',
        ]);

        try {
            $recentRequest = HelpDesks::with('emp')->where('id', $this->recordId)->first();

            if ($recentRequest) {

                if ($recentRequest) {
                    $recentRequest->update([
                        'rejection_reason' => $this->reason,
                        'status_code' => '3'
                    ]);
                }
                // Set the status to "Reject" when rejecting the request


                $employee = auth()->guard('it')->user();
                $employeeEmail = $recentRequest->mail;  // The input string




                // Output the matched IDs

                $employeeName = $recentRequest->emp->first_name . ' ' . $recentRequest->emp->last_name;

                $requestId = $recentRequest->request_id;

                $shortDescription = $recentRequest->description; // Assuming this field exists

                $RejetedEmployeeName =  ucwords(strtolower($employee->employee_name));

            // Send rejection email
               Mail::to($employeeEmail)->send(new RejectRequestMail(
                $employeeName,
                $this->reason,
                $requestId,
                $shortDescription,
                $RejetedEmployeeName,
                $recentRequest->category,


            ));

            $activityDetails = '';
            // Set the status to "Open" when approving

            $activityDetails = "Request was Rejected by {$RejetedEmployeeName} for Request ID - {$recentRequest->request_id}";

            FlashMessageHelper::flashSuccess("Request has been rejected, and email has been sent!");

            ActivityLog::create([
                'action' => 'State',
                'details' => $activityDetails,
                'performed_by' => $RejetedEmployeeName,
                'request_type' => 'Catalog Request',
                'request_id' => $recentRequest->request_id,
            ]);

                $this->updateCounts();

                $this->showRejectionModal = false;

                // Reset the recordId and reason after processing
                $this->recordId = null;
                $this->reason = '';
            } else {
                // Handle case when the request is not found
                FlashMessageHelper::flashError("Request not found.");
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in rejectStatus method", [
                'exception' => $e,
                'recordId' => $this->recordId,
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while rejecting the request.");
        }
    }


    public function Cancel()
    {

        $this->showRejectionModal = false;
        $this->showRejectionBulkRqsModal = false;
        $this->showCancelModal = false;
    }



    public $modalPurpose = '';
    public $showStatusModal = false;




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




    public function closeStatusModal()
    {

        $this->showStatusModal = false;

        
        $this->reset(['pendingReason', 'pendingRequestId','modalPurpose','selectedStatus']);


    }


    public function closeStatusModal1()
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
        $task = HelpDesks::find($this->pendingRequestId);

        if ($task && $this->modalPurpose) {

        if ($this->modalPurpose === 'Pending') {

        $task->update([
        'pending_notes' => $this->pendingReason
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
        $activityDetails = "Work in Progress was Pending for Request ID - {$task->request_id}";
        FlashMessageHelper::flashSuccess("Status has been set to Pending");


        $assigneName = $employee->employee_name;
        ActivityLog::create([
        'action' => 'State',
        'details' => $activityDetails,
        'performed_by' => $assigneName,
        'request_type' => 'Catalog Request',
        'request_id' => $task->request_id,
        ]);

        }

        elseif ($this->modalPurpose === 'Cancel') {
        // Logic for Cancel
        $cancelRequest = HelpDesks::with('emp')->where('id', $this->pendingRequestId)->first();

        $task->update(['cancel_notes' => $this->pendingReason,
                        'req_end_date' => now()]);

        $employee = auth()->guard('it')->user();

        $assignedAssigne = EmployeeDetails::where('emp_id' , $task->emp_id )->get();

        $employeeEmail = $assignedAssigne[0]->email;

        $employeeName = $task->emp->first_name . ' ' . $task->emp->last_name;

        $requestId = $task->request_id;
        $shortDescription = $task->description; // Assuming this field exists
        $pendingReason = $this->pendingReason;


        Mail::to($employeeEmail)->send(new statusRequestMail(
            $employeeName,
            $requestId,
            $pendingReason,
            $shortDescription,
            $task->category,
            'Cancel' // Passing a flag for Pending
            ));


        $activityDetails = '';
        // Flash a success message based on the selected status


        $activityDetails = "Work in Progress was Cancelled for Request ID - {$task->request_id}";

        $employee = auth()->guard('it')->user();
        $assigneName = $employee->employee_name;
        ActivityLog::create([
        'action' => 'State',
        'details' => $activityDetails,
        'performed_by' => $assigneName,
        'request_type' => 'Catalog Request',
        'request_id' => $task->request_id,
        ]);

        FlashMessageHelper::flashSuccess("Status has been set to Cancelled");

        } elseif ($this->modalPurpose === 'Completed') {


        $task->update([
        'closed_notes' => $this->pendingReason,
        'req_end_date' => now(),
        ]);

        $employee = auth()->guard('it')->user();

        $assignedAssigne = EmployeeDetails::where('emp_id' , $task->emp_id )->get();

        $employeeEmail = $assignedAssigne[0]->email;

        $employeeName = $task->emp->first_name . ' ' . $task->emp->last_name;

        $requestId = $task->request_id;
        $shortDescription = $task->description; // Assuming this field exists
        $pendingReason = $this->pendingReason;

        Mail::to($employeeEmail)->send(new statusRequestMail(
            $employeeName,
            $requestId,
            $pendingReason,
            $shortDescription,
            $task->category,
            'Closed' // Passing a flag for Pending
            ));


        $activityDetails = '';
        // Flash a success message based on the selected status
        $activityDetails = "Work in Progress was Completed for Request ID - {$task->request_id}";
        FlashMessageHelper::flashSuccess("Status has been set to Completed");


        $assigneName = $employee->employee_name;
        ActivityLog::create([
        'action' => 'State',
        'details' => $activityDetails,
        'performed_by' => $assigneName,
        'request_type' => 'Catalog Request',
        'request_id' => $task->request_id,
        ]);

        }




        ActivityLog::create([
        'action' => $this->modalPurpose,
        'details' => $this->pendingReason,
        'performed_by' => $employee->employee_name,
        'request_type' => 'Catalog Request',
        'request_id' => $task->request_id,
        ]);


        $this->closeStatusModal1();


        } else {
        // Handle case where the task was not found or no status is selected
        FlashMessageHelper::flashError("Task not found or invalid status.");
        }



    }



    public function activeCatalogSubmit($taskId)
    {

        $this->validate([
            'selectedStatus' => 'required',

            'selectedAssigne' => 'required',
        ], [
            'selectedStatus.required' => 'Status is required.',
            'selectedAssigne.required' => 'Assign to is required.',
        ]);


        $task = HelpDesks::find($taskId);
        $employee = auth()->guard('it')->user();
        $snowId = $task->request_id;

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


            $task->update(['assign_to' => $this->selectedAssigne]);


            ActivityLog::create([
                'action' => 'Assigned to',
                'details' => "{$fullName}",
                'performed_by' => $assigneName , // Assuming user is logged in
                'request_type' => 'Catalog Request',
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
            $task = HelpDesks::find($taskId);

            $employee = auth()->guard('it')->user();
            $requestId = $task->request_id;
            $shortDescription = $task->description; // Assuming this field exists
            $activityDetails = '';
            // Flash a success message based on the selected status
            if ($this->selectedStatus === '5') {
                $activityDetails = "Work in Progress was Pending for Request ID - {$task->request_id}";
            }elseif ($this->selectedStatus === '16') {

                $task->cat_progress_since = now();  // Set the current timestamp
                $task->save();
                $activityDetails = "Work in Progress was Inprogress for Request ID - {$task->request_id}";

            }
             elseif ($this->selectedStatus === '11') {

            } elseif ($this->selectedStatus === '15') {

            }


        $assigneName = $employee->employee_name;
        ActivityLog::create([
            'action' => 'State',
            'details' => $activityDetails,
            'performed_by' => $assigneName,
            'request_type' => 'Catalog Request',
            'request_id' => $task->request_id,
        ]);

            $this->updateCounts();
        } else {
            // Handle case where the task was not found or no status is selected
            FlashMessageHelper::flashError("Task not found or invalid status.");
        }

        FlashMessageHelper::flashSuccess("Request submitted successfully");
        $this->viewRecentRequests = false;
        $this->recentrequestDetails = false;
        $this->viewingDetails = false;
        $this->reset(['selectedStatus', 'selectedAssigne']);
        $this->resetErrorBag();
        $this->updateCounts();
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

                $task = HelpDesks::find($this->selectedTaskId);

                if (!$task) {
                    throw new \Exception('Task not found');
                }

                $employee = auth()->guard('it')->user();

                if ($this->pendingReason) {
                    ActivityLog::create([
                        'action' => "Inprogress Notes",
                        'details' => $this->pendingReason,
                        'performed_by' => $employee->employee_name,
                        'request_type' => 'Catalog Request',
                        'request_id' => $task->request_id,
                    ]);
                }

                if ($task) {
                    if ($task->cat_progress_since === null) {
                        // If it's the first time switching to InProgress, set the current time
                        $task->cat_progress_since = now();
                    }

                    $task->update([
                        'inprogress_notes' => $this->pendingReason,
                        'status_code' => 16,
                    ]);


                    $employee = auth()->guard('it')->user();

                    $activityDetails = '';
                    // Flash a success message based on the selected status
                    $activityDetails = "Work in Progress was Inprogress for Request ID - {$task->request_id}";
                    FlashMessageHelper::flashSuccess("Status has been set to Inprogress");

                    $assigneName = $employee->employee_name;
                    ActivityLog::create([
                        'action' => 'State',
                        'details' => $activityDetails,
                        'performed_by' => $assigneName,
                        'request_type' => 'Catalog Request',
                        'request_id' => $task->request_id,
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


            $task = HelpDesks::find( $this->selectedTaskId );

            $employee = auth()->guard('it')->user();

            if($this->pendingReason){

                ActivityLog::create([
                    'action' => "Pending Notes",
                    'details' => $this->pendingReason,
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Catalog Request',
                    'request_id' => $task->request_id,
                    ]);

            }

            if ($task) {
                $elapsedTime = \Carbon\Carbon::parse($task->cat_progress_since)->diffInMinutes(now());

                // Update the total in-progress time and set status to Pending
                $task->total_cat_progress_time += $elapsedTime;  // Add elapsed time to total

                $task->update([
                    'pending_notes' => $this->pendingReason,
                    'status_code' => 5,
                    'cat_progress_since' => null,
                    ]);

                    $employee = auth()->guard('it')->user();

                    $activityDetails = '';
                    // Flash a success message based on the selected status
                    $activityDetails = "Work in Progress was Pending for Request ID - {$task->request_id}";
                    FlashMessageHelper::flashSuccess("Status has been set to Pending");


                    $assigneName = $employee->employee_name;
                    ActivityLog::create([
                    'action' => 'State',
                    'details' => $activityDetails,
                    'performed_by' => $assigneName,
                    'request_type' => 'Catalog Request',
                    'request_id' => $task->request_id,
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


            $task = HelpDesks::find( $this->selectedTaskId );

            $employee = auth()->guard('it')->user();

            if($this->pendingReason && $this->customerVisibleNotes){

                ActivityLog::create([
                    'action' => "Closed Notes",
                    'details' => "Closed Notes: {$this->pendingReason} ||| Customer Visible Notes: {$this->customerVisibleNotes}",
                    'performed_by' => $employee->employee_name,
                    'request_type' => 'Catalog Request',
                    'request_id' => $task->request_id,
                    ]);

            }

            if ($task) {

                $totalElapsedMinutes = 0;

                if ($task->cat_progress_since) {
                    $totalElapsedMinutes = \Carbon\Carbon::parse($task->cat_progress_since)->diffInMinutes(now());
                }

                // Add the previously tracked progress time if exists
                if (isset($task->total_cat_progress_time)) {
                    $totalElapsedMinutes += $task->total_cat_progress_time;
                }

                $task->update([
                    'status_code' => '11', // Closed
                    'total_cat_progress_time' => $totalElapsedMinutes, // Store the total progress time
                    'cat_progress_since' => null, // Reset the progress start time
                    'req_end_date' => now(),
                    'closed_notes' => $this->pendingReason,
                    'customer_visible_notes' => $this->customerVisibleNotes,
                    ]);

                    $employee = auth()->guard('it')->user();

                    $activityDetails = '';
                    // Flash a success message based on the selected status
                    $activityDetails = "Work in Progress was Closed for Request ID - {$task->request_id}";
                    FlashMessageHelper::flashSuccess("Status has been set to Closed");


                    $assigneName = $employee->employee_name;
                    ActivityLog::create([
                    'action' => 'State',
                    'details' => $activityDetails,
                    'performed_by' => $assigneName,
                    'request_type' => 'Catalog Request',
                    'request_id' => $task->request_id,
                    ]);

                $this->closeClosedModal();
                $this->updateCounts();
            }


        }


            public $showModal = false;

            public function viewRecord($id)
            {

                $requestCategories = Request::select('Request', 'category')
                ->where('Request', 'IT') // Adjust this to match the condition for IT requests
                ->pluck('category');
                // Fetch the record based on the ID
                $this->selectedRecord = HelpDesks::with('emp')
                ->whereIn('category',  $requestCategories)
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->orderBy('created_at', 'desc')
                ->find($id);

                $this->showModal = true;


            }

            public function closeModal()
                {
                    $this->showModal = false; // Hide the modal
                    $this->selectedRecord = null; // Reset the selected record
                }





    public function postComment($taskId)
{
    $this->validate([
        'comments' => 'required|string|max:500',
    ]);

    try {
        // Find the task by taskId
        $task = HelpDesks::find($taskId);

        // Check if task exists and a comment is provided
        if ($task && $this->comments) {
            // Update the task with the comment
            $task->update(['active_comment' => $this->comments]);

            $employee = auth()->guard('it')->user(); // Get the logged-in user

            ActivityLog::create([
                'action' => 'Active Comment',
                'details' => "$this->comments",
                'performed_by' => $employee->employee_name,
                'request_type' => 'Catalog Request',
                'request_id' => $task->request_id,
            ]);

            // Flash a success message
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

public function postPendingRemarks($taskId)
{
    try {
        // Retrieve remarks for the specific task
        $remarks = $this->remarks[$taskId] ?? '';
        // Find the task by taskId
        $task = HelpDesks::find($taskId);

        // Check if the task exists
        if ($task) {

            if (empty($this->remarks)) {
                FlashMessageHelper::flashError("Remarks cannot be empty.");
                return;
            }
            // Update the task with the remarks
            $task->update(['pending_remarks' => $remarks]);


            $employee = auth()->guard('it')->user(); // Get the logged-in user
            ActivityLog::create([
                'action' => 'Pending Comment',
                'details' =>  $remarks,
                'performed_by' => $employee->employee_name,
                'request_type' => 'Catalog Request',
                'request_id' => $task->request_id,
            ]);

            // Flash a success message
            FlashMessageHelper::flashSuccess("Remarks posted successfully!");
        } else {
            // Handle case where task not found
            FlashMessageHelper::flashError("Task not found.");
        }
    } catch (\Exception $e) {
        // Log the exception for debugging
        Log::error("Error occurred while posting remarks", [
            'exception' => $e,
            'taskId' => $taskId,
            'remarks' => $remarks,
        ]);

        // Flash an error message
        FlashMessageHelper::flashError("An error occurred while posting the remarks.");
    }
}


public function postInprogressRemarks($taskId)
{
    try {
        // Retrieve remarks for the specific task
        $remarks = $this->remarks[$taskId] ?? '';
        // Find the task by taskId
        $task = HelpDesks::find($taskId);

        // Check if the task exists
        if ($task) {

            if (empty($this->remarks)) {
                FlashMessageHelper::flashError("Remarks cannot be empty.");
                return;
            }

            // Update the task with the remarks
            $task->update(['inprogress_notes' => $remarks]);

            $employee = auth()->guard('it')->user(); // Get the logged-in user
            ActivityLog::create([
                'action' => 'Inprogress Comment',
                'details' => $remarks,
                'performed_by' => $employee->employee_name,
                'request_type' => 'Catalog Request',
                'request_id' => $task->request_id,
            ]);


            // Flash a success message
            FlashMessageHelper::flashSuccess("Remarks posted successfully!");
        } else {
            // Handle case where task not found
            FlashMessageHelper::flashError("Task not found.");
        }
    } catch (\Exception $e) {
        // Log the exception for debugging
        Log::error("Error occurred while posting remarks", [
            'exception' => $e,
            'taskId' => $taskId,
            'remarks' => $remarks,
        ]);

        // Flash an error message
        FlashMessageHelper::flashError("An error occurred while posting the remarks.");
    }
}


public $filterType;
public function filterLogs($type)
{
    $this->filterType = $type;
    $this->loadLogs($this->requestID); // Re-load logs with the new filter
}


public $requestID;
public $catologueIDHeader = '';
public $activityLogs;
public $employeeInitials;

public function loadLogs($requestID = null)
{

    if ($requestID) {

        $this->requestID = $requestID;
        $this->catologueIDHeader =   $this->requestID;
        $query = ActivityLog::where('request_id', $this->requestID)
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


public function updateCounts()
{
    try {
        // Fetch categories for IT requests
        $requestCategories = Request::select('Request', 'category')
            ->where('Request', 'IT') // Adjust this to match the condition for IT requests
            ->pluck('category');

        // Count new requests (Recent)
        $this->newRequestCount = HelpDesks::where('status_code', '8')
            ->whereIn('category', $requestCategories)->count();

        // Count rejected requests (Reject)
        $this->newRejectionCount = HelpDesks::where('status_code', '3')
            ->whereIn('category', $requestCategories)->count();

        // Count active requests (Open)
        $this->activeCount = HelpDesks::where('status_code', '10')
            ->whereIn('category', $requestCategories)->count();

        // Count pending requests (Pending)
        $this->pendingCount = HelpDesks::where('status_code', '5')
            ->whereIn('category', $requestCategories)->count();

            $this->inprogressCount = HelpDesks::where('status_code', '16')
            ->whereIn('category', $requestCategories)->count();

        // Count closed requests (Completed)
        $this->closedCount = HelpDesks::whereIn('status_code', ['11', '15'])
        ->whereIn('category', $requestCategories)->count();

    } catch (\Exception $e) {
        // Log the exception for debugging purposes
        Log::error("Error occurred while updating counts", [
            'exception' => $e,
        ]);

        // Optionally, set all counts to zero or handle the error gracefully
        $this->newRequestCount = 0;
        $this->newRejectionCount = 0;
        $this->activeCount = 0;
        $this->pendingCount = 0;
        $this->closedCount = 0;

        // Flash an error message to inform the user
        FlashMessageHelper::flashError("An error occurred while updating the request counts.");
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




    public $previews=[];
    public $all_files = [];
    public $cat_file_paths = [];
    public $showSuccessMsg=false;

    public function updatedCatFilePaths($value, $recordId)
{

    // Assuming cat_file_paths is an array of files for a specific request ID
    foreach ($this->cat_file_paths[$recordId] as $file) {
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



        $this->cat_file_paths = $this->all_files;

        $attachments = HelpDesks::find($selectedRecordId);

        // Initialize fileDataArray
        $fileDataArray = [];


        if ($this->cat_file_paths) {
            foreach ($this->cat_file_paths as $file) {
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
                'cat_file_paths.*' => [
                    'nullable',
                    'file',
                    'mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif',
                    'max:512000', // Maximum size in KB (500 MB for general validation)
                ],
            ]);
        }

        if ($this->cat_file_paths) {
            // Validate files
            $this->validate([
                'cat_file_paths.*' => 'nullable|file|mimes:xls,csv,xlsx,pdf,jpeg,png,jpg,gif|max:40960',
            ]);


            // Process each file
            foreach ($this->cat_file_paths as $file) {
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
                    'existing_cat_file_paths' => $attachments->cat_file_paths, // Log existing file paths
                    'new_cat_file_paths' => $fileDataArray, // Log the new file data array
                ]);

                // Perform the update
                // dd(json_encode($fileDataArray));

                $existingFiles = json_decode($attachments->cat_file_paths, true) ?? [];
                $allFiles = array_merge($existingFiles, $fileDataArray);

                $attachments->cat_file_paths=json_encode($allFiles);
                $attachments->save();


                $employee = auth()->guard('it')->user();
                $assigneName = $employee->employee_name;
                ActivityLog::create([
                    'request_id' => $attachments->request_id, // Assuming this is the request ID
                    'description' => "Uploaded file: {$file->getClientOriginalName()}",
                    'performed_by' => $assigneName,
                    'attachments' => json_encode($fileDataArray)
                ]);

                // Log successful update
                Log::info('Attachments updated successfully', [
                    'attachment_id' => $attachments->id ?? null,
                    'updated_cat_file_paths' => json_encode($fileDataArray),
                ]);

                $this->previews=[];
                $this->all_files = [];
            } catch (\Exception $e) {
                // Log any errors
                Log::error('Error updating attachments', [
                    'error_message' => $e->getMessage(),
                    'attachment_id' => $attachments->id ?? null,
                    'new_cat_file_paths' => $fileDataArray,
                ]);

                // Optionally, rethrow the exception if needed
                throw $e;
            }
        }



        // Optional: return a success message or redirect
        FlashMessageHelper::flashSuccess("Files uploaded successfully!");
        $this->showFilePreviewModal = false;

    }




public $showViewImageDialog = false;
public $currentImageRequesId;
public $showViewFileDialog = false;
public function closeViewFile()
{
    $this->showViewFileDialog = false;
}
public function showViewImage($imgRequestId)
{
    $this->currentImageRequesId = $imgRequestId;
    $this->showViewImageDialog = true;
}


public function showViewFile($imgRequestId)
{
    $this->currentImageRequesId = $imgRequestId;
    $this->showViewFileDialog = true;
}


public function closeViewImage()
{
    $this->showViewImageDialog = false;
}


public $showViewEmpImageDialog = false;
public $showViewEmpFileDialog = false;



public function showViewEmpImage($imgRequestId)
{
    $this->currentImageRequesId = $imgRequestId;
    $this->showViewEmpImageDialog = true;
}



public function showViewEmpFile($imgRequestId)
{
    $this->currentImageRequesId = $imgRequestId;
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



public function downloadITImages($imgRequestId)
{
    try {
        $catalog = collect($this->allRequestDetails)->firstWhere('id', $imgRequestId);

        if (!$catalog) {
            // catalog not found
            return response()->json(['message' => 'catalog not found'], 404);
        }

        $fileDataArray = is_string($catalog->cat_file_paths)
            ? json_decode($catalog->cat_file_paths, true)
            : $catalog->cat_file_paths;

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



public function downloadImages($imgRequestId)
{
    try {
        $imgRequest = collect($this->allRequestDetails)->firstWhere('id', $imgRequestId);

        if (!$imgRequest) {
            // imgRequest not found
            return response()->json(['message' => 'imgRequest not found'], 404);
        }

        $fileDataArray = is_string($imgRequest->file_paths)
            ? json_decode($imgRequest->file_paths, true)
            : $imgRequest->file_paths;

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






    public $allRequestDetails;
    public $selectedRecord = null;
    public $forIT;
    public $recentDetails;
    public $rejectDetails;
    public $requestData;
    public $itData;
    public $requestCategories='';
  public function render()
{
    try {

        $this->loadPendingRecordsByAssigne();
        $this->loadInprogessRecordsByAssigne();
        $this->loadClosedRecordsByAssigne();
        $this->itAssigneMemebers = EmployeeDetails::where('sub_dept_id', '9915')
        ->where('dept_id', '8803')
        ->where('status', 1)
        ->orderBy('first_name', 'asc')
        ->get();
        // Fetch IT request categories
        $requestCategories = Request::select('Request', 'category')
            ->where('Request', 'IT') // Adjust this to match the condition for IT requests
            ->pluck('category');

        // Fetch IT data (empIt related data)
        $this->itData = IT::with('empIt')->get();

        $companyId = auth()->guard('it')->user()->company_id;

        // Fetch HelpDesk records based on the category and companyId
        $this->forIT = HelpDesks::with('emp')
            ->whereHas('emp', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->orderBy('created_at', 'desc')
            ->whereIn('category',  $requestCategories)
            ->get();

        // Fetch recent, rejected, and active details based on status

        $this->allRequestDetails = HelpDesks::with('emp')
        ->orderBy('created_at', 'desc')
        ->orderBy($this->sortColumn, $this->sortDirection)
        ->whereIn('category',  $requestCategories)
        ->get();

        $this->recentDetails = HelpDesks::with('emp')
            ->where('status_code', '8')
            ->orderBy('created_at', 'desc')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->whereIn('category',  $requestCategories)
            ->get();


        $this->rejectDetails = HelpDesks::with('emp')
            ->where('status_code', '3')
            ->orderBy('created_at', 'desc')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->whereIn('category',  $requestCategories)
            ->get();

        // Dynamic query for the active tab filter
        if ($this->activeTab == 'active') {
            $this->forIT = HelpDesks::with('emp')
                ->where('status_code', '10')
                ->orderBy('created_at', 'desc')
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->whereIn('category',  $requestCategories)
                ->get();
        }
        //  elseif ($this->activeTab == 'pending') {
        //     $this->forIT = HelpDesks::with('emp')
        //         ->where('status_code', '5')
        //         ->whereIn('category', $requestCategories)
        //         ->orderBy($this->sortColumn, $this->sortDirection)
        //         ->orderBy('created_at', 'desc')
        //         ->get();
        // }
        // elseif ($this->activeTab == 'inprogress') {
        //     $this->forIT = HelpDesks::with('emp')
        //         ->where('status_code', '16')
        //         ->whereIn('category', $requestCategories)
        //         ->orderBy($this->sortColumn, $this->sortDirection)
        //         ->orderBy('created_at', 'desc')
        //         ->get();
        // }
        // elseif ($this->activeTab == 'closed') {
        //     $this->forIT = HelpDesks::with('emp')
        //     ->whereIn('status_code', ['11', '15'])
        //         ->whereIn('category',  $requestCategories)
        //         ->orderBy($this->sortColumn, $this->sortDirection)
        //         ->orderBy('created_at', 'desc')
        //         ->get();
        // }

        // Handling IT requests after 7 days to update status
        if (auth()->guard('it')->check()) {
            $companyId = auth()->guard('it')->user()->company_id;
            $thresholdDate = Carbon::now()->subDays(7);

            // Get holidays within the last 7 days
            $holidays = HolidayCalendar::whereBetween('date', [$thresholdDate->startOfDay(), Carbon::now()->endOfDay()])
                ->pluck('date')
                ->map(function($date) {
                    return Carbon::parse($date)->format('Y-m-d'); // Normalize date format
                })
                ->toArray();

            // Count the number of non-holiday days in the last 7 days
            $nonHolidayDays = 0;
            $currentDate = Carbon::now()->startOfDay();

            while ($currentDate->greaterThanOrEqualTo($thresholdDate->startOfDay())) {
                $formattedDate = $currentDate->format('Y-m-d');

                // Check if it's a weekend or a holiday
                if (!in_array($formattedDate, $holidays) && !in_array($currentDate->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                    $nonHolidayDays++;
                }

                $currentDate->subDay(); // Move to the previous day
            }

            // Update the status of older IT requests
            $recentRequest = HelpDesks::where('status_code', '8')
            ->where('created_at', '<=', $thresholdDate)
            ->first();

            if ($recentRequest) {

                // Update the status of older IT requests
                $recentRequest->update(['status_code' => '3']);
                $employee = auth()->guard('it')->user();
                // Prepare the email details
                $employeeEmail = $recentRequest->mail;
                $employeeName = $recentRequest->emp->first_name . ' ' . $recentRequest->emp->last_name;
                $requestId = $recentRequest->request_id;
                $shortDescription = $recentRequest->description;
                $RejetedEmployeeName = "Auto Rejected";
                $rejectionReason =  "This request is auto rejected ,sorry for inconvinience";
                // Send rejection email
                Mail::to($employeeEmail)->send(new RejectRequestMail(
                    $employeeName,
                    $rejectionReason,
                    $requestId,
                    $shortDescription,
                    $RejetedEmployeeName,

                ));
                FlashMessageHelper::flashSuccess("Email sent successfully!");

            } else {
                // Handle case where no matching request was found
                Log::info('No IT request found for rejection after 7 days.');
            }



        }

        // Handle category grouping
        if ($requestCategories->isNotEmpty()) {
            $this->requestCategories = $requestCategories->groupBy('Request')->map(function ($group) {
                return $group->unique('category'); // Ensure categories are unique
            });
        } else {
            $this->requestCategories = collect(); // Initialize as an empty collection
        }

        return view('livewire.request-process', [
            'newRequestCount' => $this->newRequestCount,
            'newRejectionCount' => $this->newRejectionCount,
            'activeCount' => $this->activeCount,
            'pendingCount' => $this->pendingCount,
            'closedCount' => $this->closedCount,
            'ClosedRequests' => $this->ClosedRequests,
            'inProgressRequests' => $this->inProgressRequests,
            'viewingDetails' => $this->viewingDetails,
            'recentrequestDetails' => $this->recentrequestDetails,
            'rejectedrequestDetails' => $this->rejectedrequestDetails,
            'requests' => $this->requests,
            'activeTab' => $this->activeTab,
        ]);
    } catch (\Exception $e) {
        // Log the exception for debugging
        Log::error("Error occurred in rendering requests: ", ['exception' => $e]);

        // Optionally, set default values or handle failure
        FlashMessageHelper::flashError("An error occurred while loading the request data.");

        // Return an empty view or partial data if needed
        return view('livewire.request-process', [
            'newRequestCount' => 0,
            'newRejectionCount' => 0,
            'activeCount' => 0,
            'pendingCount' => 0,
            'closedCount' => 0,
            'requests' => collect(),
            'activeTab' => $this->activeTab,
        ]);
    }
}

}
