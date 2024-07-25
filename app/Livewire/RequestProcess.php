<?php

namespace App\Livewire;

use Illuminate\Http\Client\Request;
use Livewire\Component;

class RequestProcess extends Component
{
    public $activeTab = 'active';
    public $requests = [];
    public $viewingDetails = false;
    public $assignTo;
    public $comments;
    public $request;
    public $selectedRequest;
    public $showOverview = false;
    public $attachments;
    public $currentRequestId;


    protected $rules = [
        'request.assignTo' => 'required',
        'comments' => 'required',
        'request.status' => 'required',
    ];

    public function setActiveTab($tab)
    {

        $this->activeTab = $tab;
    }

    public function mount()
    {
        $this->requests = [
            [
                'id' => 1,
                'requested_by' => 'Kiran',
                'category' => 'IT Support',
                'subject' => 'Computer not working',
                'description' => 'The computer in the main office is not turning on.',
                'distributor' => 'ABC Tech',
                'mobile' => '123-456-7890',
                'mailbox' => 'kiran@example.com',
                'attach_files' => [
                    'https://www.sawanonlinebookstore.com/zubyheet/2021/12/BAbur.jpg',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQsIz4qZKTOplGKCIt860B8HP3mTBMZGACNFg&s',
                    'https://static.gettyimages.com/display-sets/creative-landing/images/GettyImages-1907862843.jpg'
                    // Add more image URLs as needed
                ],
                'cc_to' => 'manager@example.com',
                'priority' => 'High',
                'select_equipment' => 'Desktop Computer',
                'status' => 'Completed'
            ],

            [
                'id' => 2,
                'requested_by' => 'Emily',
                'category' => 'Admin Services',
                'subject' => 'Office Supplies Order',
                'description' => 'Requesting a replenishment of office supplies for the marketing department.',
                'distributor' => 'Office Supplies Plus',
                'mobile' => '987-654-3210',
                'mailbox' => 'emily@example.com',
                'attach_files' => [
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnhoHDIbSi0WJkzGYr6wemnCS2OzSRkhokmA&s',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQqGK3diR3Zi-mnOXEaj-3ewmFyRYVxGzVzZw&s',
                    'https://buffer.com/library/content/images/size/w1200/2023/10/free-images.jpg',
                    // Add more document URLs as needed
                ],
                'cc_to' => 'supervisor@example.com',
                'priority' => 'Medium',
                'select_equipment' => 'Office Supplies',
                'status' => 'Completed'
            ],

            [
                'id' => 3,
                'requested_by' => 'Kiran Kumar',
                'category' => 'IT Support',
                'subject' => 'Computer not working',
                'description' => 'The computer in the main office is not turning on.',
                'distributor' => 'ABC Tech',
                'mobile' => '123-456-7890',
                'mailbox' => 'kiran@example.com',
                'attach_files' => [
                    'https://static.vecteezy.com/system/resources/thumbnails/034/038/529/small_2x/sustainable-development-and-green-business-based-on-renewable-energy-concepts-ai-generated-photo.jpg',
                    'https://tinypng.com/images/social/website.jpg',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrpZEneLh1WL_0kpeQEvbvHipkPx22W2hKMg&s',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrpZEneLh1WL_0kpeQEvbvHipkPx22W2hKMg&s'

                ],
                'cc_to' => 'manager@example.com',
                'priority' => 'High',
                'select_equipment' => 'Desktop Computer',
                'status' => 'inProgress'
            ],

            [
                'id' => 4,
                'requested_by' => 'Bhargav Kumar',
                'category' => 'Admin Team',
                'subject' => 'Computer working',
                'description' => 'The computer in the main office is not turning on.',
                'distributor' => 'ABC Tech',
                'mobile' => '123-456-7890',
                'mailbox' => 'kiran@example.com',
                'attach_files' => [
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCcZgzHS5HS02nxVXYM-ZV7LxuHqbUNdCj8A&s',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTufTC6mDEMp7zh4lgLIOFGTQnp4qjswpH6w&s',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrpZEneLh1WL_0kpeQEvbvHipkPx22W2hKMg&s'
                    // Add more image URLs as needed
                ],
                'cc_to' => 'manager@example.com',
                'priority' => 'High',
                'select_equipment' => 'Desktop Computer',
                'status' => 'inProgress'
            ],

            [
                'id' => 5,
                'requested_by' => 'Bhargav Kumar',
                'category' => 'Admin Team',
                'subject' => 'Computer working',
                'description' => 'The computer in the main office is not turning on.',
                'distributor' => 'ABC Tech',
                'mobile' => '123-456-7890',
                'mailbox' => 'kiran@example.com',
                'attach_files' => [
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCcZgzHS5HS02nxVXYM-ZV7LxuHqbUNdCj8A&s',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTufTC6mDEMp7zh4lgLIOFGTQnp4qjswpH6w&s',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrpZEneLh1WL_0kpeQEvbvHipkPx22W2hKMg&s'
                    // Add more image URLs as needed
                ],
                'cc_to' => 'manager@example.com',
                'priority' => 'High',
                'select_equipment' => 'Desktop Computer',
                'status' => 'inProgress'
            ],
            [
                'id' => 6,
                'requested_by' => 'Bhargav Kumar',
                'category' => 'Admin Team',
                'subject' => 'Computer working',
                'description' => 'The computer in the main office is not turning on.',
                'distributor' => 'ABC Tech',
                'mobile' => '123-456-7890',
                'mailbox' => 'kiran@example.com',
                'attach_files' => [
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCcZgzHS5HS02nxVXYM-ZV7LxuHqbUNdCj8A&s',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTufTC6mDEMp7zh4lgLIOFGTQnp4qjswpH6w&s',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrpZEneLh1WL_0kpeQEvbvHipkPx22W2hKMg&s'
                    // Add more image URLs as needed
                ],
                'cc_to' => 'manager@example.com',
                'priority' => 'High',
                'select_equipment' => 'Desktop Computer',
                'status' => 'Completed'
            ],
           
        ];
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

    public function viewDetails($index)
    {
        $this->request = $this->requests[$index] ?? null;
        $this->viewingDetails = true;
    }


    public function toggleOverview()
    {
        $this->showOverview = !$this->showOverview;
    }


    public function closeDetails()
    {
        $this->viewingDetails = false;
        // $this->selectedRequest = true;
    }

    public function updated($field)
    {
        $this->validateOnly($field);
        // $this->debounce('save', function () {
        //     $this->save();
        // });
        $this->save();
    }

    public function save()
    {
        $this->validate();
        $request = Request::find($this->selectedRequest['id']);
        $request->assign_to = $this->assignTo;
        $request->comments = $this->comments;
        $request->status = $this->selectedRequest['status'];
        $request->save();

        $this->reset(['assignTo', 'comments', 'selectedRequest']);
    }


    public function render()
    {
        // $selectedRequest = $this->requests[$this->selectedIndex] ?? null;
        return view('livewire.request-process', [

            'ClosedRequests' => $this->ClosedRequests,
            'inProgressRequests' => $this->inProgressRequests,
            'viewingDetails' => $this->viewingDetails,
            'requests' => $this->requests,
            'activeTab' => $this->activeTab,

        ]);
    }
}
