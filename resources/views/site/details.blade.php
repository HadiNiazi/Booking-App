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

    @if (session('booking_failed'))
        <div class="alert alert-danger" role="alert">
            {{ session()->get('booking_failed') }}
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-2">
            <a href="{{ route('site.home') }}" class="btn btn-info text-white">Go Back</a>
        </div>
    </div>

    <div class="event-details-section">
        @if ($event)
            <div class="row">
                <div class="col-12">
                    <!-- Placeholder with Icon Instead of Image -->


                    <h2 class="event-title">{{ $event->name }}</h2>



                    <p class="card-text"><strong>Price:</strong>

                        @if ($event->type == 'PAID')
                            <span> ${{ $event->price }} </span>
                        @else
                            <span> <span class="badge badge-free">Free</span></span>
                        @endif

                    </p>


                    <p class="card-text"><strong>Location:</strong> {{ $event->location }}</p>
                    <p class="card-text"><strong>Category:</strong> {{ $event->category ? $event->category->name: '' }} </p>
                    <p class="card-text"><strong>Start Date:</strong> {{ date('D M Y', strtotime($event->start_date)) }} </p>
                    <p class="card-text"><strong>End Date:</strong> {{ date('D M Y', strtotime($event->end_date)) }} </p>
                    <p class="card-text"><strong>Max Attendees:</strong> {{ $event->max_attendees }}</p>
                    <p class="card-text"><strong>Description:</strong> {{ $event->description }}</p>

                    </p>

                    <div class="text-center">
                        <form action="{{ route('checkout') }}">
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <button class="btn btn-success btn-lg">Checkout</button>
                        </form>
                    </div>

                </div>
            </div>


        @else
            <p class="text-danger text-center text-bold mt-3">Unable to find the event details.</p>
        @endif
    </div>
</div>
@endsection

