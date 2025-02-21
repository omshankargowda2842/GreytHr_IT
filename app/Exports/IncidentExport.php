<?php

namespace App\Exports;

use App\Models\Incident;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class IncidentExport implements FromCollection, WithHeadings ,WithStyles
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
                'Incident ID' => $record->snow_id ?? '-',
                'Category' => $record->category ?? '-',
                'Employee ID' => $record->emp_id ?? '-',
                'Short Description' => $record->short_description ?? '-',
                'Description' => $record->description ?? '-',
                'Active Incident Notes' => $record->active_inc_notes ?? '-',
                'Incident Assigned To' => $record->inc_assign_to ?? '-',
                'Incident Pending Notes' => $record->inc_pending_notes ?? '-',
                'Incident In Progress Notes' => $record->inc_inprogress_notes ?? '-',
                'Incident Completed Notes' => $record->inc_completed_notes ?? '-',
                'Incident Cancel Notes' => $record->inc_cancel_notes ?? '-',
                'Incident Customer Visible Notes' => $record->inc_customer_visible_notes ?? '-',
                'In Progress Since' => $record->in_progress_since ?? '-',
                'Total Incident Progress Time' => $record->total_in_progress_time ?? '-',
                'Incident End Date' => $record->inc_end_date ?? '-',
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
            'ID', 'Incident ID', 'Category', 'Employee ID', 'Short Description', 'Description',
            'Active Incident Notes','Incident Assigned To', 'Incident Pending Notes','Incident In Progress Notes',
            'Incident Completed Notes', 'Incident Cancel Notes','Incident Customer Visible Notes','In Progress Since',
            'Total Incident Progress Time','Incident End Date',
            'Priority', 'Assigned Department',
            'Status Code',' Created At' ,'Updated At'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Applies bold styling to the first row (headings)
            'T:U' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Date columns
        ];
    }

}
