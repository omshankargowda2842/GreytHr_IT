<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServiceExport implements FromCollection,WithHeadings,WithStyles
{
    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records->map(function ($record) {
            return [
                'ID' => $record->id ?? '-',
                'Service ID' => $record->snow_id ?? '-',
                'Category' => $record->category ?? '-',
                'Employee ID' => $record->emp_id ?? '-',
                'Short Description' => $record->short_description ?? '-',
                'Description' => $record->description ?? '-',
                'Active Service Notes' => $record->active_ser_notes ?? '-',
                'Service Assigned To' => $record->ser_assign_to ?? '-',
                'Service Pending Notes' => $record->ser_pending_notes ?? '-',
                'Service In Progress Notes' => $record->ser_inprogress_notes ?? '-',
                'Service Completed Notes' => $record->ser_completed_notes ?? '-',
                'Service Cancel Notes' => $record->ser_cancel_notes ?? '-',
                'Service Customer Visible Notes' => $record->ser_customer_visible_notes ?? '-',
                'Service Progress Since' => $record->ser_progress_since ?? '-',
                'Total Service Progress Time' => $record->total_ser_progress_time ?? '-',
                'Service End Date' => $record->ser_end_date ?? '-',
                'Priority' => $record->priority ?? '-',
                'Assigned Department' => $record->assigned_dept ?? '-',
                'Status Code' => $record->status_code ?? '-',
                'Created At' => $record->created_at ? $record->created_at->format('d-M-Y') . '"' : '-',
                'Updated At' => $record->updated_at ? $record->updated_at->format('d-M-Y') . '"' : '-',

            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID', 'Service ID', 'Category', 'Employee ID', 'Short Description', 'Description',
           'Active Service Notes',
            'Service Assigned To', 'Service Pending Notes',
             'Service In Progress Notes',
            'Service Completed Notes',
            'Service Cancel Notes',
            'Service Customer Visible Notes',
            'Service Progress Since',
            'Total Service Progress Time','Service End Date',
            'Priority', 'Assigned Department',
            'Status Code',' Created At' ,'Updated At'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Applies bold styling to the first row (headings)
        ];
    }

}
