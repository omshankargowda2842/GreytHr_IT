<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chating extends Model
{
    protected $fillable=[
        'receiver_id',
        'sender_id'
    ];


    // // public function messages()
    // {
    //     return $this->hasMany(Message::class);
    // }


    public function getReceiver()
    {
       
        if ($this->sender_id === auth()->id()) {

            return EmployeeDetails::firstWhere('emp_id',$this->receiver_id);

        } else {

            return EmployeeDetails::firstWhere('emp_id',$this->sender_id);
        }
        

    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }


   public function scopeWhereNotDeleted($query) 
     {
        $userId=auth()->id();
        return $query->where(function ($query) use ($userId){

            #where message is not deleted
            $query->whereHas('messages',function($query) use($userId){

                $query->where(function ($query) use($userId){
                    $query->where('sender_id',$userId)
                        ->whereNull('sender_deleted_at');
                })->orWhere(function ($query) use ($userId){

                    $query->where('receiver_id',$userId)
                    ->whereNull('receiver_deleted_at');
                });


            })
             #include conversations without messages
              ->orWhereDoesntHave('messages');


            // // })
             #include conversations without messages
            

        });
        
    }



  public  function isLastMessageReadByUser():bool {


        $user=Auth()->User();
        $lastMessage= $this->messages()->latest()->first();
   
        if($lastMessage){
           
            return  $lastMessage->read_at !==null && $lastMessage->sender_id == $user->id;
        }
       
        
    }




   public  function unreadMessagesCount() : int {


    return $unreadMessages= Message::where('chating_id','=',$this->id)
                                ->where('receiver_id',auth()->user()->emp_id)
                                ->whereNull('read_at')->count();

    }

    

}