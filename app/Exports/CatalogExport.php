<?php

namespace App\Exports;

use App\Models\Catalog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CatalogExport implements FromCollection,WithHeadings,WithStyles
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
                'ID' => $record->id,
                'Employee ID' => $record->emp_id ?? '-',
                'Category' => $record->category ?? '-',
                'Subject' => $record->subject ?? '-',
                'Description' => $record->description ?? '-',
                'CC To' => $record->cc_to ?? '-',
                'Priority' => $record->priority ?? '-',
                'Status Code' => $record->status_code ?? '-',
                'Mail' => $record->mail ?? '-',
                'Mobile' => $record->mobile ?? '-',
                'Distributor Name' => $record->distributor_name ?? '-',
                'Selected Equipment' => $record->selected_equipment ?? '-',
                'Rejection Reason' => $record->rejection_reason ?? '-',
                'Active Comment' => $record->active_comment ?? '-',
                'Closed Notes' => $record->closed_notes ?? '-',
                'Cancel Notes' => $record->cancel_notes ?? '-',
                'Request End Date' => $record->req_end_date ?? '-',
                'Pending Remarks' => $record->pending_remarks ?? '-',
                'Pending Notes' => $record->pending_notes ?? '-',
                'In-Progress Notes' => $record->inprogress_notes ?? '-',
                'Category Progress Since' => $record->cat_progress_since ?? '-',
                'Total Category Progress Time' => $record->total_cat_progress_time ?? '-',
                'Assigned To' => $record->assign_to ?? '-',
                'Customer Visible Notes' => $record->customer_visible_notes ?? '-',
                'Created At' => $record->created_at ? $record->created_at->format('d-M-Y') . '"' : '-',
                'Updated At' => $record->updated_at ? $record->updated_at->format('d-M-Y') . '"' : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Employee ID',
            'Category',
            'Subject',
            'Description',
            'CC To',
            'Priority',
            'Status Code',
            'Mail',
            'Mobile',
            'Distributor Name',
            'Selected Equipment',
            'Rejection Reason',
            'Active Comment',
            'Closed Notes',
            'Cancel Notes',
            'Request End Date',
            'Pending Remarks',
            'Pending Notes',
            'In-Progress Notes',
            'Category Progress Since',
            'Total Category Progress Time',
            'Assigned To',
            'Customer Visible Notes',
            'Created At',
            'Updated At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Applies bold styling to the first row (headings)
        ];
    }
}
