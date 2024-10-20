@extends('layouts.auth')

@section('css')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.css">
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title"> Events </h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Events</a></li>
      </ol>
    </nav>
  </div>
  <div class="row">

    <div class="row mb-3">
        <div class="col-2">
            <a href="{{ route('events.create') }}" class="btn btn-info">New Event</a>
        </div>
    </div>
    <div class="container">
        @if (session('success_msg'))
            <div class="alert alert-success" role="alert">
                <strong>Good Job!</strong> {{ session()->get('success_msg') }}
            </div>
        @endif
        @if (session('error_msg'))
            <div class="alert alert-danger" role="alert">
                <strong>Good Job!</strong> {{ session()->get('error_msg') }}
            </div>
        @endif
    </div>


    <div class="col-lg-12 grid-margin stretch-card">

      <div class="card">
        <div class="card-body">

          <div class="table-responsive">
            @if(count($events) > 0)
            <table id="event-table" class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Max Attendees</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $event->name }}</td>
                            <td>{{ Str::limit($event->description, 15) }}</td>
                            <td>{{ $event->category ? $event->category->name: '' }}</td>
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
                            <td>
                                @if ($event->type == 'FREE')
                                    <span class="badge badge-primary">{{ $event->type }}</span>
                                @elseif ($event->type == 'PAID')
                                    <span class="badge badge-success">{{ $event->type }}</span>
                                @else
                                    <span class="badge badge-info">{{ $event->type }}</span>
                                @endif
                            </td>
                            <td>{{ number_format($event->price) }}</td>
                            <td>{{ $event->max_attendees }}</td>
                            <td style="display: flex">
                                <a href="" class="btn btn-success">Show</a> &nbsp;
                                <a href="" class="btn btn-info">Edit</a> &nbsp;
                                <form action="">
                                    <button class="btn btn-danger">Delete</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-danger text-bold">No event created yet.</p>
            @endif
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.js"></script>
<script>
    new DataTable('#event-table');
    // let table = new Datatable('#event-table');
</script>
@endsection
