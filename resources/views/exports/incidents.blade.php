<!DOCTYPE html>
<html>

<head>
    <title>Incidents Report</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 5px;
        font-size: 10px;
    }

    th {
        background-color: #f2f2f2;
        text-align: left;
    }

    h1 {
        text-align: center;
        font-size: 18px;
    }
    .footer {
        text-align: center;
        font-size: 10px;
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <h1>Incidents Report</h1>
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Incident ID</th>
                <th>Category</th>
                <th>Employee ID</th>
                <th>Short Description</th>
                <th>Description</th>
                <th>Incident Assigned To</th>
                <th>Incident End Date</th>
                <th>Priority</th>
                <th>Status Code</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $index => $record)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $record->snow_id ?? '-' }}</td>
                <td>{{ $record->category ?? '-' }}</td>
                <td>{{ $record->emp_id ?? '-' }}</td>
                <td>{{ $record->short_description ?? '-' }}</td>
                <td>{{ $record->description ?? '-' }}</td>
                <td>{{ $record->inc_assign_to ?? '-' }}</td>
                <td>{{ $record->inc_end_date ?? '-' }}</td>
                <td>{{ $record->priority ?? '-' }}</td>
                <td>{{ $record->status_code ?? '-' }}</td>
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
