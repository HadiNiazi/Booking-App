@extends('layouts.site')

@section('content')
<!-- Header Section -->

@section('header')
<div class="header-section">
    <h1>Explore Our Exclusive Events</h1>
    <p>Discover, book, and enjoy the best events tailored just for you. Don’t miss out on the fun!</p>

    {{-- @auth

    @else

    @endauth --}}

    <div class="mt-5">
        @if(auth()->check())
            <a href="{{ route('dashboard') }}" class="btn btn-success">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        @endif
    </div>

</div>
@endsection

<!-- Events Section -->
<div class="container my-5">
    <div class="events-section">
        <div class="row g-4">
            <!-- Event Card Template - Repeat for Each Event -->

            @if (count($events) > 0)
                @foreach ($events as $event)

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            {{ $event->name }}
                        </div>
                        <div class="card-body">
                            <p class="card-text"><strong>Location:</strong> {{ $event->location }}</p>
                            <p class="card-text"><strong>Category:</strong> {{ $event->category ? $event->category->name : '' }} </p>
                            <p class="card-text"><strong>Start Date:</strong> {{ date('D M Y', strtotime($event->start_date)) }}</p>
                            <p class="card-text"><strong>End Date:</strong> {{ date('D M Y', strtotime($event->end_date)) }}</p>
                            <p class="card-text"><strong>Seats Left:</strong> {{ $event->max_attendees }}</p>
                            <p class="price">
                                @if ($event->type == 'PAID')
                                  ${{ $event->price }}
                                @else
                                    <span class="badge badge-free">Free</span>
                                @endif
                            </p>
                            <a href="{{ route('site.event.details', $event->id) }}" class="btn w-100" style="background-color: #1e8d52; color:white">View Details</a>
                        </div>
                    </div>
                </div>

                @endforeach
            @else
            <p class="text-danger text-bold text-center pt-3"><b>No Event Held yet.</b></p>
            @endif


            <!-- Additional Event Cards here -->

            {{-- <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Event Name
                    </div>
                    <div class="card-body">
                        <!-- <h5 class="event-title">Paid Event</h5> -->
                        <p class="card-text"><strong>Location:</strong> Event Location</p>
                        <p class="card-text"><strong>Category:</strong> Category Name</p>
                        <p class="card-text"><strong>Start Date:</strong> 01/01/2024</p>
                        <p class="card-text"><strong>End Date:</strong> 01/02/2024</p>
                        <p class="card-text"><strong>Max Attendees:</strong> 20</p>
                        <p class="price">$0</p>
                        <button class="btn w-100" style="background-color: #1e8d52; color:white" onclick="alert('Booking successful!')">Book Now</button>
                    </div>
                </div>
            </div> --}}

        </div>

        @if (count($events) > 9)
            <nav aria-label="Event pagination" class="mt-4">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
</div>
@endsection


