<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\HelpDesks;
use App\Models\HolidayCalendar;
use App\Models\Request;
use App\Models\IT;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Client\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use function Termwind\render;

class RequestProcess extends Component
{
    public $activeTab = 'active';
    public $requests = [];
    public $viewingDetails = false;
    public $recentrequestDetails = false;
    public $rejectedrequestDetails = false;
    public $viewRecentRequests = true;
    public $viewRejectedRequests = false;
    public $viewEmpRequest = false;

    public $assignTo;
    public $comments;
    public $remarks =[];
    public $request;
    public $selectedRequest;
    public $recentRequest;
    public $rejectedRequest;
    public $showOverview = false;
    public $showRejectionModal = false;
    public $attachments;
    public $currentRequestId;
    public $newRequestCount;
    public $newRejectionCount;
    public $activeCount;
    public $pendingCount;
    public $closedCount;
    public $file_path;




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

    public function mount()
    {
        try {
            // Get the authenticated user
            $employee = auth()->user();

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

            return $request['status'] == 'inProgress';
        });
    }
    public function getClosedRequestsProperty()
    {
        return array_filter($this->requests, function ($request) {
            return $request['status'] == 'Completed';
        });
    }

    public function viewRejectDetails($index)
    {
        try {
            $this->comments = '';
            $this->rejectedRequest = $this->rejectDetails->where('status', 'Reject')->values()->get($index);

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

    public function viewApproveDetails($index)
    {
        try {
            $this->comments = '';
            $this->recentRequest = $this->recentDetails->where('status', 'Recent')->values()->get($index);

            // Check if the selected request exists
            if (!$this->recentRequest) {
                abort(404, 'Request not found');
            }

            $this->recentrequestDetails = true;
            $this->currentRequestId = $this->recentRequest->id;

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred in viewApproveDetails method", [
                'exception' => $e,
                'index' => $index,
            ]);

            // Flash an error message for the user
            FlashMessageHelper::flashError("An error occurred while viewing the approved request.");

            // Optionally, reset properties in case of error
            $this->recentrequestDetails = false;
            $this->currentRequestId = null;
        }
    }

    public function viewDetails($index)
    {
        try {
            $this->comments = '';
            $this->selectedRequest = $this->forIT->where('status', 'Open')->values()->get($index);
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

    public function redirectBasedOnStatus()
    {

        $this->validate([
            'selectedStatus' => 'required',

            'selectedAssigne' => 'required',
        ], [
            'selectedStatus.required' => 'Status is required.',
            'selectedAssigne.required' => 'Assign to is required.',
        ]);


        if ($this->selectedStatus === 'Pending') {

            $this->setActiveTab('pending');

        } elseif ($this->selectedStatus === 'Completed') {

            $this->setActiveTab('closed');

        }
        $this->reset(['selectedStatus', 'selectedAssigne']);
        $this->resetErrorBag();
        $this->updateCounts();
    }




    public function pendingForDesks($taskId)
    {
        $task = HelpDesks::find($taskId);

        if ($task) {
            $task->update(['status' => 'Pending']);
            FlashMessageHelper::flashSuccess("Status saved successfully!");

        }
        $this->updateCounts();
    }


    public function openForDesks($taskId)
    {
        $task = HelpDesks::find($taskId);

        if ($task) {
            $task->update(['status' => 'Completed']);
            FlashMessageHelper::flashSuccess("Status Closed successfully!");

        }
        $this->updateCounts();
    }
    public $error = '';
    public $loading = false;
    public function closeForDesks($taskId)
    {
        $this->loading = true;
        sleep(3);
        $task = HelpDesks::find($taskId);
        try {
        if ($task) {
            $task->update(['status' => 'Open']);
            FlashMessageHelper::flashSuccess("Status Reopened successfully!");

        }
    }catch (\Exception $e) {
        // Handle exception
        $this->error = "An unexpected error occurred. Please try again.";
    } finally {
        $this->loading = false;

    }
        $this->updateCounts();
    }



    public function approveStatus($taskId)
    {
        $task = HelpDesks::find($taskId);

        if ($task) {
            // Set the status to "Open" when approving
            $task->update(['status' => 'Open']);

            FlashMessageHelper::flashSuccess("Request has been approved!");
            $this->updateCounts();
        }
    }

    public $recordId;
    public $reason = [];

    public function rejectionModal($taskId)
    {
        $this->recordId = $taskId;
        $this->showRejectionModal =true;

    }


    public function rejectStatus()
    {
        try {
            $recentRequest = HelpDesks::where('id', $this->recordId)->first();

            if ($recentRequest) {
                // Set the status to "Reject" when rejecting the request
                $recentRequest->update(['status' => 'Reject']);

                FlashMessageHelper::flashSuccess("Request has been rejected!");
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

    }

    public $selectedStatus;

    public function updateStatus($taskId)
    {
        try {
            $this->validateOnly('selectedStatus');
            $this->resetErrorBag('selectedStatus');

            // Find the task by ID
            $task = HelpDesks::find($taskId);

            // Check if the task exists and a valid status is selected
            if ($task && $this->selectedStatus) {
                // Update the task status
                $task->update(['status' => $this->selectedStatus]);

                // Flash a success message based on the selected status
                if ($this->selectedStatus === 'Pending') {
                    FlashMessageHelper::flashSuccess("Status has been set to Pending!");
                } elseif ($this->selectedStatus === 'Completed') {
                    FlashMessageHelper::flashSuccess("Status has been set to Completed!");
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






    public $selectedAssigne;


    public function updateAssigne($taskId)
    {
        try {
            // Validate the selected assignee
            $this->validateOnly('selectedAssigne');
            $this->resetErrorBag('selectedAssigne');

            // Find the task by ID
            $task = HelpDesks::find($taskId);

            // Check if the task exists and a valid assignee is selected
            if ($task && $this->selectedAssigne) {
                // Update the task with the selected assignee
                $task->update(['assign_to' => $this->selectedAssigne]);

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




    public function postComment($taskId)
{
    try {
        // Find the task by taskId
        $task = HelpDesks::find($taskId);

        // Check if task exists and a comment is provided
        if ($task && $this->comments) {
            // Update the task with the comment
            $task->update(['active_comment' => $this->comments]);

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
        // Retrieve remarks for the specific task
        $remarks = $this->remarks[$taskId] ?? '';
        // Find the task by taskId
        $task = HelpDesks::find($taskId);

        // Check if the task exists
        if ($task) {
            // Update the task with the remarks
            $task->update(['inprogress_remarks' => $remarks]);

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


public function updateCounts()
{
    try {
        // Fetch categories for IT requests
        $requestCategories = Request::select('Request', 'category')
            ->where('Request', 'IT') // Adjust this to match the condition for IT requests
            ->pluck('category');

        // Count new requests (Recent)
        $this->newRequestCount = HelpDesks::where('status', 'Recent')
            ->whereIn('category', $requestCategories)->count();

        // Count rejected requests (Reject)
        $this->newRejectionCount = HelpDesks::where('status', 'Reject')
            ->whereIn('category', $requestCategories)->count();

        // Count active requests (Open)
        $this->activeCount = HelpDesks::where('status', 'Open')
            ->whereIn('category', $requestCategories)->count();

        // Count pending requests (Pending)
        $this->pendingCount = HelpDesks::where('status', 'Pending')
            ->whereIn('category', $requestCategories)->count();

        // Count closed requests (Completed)
        $this->closedCount = HelpDesks::where('status', 'Completed')
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



    public $forIT;
    public $recentDetails;
    public $rejectDetails;
    public $requestData;
    public $itData;
    public $requestCategories='';
  public function render()
{
    try {
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
        $this->recentDetails = HelpDesks::with('emp')
            ->where('status', 'Recent')
            ->orderBy('created_at', 'desc')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->whereIn('category',  $requestCategories)
            ->get();

        $this->rejectDetails = HelpDesks::with('emp')
            ->where('status', 'Reject')
            ->orderBy('created_at', 'desc')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->whereIn('category',  $requestCategories)
            ->get();

        // Dynamic query for the active tab filter
        if ($this->activeTab == 'active') {
            $this->forIT = HelpDesks::with('emp')
                ->where('status', 'Open')
                ->orderBy('created_at', 'desc')
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->whereIn('category',  $requestCategories)
                ->get();
        } elseif ($this->activeTab == 'pending') {
            $this->forIT = HelpDesks::with('emp')
                ->where('status', 'Pending')
                ->whereIn('category', $requestCategories)
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($this->activeTab == 'closed') {
            $this->forIT = HelpDesks::with('emp')
                ->where('status', 'Completed')
                ->whereIn('category',  $requestCategories)
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->orderBy('created_at', 'desc')
                ->get();
        }

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
            HelpDesks::where('status', 'Recent')
                ->where('created_at', '<=', $thresholdDate)
                ->update(['status' => 'Open']);
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
