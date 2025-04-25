<!DOCTYPE html>
<html>
<head>
    <title>Your Purchase Cancellation Confirmation</title>
    <style>
        /* Inline CSS */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .ticket-info {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .ticket-item {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #eee;
        }
        .show-info {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Cancellation</h1>
            <p>Your purchase with ID {{ $purchase->id }} has been cancelled.</p>
            <p>Your refund amount is: ₹{{ number_format($refundAmount, 2) }}</p>
        </div>

        @php $show = $purchase->tickets()->first()->showSeat->first()->show; @endphp

        <div class="ticket-info">
            <h2>Ticket Information</h2>
            <div class="show-info">
                <h3>Show: {{ $show->name }}</h3>
                <p><strong>Date:</strong> {{ $show->date_time->format('Y-m-d') }}</p>
                <p><strong>Time:</strong> {{ $show->date_time->format('H:i:s') }}</p>
                <p><strong>Artist:</strong> {{ $show->artist }}</p>
            </div>
            @foreach ($purchase->tickets as $ticket)
                <div class="ticket-item">
                    <p><strong>Row:</strong> {{ $seatsInfo[$ticket->id]['row'] }}</p>
                    <p><strong>Seat:</strong> {{ $seatsInfo[$ticket->id]['seat'] }}</p>
                    <p><strong>Type:</strong> {{ $ticket->showSeat->seat_type }}</p>
                </div>
            @endforeach
        </div>

        <p>Please note that the refund will be processed to your original payment method. If you have any questions, please contact us.</p>
    </div>
</body>
</html>
