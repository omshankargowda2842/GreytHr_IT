<?php

namespace App\Livewire;

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
        $this->itRelatedEmye = IT::where('is_active', 0)->get();

    }

    public function restore($id)
    {
        $itMember = IT::find($id);

        if ($itMember) {
            $itMember->is_active = 1;
            $itMember->save();
            session()->flash('updateMessage', 'IT member Restored successfully!');
            $this->showLogoutModal = false;
            $this->itRelatedEmye = IT::where('is_active', 1)->get();
        }
    }



    public function cancelLogout()
    {
         $this->showLogoutModal = true;
    }

    public function cancel(){
        $this->showLogoutModal = false;
    }

    public function render()
    {
        $this->itRelatedEmye = IT::where('is_active', 0)->get();
        return view('livewire.old-it-members');
    }
}
