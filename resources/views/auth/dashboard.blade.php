@extends('layouts.auth')

@section('title', 'Dashboard')

@section('content')
<div class="row">
<div class="col-sm-4 grid-margin">
    <div class="card">
    <div class="card-body">
        <h5>Completed</h5>
        <div class="row">
        <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex d-sm-block d-md-flex align-items-center">
            <h2 class="mb-0">{{ $completed_booking ? $completed_booking: 0 }}</h2>
            </div>
            <h6 class="text-muted font-weight-normal">Lifetime bookings</h6>
        </div>
        <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-codepen text-primary ms-auto"></i>
        </div>
        </div>
    </div>
    </div>
</div>
<div class="col-sm-4 grid-margin">
    <div class="card">
    <div class="card-body">
        <h5>Incomplete</h5>
        <div class="row">
        <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex d-sm-block d-md-flex align-items-center">
            <h2 class="mb-0">{{ $incomplete_bookings ? $incomplete_bookings: 0 }}</h2>
            </div>
            <h6 class="text-muted font-weight-normal"> Lifetime bookings</h6>
        </div>
        <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-wallet-travel text-danger ms-auto"></i>
        </div>
        </div>
    </div>
    </div>
</div>
<div class="col-sm-4 grid-margin">
    <div class="card">
    <div class="card-body">
        <h5>Categories</h5>
        <div class="row">
        <div class="col-8 col-sm-12 col-xl-8 my-auto">
            <div class="d-flex d-sm-block d-md-flex align-items-center">
            <h2 class="mb-0">{{ $categoriesCount ? $categoriesCount: 0 }}</h2>
            </div>
            <h6 class="text-muted font-weight-normal">Total Categories</h6>
        </div>
        <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
            <i class="icon-lg mdi mdi-monitor text-success ms-auto"></i>
        </div>
        </div>
    </div>
    </div>
</div>
</div>

<div class="row ">
<div class="col-12 grid-margin">
    <div class="card">
    <div class="card-body">
        <h4 class="card-title">Recent events</h4>
        <div class="table-responsive">
            @if(count($events) > 0)
            <table id="event-table" class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Max Attendees</th>
                    <th>Type</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $event->name }}</td>
                            {{-- <td>
                                @php
                                    $category = null;
                                @endphp
                                @if ($event->category)
                                    @php
                                        $category = $event->category->name;
                                    @endphp
                                @endif
                                {{ $category }}
                            </td> --}}
                            <td>{{ $event->location }}</td>

                            <td>{{ number_format($event->price) }}</td>
                            <td>{{ $event->max_attendees }}</td>
                            <td>
                                @if ($event->type == 'FREE')
                                    <span class="badge badge-primary">{{ $event->type }}</span>
                                @elseif ($event->type == 'PAID')
                                    <span class="badge badge-success">{{ $event->type }}</span>
                                @else
                                    <span class="badge badge-info">{{ $event->type }}</span>
                                @endif
                            </td>
                            <td style="display: flex">
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-success">Show</a> &nbsp;

                                @if (auth()->user()->role == 'admin')

                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-info">Edit</a> &nbsp;
                                    <form class="event-delete-form" method="post" action="{{ route('events.destroy', $event->id) }}">
                                    @csrf
                                    @method('DELETE')
                                        <button class="btn btn-danger delete-btn">Delete</button>
                                    </form>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-danger text-bold text-center mt-3"><b>No event created yet.</b></p>
            @endif
        </div>
    </div>
    </div>
</div>
</div>
@endsection
