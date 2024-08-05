<?php
     namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class EmpPersonalInfo extends Model
    {
        use HasFactory;
        protected $fillable = [
            'emp_id',
            'title',
            'first_name',
            'last_name',
            'date_of_birth',
            'gender',
            'blood_group',
            'image',
            'signature',
            'nationality',
            'religion',
            'marital_status',
            'physically_challenge',
            'email',
            'mobile_number',
            'alternate_mobile_number',
            'city',
            'state',
            'postal_code',
            'country',
            'qualification',
            'company_name',
            'designation',
            'experience',
            'present_address',
            'permenant_address',
            'passport_no',
            'pan_no',
            'adhar_no',
            'pf_no',
            'nick_name',
            'biography',
            'facebook',
            'twitter',
            'linked_in',
            'status',
            'skill_set',

        ];
    }
