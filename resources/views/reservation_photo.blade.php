<!-- resources/views/reservation_photo.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            padding: 20px;
            border: 2px solid #000;
            width: 600px;
            margin: 0 auto;
        }
        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .content p {
            margin: 5px 0;
        }
    </style>
    <title></title>
</head>
<body>
<div class="container">
    <div class="title">{{ $reservation->movie->name }}</div>
    <div class="content">
        <p>Date: {{ $reservation->movie->showing_date }}</p>
        <p>Time: {{ \Carbon\Carbon::parse($reservation->movie->start_time)->format('h:i A') }}</p>
        <p>Period: {{ \Carbon\Carbon::parse($reservation->movie->start_time)->diff(\Carbon\Carbon::parse($reservation->movie->end_time))->format('%H:%I') }} hours</p>
        <p>Standard Seats: {{ $reservation->standard_seats }} - {{ $reservation->movie->standard_price }}$</p>
        <p>VIP Seats: {{ $reservation->vip_seats }} - {{ $reservation->movie->vip_price }}$</p>
        <p>Hall: {{ $reservation->movie->hall->name }}</p>
        <p>Total: {{ $reservation->total_price }}$</p>
        @if(count($reservation->consumables) > 0)
            <p>Consumables:</p>
            <ul>
                @foreach($reservation->consumables as $consumable)
                    <li>{{ $consumable->name }} (x{{ $consumable->pivot->quantity }}) - {{ $consumable->pivot->price * $consumable->pivot->quantity }} $</li>
                @endforeach
            </ul>
        @else
            <p>No consumables selected</p>
        @endif
    </div>
</div>
</body>
</html>
