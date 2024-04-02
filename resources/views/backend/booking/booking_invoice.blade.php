<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice</title>

    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            margin-bottom: 10px;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            margin-top: 20px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer p {
            margin: 0;
        }

        .thanks {
            font-style: italic;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        {{-- <img src="logo.png" alt="EasyShop Logo" class="logo"> --}}
        <h2 style="color: green; font-size: 26px;"><strong>EasyShop</strong></h2>
        <h1>Invoice</h1>
    </div>

    <div class="invoice-details">
        <p>EasyShop Head Office<br>
            Email: support@easylearningbd.com<br>
            Mob: 1245454545<br>
            Dhaka 1207, Dhanmondi: #4</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Room Type</th>
                <th>Total Room</th>
                <th>Price</th>
                <th>Check In / Out Date</th>
                <th>Total Days</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $editData->room->type->name }}</td>
                <td>{{ $editData->number_of_rooms }}</td>
                <td>${{ $editData->actual_price }}</td>
                <td><span class="badge bg-primary">{{ $editData->check_in }}</span> / <span class="badge bg-warning  text-dark">{{ $editData->check_out }}</span></td>
                <td>{{ $editData->total_night }}</td>
                <td>${{ $editData->actual_price * $editData->number_of_rooms }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>Subtotal: ${{ $editData->subtotal }}</p>
        <p>Discount: ${{ $editData->discount }}</p>
        <p>Grand Total: ${{ $editData->total_price }}</p>
    </div>

    <div class="footer">
        <p>Thanks For Your Booking..!!</p>
        <p>-----------------------------------</p>
        <p>Authority Signature:</p>
    </div>
</div>

</body>
</html>
