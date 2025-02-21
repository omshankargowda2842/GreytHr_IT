<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog Report</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 10px;
        margin: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        font-size: 5px;
    }

    th {
        background-color: #f2f2f2;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h1 {
        margin: 0;
        font-size: 10px;
    }

    .footer {
        text-align: center;
        font-size: 10px;
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>Catalog Report</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Catalog ID</th>
                <th>Employee ID</th>
                <th>Category</th>
                <th>Subject</th>
                <th>CC To</th>
                <th>Priority</th>
                <th>Status Code</th>
                <th>Mail</th>
                <th>Mobile</th>
                <th>Distributor Name</th>
                <th>Selected Equipment</th>
                <th>Req End Date</th>
                <th>Assign To</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $record)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $record->request_id ?? '-' }}</td>
                <td>{{ $record->emp_id ?? '-' }}</td>
                <td>{{ $record->category ?? '-' }}</td>
                <td>{{ $record->subject ?? '-' }}</td>
                <td>{{ $record->cc_to ?? '-' }}</td>
                <td>{{ $record->priority ?? '-' }}</td>
                <td>{{ $record->status_code ?? '-' }}</td>
                <td>{{ $record->mail ?? '-' }}</td>
                <td>{{ $record->mobile ?? '-' }}</td>
                <td>{{ $record->distributor_name ?? '-' }}</td>
                <td>{{ $record->selected_equipment ?? '-' }}</td>
                <td>{{ $record->req_end_date ?? '-'  }}</td>
                <td>{{ $record->assign_to ?? '-'  }}</td>
                <td>{{ $record->created_at ? $record->created_at->format('d F, Y') : '-' }}</td>
                <td>{{ $record->updated_at ? $record->updated_at->format('d F, Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>

</html>
