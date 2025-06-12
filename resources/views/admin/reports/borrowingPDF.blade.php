{{-- INSTALL: composer require barryvdh/laravel-dompdf --}}

<!DOCTYPE html>
<html>
<head>
    <title>Borrowing Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h2 { text-align: center; color: #2d3748; margin-bottom: 20px; }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h2>Borrowing Report</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Borrowing Date</th>
                <th>Borrower's Name</th>
                <th>Borrowed Book</th>
                <th>Return Due</th>
                <th>Status</th>
                <th>Fine</th>
                <th>Fine Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowings as $index => $brw)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $brw->borrowing_date }}</td>
                <td>{{ $brw->user->name }}</td>
                <td>{{ $brw->book->title_book }}</td>
                <td>{{ $brw->return_due }}</td>
                <td>
                    @if ($brw->status_returned == 0)
                        Unreturned
                    @else
                        Returned on {{ \Carbon\Carbon::parse($brw->return_date)->format('Y-m-d') }}
                    @endif
                </td>
                <td>
                    @if(isset($brw->fine->fine_total) && $brw->fine->fine_total > 0)
                        Rp{{ number_format($brw->fine->fine_total, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($brw->fine && $brw->fine->status_fine == 0)
                        Unpaid
                    @elseif($brw->fine)
                        Paid on {{ \Carbon\Carbon::parse($brw->fine->date_finepayment)->format('Y-m-d') }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
