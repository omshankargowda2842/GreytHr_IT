<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\IT;
use Livewire\Component;

class OldItMembers extends Component
{
    public $itMembers = [];
    public $itRelatedEmye = [];
    public $showLogoutModal =false;


    public function mount()
    {
        $this->itMembers = EmployeeDetails::where('sub_dept_id', '9915')->get();
        $this->itRelatedEmye = IT::where('status', 0)->get();

    }

    public function restore($id)
    {
        $itMember = IT::find($id);

        if ($itMember) {
            $itMember->status = 1;
            $itMember->save();
            FlashMessageHelper::flashSuccess("IT member Restored successfully!");
            $this->showLogoutModal = false;
            $this->itRelatedEmye = IT::where('status', 1)->get();
        }
    }



    public function cancelLogout()
    {
         $this->showLogoutModal = true;
    }

    public function cancel(){
        $this->showLogoutModal = false;
    }


    public $sortColumn = 'employee_name'; // default sorting column
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


    public function render()
    {
        $this->itRelatedEmye = IT::where('status', 0)
        ->orderBy($this->sortColumn, $this->sortDirection)
        ->get();

       return view('livewire.old-it-members');
    }
}
