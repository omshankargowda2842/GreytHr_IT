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
        $employee = auth()->user();
        // Set flags based on user role
        if (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))) {

            $this->viewRecentRequests = true; // User can view recent requests
            $this->viewRejectedRequests = false; // User can view recent requests

        } else {

            $this->viewRecentRequests = false; // User cannot view recent requests
            $this->viewRejectedRequests = false; // User cannot view recent requests
            $this->recentrequestDetails = false; // No request details available
            $this->rejectedrequestDetails = false; // No request details available
            $this->viewEmpRequest =true;
         }

        $this->selectedStatus = '';
        $this->selectedAssigne = '';
        $this->updateCounts();
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
        $this->comments = '';
        $this->rejectedRequest = $this->rejectDetails->where('status', 'Reject')->values()->get($index);

        // Check if the selected request exists
        if (!$this->rejectedRequest) {
            abort(404, 'Request not found');
        }

        $this->rejectedrequestDetails = true;
        $this->currentRequestId = $this->rejectedRequest->id;
    }

    public function viewApproveDetails($index)
    {
        $this->comments = '';
        $this->recentRequest = $this->recentDetails->where('status', 'Recent')->values()->get($index);


        // Check if the selected request exists
        if (!$this->recentRequest) {
            abort(404, 'Request not found');
        }

        $this->recentrequestDetails = true;
        $this->currentRequestId = $this->recentRequest->id;
    }


    public function viewDetails($index)
    {
        $this->comments='';
        $this->selectedRequest = $this->forIT->where('status', 'Open')->values()->get($index);
        $this->viewingDetails = true;
            if (!$this->selectedRequest) {
            abort(404, 'Request not found');
        }
        $this->currentRequestId = $this->selectedRequest->id;
        $file_path = $this->selectedRequest->file_path;

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


        $recentRequest = HelpDesks::where('id', $this->recordId)->first();

    if ($recentRequest) {
            // Set the status to "Reject" when approving
            $recentRequest->update(['status' => 'Reject']);

            FlashMessageHelper::flashSuccess("Request has been rejected!");
            $this->updateCounts();

            $this->showRejectionModal = false;
            // Reset the recordId and reason after processing
            $this->recordId = null;
            $this->reason = '';
        }
    }


    public function Cancel()
    {

        $this->showRejectionModal = false;

    }

    public $selectedStatus;

    public function updateStatus($taskId)
    {
        $this->validateOnly('selectedStatus');
        $this->resetErrorBag('selectedStatus');
        $task = HelpDesks::find($taskId);

        if ($task && $this->selectedStatus) {
            $task->update(['status' => $this->selectedStatus]);
            if ($this->selectedStatus === 'Pending') {
                FlashMessageHelper::flashSuccess("Status has been set to Pending!");
            } elseif ($this->selectedStatus === 'Completed') {
                FlashMessageHelper::flashSuccess("Status has been set to Completed!");

            } else {
                FlashMessageHelper::flashSuccess("Status Updated successfully!");
            }
        }
    }





    public $selectedAssigne;


    public function updateAssigne($taskId)
    {
        $this->validateOnly('selectedAssigne');
        $this->resetErrorBag('selectedAssigne');

        $task = HelpDesks::find($taskId);

        if ($task  && $this->selectedAssigne) {
            $task->update(['assign_to' => $this->selectedAssigne]);
            // session()->flash('message', 'Status Reopened successfully');
        }
    }




    public function postComment($taskId)
    {
        $task = HelpDesks::find($taskId);

        if ($task && $this->comments) {

            $task->update(['active_comment' => $this->comments]);
            FlashMessageHelper::flashSuccess("Comment posted successfully!");
        }
    }


    public function postRemarks($taskId)
    {
        $remarks = $this->remarks[$taskId] ?? '';
        $task = HelpDesks::find($taskId);

        if ($task) {

        $task->update(['inprogress_remarks' => $remarks]);
            // $task->update(['inprogress_remarks' => $this->remarks]);
            FlashMessageHelper::flashSuccess("Comment posted successfully!");

        }
    }


    public function updateCounts()
    {
        $requestCategories = Request::select('Request', 'category')
        ->where('Request', 'IT') // Adjust this to match the condition for IT requests
        ->pluck('category');

        $this->newRequestCount = HelpDesks::where('status', 'Recent')
        ->whereIn('category',  $requestCategories)->count();

        $this->newRejectionCount = HelpDesks::where('status', 'Reject')
        ->whereIn('category',  $requestCategories)->count();


        $this->activeCount = HelpDesks::where('status', 'Open')
        ->whereIn('category',  $requestCategories)->count();

        $this->pendingCount = HelpDesks::where('status', 'Pending')
         ->whereIn('category',  $requestCategories)->count();

        $this->closedCount = HelpDesks::where('status', 'Completed')
        ->whereIn('category',  $requestCategories)->count();

    }

     public $sortColumn = 'emp_id'; // default sorting column
    public $sortDirection = 'asc'; // default sorting direction

    public function toggleSortOrder($column)
    {
        if ($this->sortColumn == $column) {
            // If the column is the same, toggle the sort direction
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {

            // If a different column is clicked, set it as the new sort column and default to ascending order
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
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

        $requestCategories = Request::select('Request', 'category')
        ->where('Request', 'IT') // Adjust this to match the condition for IT requests
        ->pluck('category');


        $this->itData = IT::with('empIt')->get();

        $companyId = auth()->guard('it')->user()->company_id;
        $this->forIT = HelpDesks::with('emp')
            ->whereHas('emp', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->orderBy('created_at', 'desc')
            ->whereIn('category',  $requestCategories)
            ->get();


            $this->recentDetails=  $this->forIT = HelpDesks::with('emp')
            ->where('status', 'Recent')
            ->orderBy('created_at', 'desc')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->whereIn('category',  $requestCategories)
            ->get();

            $this->rejectDetails=  $this->forIT = HelpDesks::with('emp')
            ->where('status', 'Reject')
            ->orderBy('created_at', 'desc')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->whereIn('category',  $requestCategories)
            ->get();

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
            ->orderBy($this->sortColumn, $this->sortDirection) // sortColumn and sortDirection applied first
            ->orderBy('created_at', 'desc') // add additional ordering here if needed
            ->get();



        } elseif ($this->activeTab == 'closed') {
            $this->forIT = HelpDesks::with('emp')
                ->where('status', 'Completed')
                ->whereIn('category',  $requestCategories)
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->orderBy('created_at', 'desc')
                ->get();
        }


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


            HelpDesks::where('status', 'Recent')
                ->where('created_at', '<=', $thresholdDate)

                ->update(['status' => 'Open']);


        }

        if ($requestCategories->isNotEmpty()) {
            // Group categories by their request
            $this->requestCategories = $requestCategories->groupBy('Request')->map(function ($group) {
                return $group->unique('category'); // Ensure categories are unique
            });
        } else {
            // Handle the case where there are no requests
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

    }
}
