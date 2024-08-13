<?php

namespace App\Livewire;

use App\Models\HelpDesks;
use App\Models\Request;
use Livewire\Component;

class Dashboard extends Component
{
    public $activeCount;
    public $categories;
    public $countRequests;
    public $helpDeskCategories;
    public $matchingCount;
    public $pendingCount;
    public $closedCount;


    public function mount(){
        $this->updateCounts();
        $categories = Request::where('Request', 'IT')
                     ->select('category')
                     ->distinct() // Get unique categories
                     ->pluck('category'); // Get the categories as a collection

    $this->categories = $categories;

    $this->countRequests = HelpDesks::get() ;

    }

    public function updateCounts()
{
    $this->activeCount = HelpDesks::where('status', 'Open')
        ->whereIn('category', [
            'Request For IT', 'Distribution List Request', 'New Laptop',
            'New Distribution Request', 'New Mailbox Request', 'Devops Access Request',
            'New ID Card', 'MMS Request', 'Desktop Request', 'N/A'
        ])->count();

    $this->pendingCount = HelpDesks::where('status', 'Pending')
        ->whereIn('category', [
            'Request For IT', 'Distribution List Request', 'New Laptop',
            'New Distribution Request', 'New Mailbox Request', 'Devops Access Request',
            'New ID Card', 'MMS Request', 'Desktop Request', 'N/A'
        ])->count();

    $this->closedCount = HelpDesks::where('status', 'Completed')->count();

   
}


    public function itRequest(){
    return redirect()->route('requests');
    }
    public function render()
    {
        $this->activeCount = HelpDesks::where('status', 'Open')
        ->whereIn('category', [
            'Request For IT', 'Distribution List Request', 'New Laptop',
            'New Distribution Request', 'New Mailbox Request', 'Devops Access Request',
            'New ID Card', 'MMS Request', 'Desktop Request', 'N/A'
        ])->count();

        $this->pendingCount = HelpDesks::where('status', 'Pending')
            ->whereIn('category', [
                'Request For IT', 'Distribution List Request', 'New Laptop',
                'New Distribution Request', 'New Mailbox Request', 'Devops Access Request',
                'New ID Card', 'MMS Request', 'Desktop Request', 'N/A'
            ])->count();

        $this->closedCount = HelpDesks::where('status', 'Completed')->count();

        return view('livewire.dashboard');
    }
}
