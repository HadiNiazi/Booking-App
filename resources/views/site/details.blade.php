@extends('layouts.site')

@section('css')
<style>
    body {
            background: linear-gradient(135deg, #e0e7ff 0%, #e0f7fa 100%);
            font-family: Arial, sans-serif;
            color: #333;
        }

        /* Header Section */
        .header-section {
            text-align: center;
            padding: 3rem 0;
            background-color: #5dbf73;
            color: #ffffff;
            margin-bottom: 2rem;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        }
        .header-section h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .header-section p {
            font-size: 1.2rem;
            color: #f5f5f5;
        }

        /* Event Details Section */
        .event-details-section {
            padding: 2rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        /* Event Placeholder */
        .event-placeholder {
            background: linear-gradient(135deg, #e0e7ff, #e0f7fa);
            border-radius: 12px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .event-placeholder i {
            font-size: 5rem;
            color: #5dbf73;
        }

        /* Price Badge */
        .price-badge {
            background-color: #1e8d52;
            color: white;
            font-size: 1.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
        }

        /* Stripe Checkout Section */
        .checkout-section {
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 12px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        /* Button Hover Effect */
        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }
</style>

<script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')

@section('header')
<div class="header-section">
    <h1>Event Details</h1>
    <p>Get all the details about the event and complete your booking below.</p>
</div>
@endsection

<div class="container my-5">
    <div class="event-details-section">
        <div class="row">
            <div class="col-12">
                <!-- Placeholder with Icon Instead of Image -->


                <h2 class="event-title">Amazing Event</h2>
                <p class="card-text"><strong>Location:</strong> Event Location</p>
                <p class="card-text"><strong>Category:</strong> Category Name</p>
                <p class="card-text"><strong>Date:</strong> 01/01/2024 - 01/02/2024</p>
                <p class="card-text"><strong>Max Attendees:</strong> 50</p>
                <p class="card-text"><strong>Description:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vel turpis cursus, bibendum dolor vel, tincidunt ex.</p>
                <p class="price-badge">Price: $20</p>
            </div>
        </div>

        <!-- Stripe Checkout Section -->
        <div class="checkout-section mt-4">
            <h3>Complete Your Payment</h3>
            <p>Enter your payment information below to secure your booking.</p>
            <form id="payment-form">
                <div class="mb-3">
                    <label for="card-element" class="form-label">Credit or debit card</label>
                    <div id="card-element" class="form-control">
                        <!-- Stripe Card Element will be inserted here -->
                    </div>
                    <div id="card-errors" role="alert" style="color: red; margin-top: 10px;"></div>
                </div>
                <button class="btn w-100" style="background-color: #1e8d52; color: white;" id="submit-button">Pay $20</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Initialize Stripe with your public key
    const stripe = Stripe('YOUR_PUBLIC_STRIPE_KEY');
    const elements = stripe.elements();

    // Create an instance of the card Element
    const card = elements.create('card', { style: { base: { fontSize: '16px', color: '#32325d' }}});
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element
    card.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: card,
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            alert('Payment successful! Your booking has been confirmed.');
            // Here you would normally send the paymentMethod.id to your server for further processing.
        }
    });
</script>
@endsection
