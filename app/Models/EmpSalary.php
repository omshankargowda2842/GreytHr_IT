<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class EmpSalary extends Model
{
    use HasFactory;

    protected $fillable = ['emp_id', 'dept_id', 'salary', 'effective_date'];

    /**
     * Set the salary attribute and encode it before saving.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setSalaryAttribute($value)
    {
        // Determine the number of decimal places
        $decimalPlaces = strpos($value, '.') !== false ? strlen(substr(strrchr($value, "."), 1)) : 0;

        // Convert the float to an integer representation
        $factor = pow(10, $decimalPlaces);
        $integerValue = intval($value * $factor);

        // Encode the integer value and decimal places
        $this->attributes['salary'] = Hashids::encode($integerValue, $decimalPlaces);
    }

    /**
     * Get the salary attribute and decode it when retrieving.
     *
     * @param  mixed  $value
     * @return mixed
     */
    /**
     * Get the salary attribute and decode it when retrieving.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public function getSalaryAttribute($value)
    {
        Log::info('Decoding salary: ' . $value);
        // Decode the hash
        $decoded = Hashids::decode($value);

        // Check if decoding was successful
        if (count($decoded) === 0) {
            return null;
        }

        // Retrieve the integer value and decimal places
        $integerValue = $decoded[0];
        $decimalPlaces = $decoded[1] ?? 0; // Default to 0 if not present

        // Convert back to float
        return $integerValue / pow(10, $decimalPlaces);
    }

}
