<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Body and Background */
        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #e0f7fa 100%);
            font-family: Arial, sans-serif;
            color: #333;
        }

        /* Header Section */
        .header-section {
            text-align: center;
            padding: 3rem 0;
            background-color: #5dbf73; /* Semi-transparent */
            color: #ffffff;
            margin-bottom: 2rem;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        }
        .header-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .header-section p {
            font-size: 1.2rem;
            color: #f5f5f5;
        }

        /* Events Section */
        .events-section {
            padding: 2rem;
            background: rgba(255, 255, 255, 0.8); /* Light, semi-transparent */
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Event Card */
        .card {
            border: 1px solid #ddd;
            border-radius: 12px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.9); /* Transparent background */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            padding: 1rem;
            font-weight: bold;
            color: #ffffff;
            background: #64c178;
            text-align: center;
        }
        .card-body {
            padding: 1.5rem;
        }
        .event-title {
            font-weight: bold;
            color: #003C71;
            font-size: 1.2rem;
        }
        .card-text {
            color: #555555;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .price {
            font-size: 1.3rem;
            color: #b53131;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .badge-free {
            background-color: #31a8c6;
            color: #ffffff;
        }
        .badge-paid {
            background-color: #dc3545;
            color: #ffffff;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }
        .btn:hover {
            transform: scale(1.07);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>

    @yield('css')
</head>
<body>

    @yield('header')
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('script')

</body>
</html>
