@extends('layouts.auth')

@section('title', 'Show Event')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.css">
<style>
    #event-details th {
        width: 10%;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title"> Events Details </h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li>
        <li class="breadcrumb-item"><a href="#">Show</a></li>
      </ol>
    </nav>
  </div>
  <div class="row">

    <div class="row mb-3">
        <div class="col-2">
            <a href="{{ route('events.index') }}" class="btn btn-info">Go Back</a>
        </div>
    </div>


    <div class="col-lg-12 grid-margin stretch-card">

      <div class="card">
        <div class="card-body">

          <div class="table-responsive">
            @if ($event)
            <table id="event-details" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <td>{{ $event->name }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $event->type }}</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>Isb</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{ $event->category ? $event->category->name : '' }}</td>
                    </tr>

                    @if ($event->type != 'FREE')
                        <tr>
                            <th>Price</th>
                            <td>{{ $event->price }}</td>
                        </tr>
                    @endif

                    <tr>
                        <th>Start Date</th>
                        <td>{{ date('D d/m/Y', strtotime($event->start_date)) }}</td>
                    </tr>

                    <tr>
                        <th>End Date</th>
                        <td>{{ date('D d/m/Y', strtotime($event->end_date)) }}</td>
                    </tr>

                    <tr>
                        <th>Max Attendee</th>
                        <td>{{ $event->max_attendees }}</td>
                    </tr>

                    @if ($event->created_at)
                    <tr>
                        <th>Created At</th>
                        <td>{{ $event->created_at->diffForHumans() }}</td>
                    </tr>
                    @endif


                </thead>
            </table>

            <br>
            <p style="color: #6c7293"><b>Description:</b></p>
            <p style="color: #6c7293">{{ $event->description }}</p>

            @else
            <p class="text-danger text-bold text-center mt-3"><b>Unable to find the event details.</b></p>
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
