@extends('layouts.auth')

@section('title', 'Events')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-info">Edit</a> &nbsp;
                                <form class="event-delete-form" method="post" action="{{ route('events.destroy', $event->id) }}">
                                @csrf
                                @method('DELETE')
                                    <button class="btn btn-danger delete-btn">Delete</button>
                                </form>
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

@section('script')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.js"></script>
<script>
    new DataTable('#event-table');
    // let table = new Datatable('#event-table');

    $(document).ready(function() {

        $('.delete-btn').click(function(e) {

            e.preventDefault();

            // if ( confirm('Are you sure? You want to delete it') ) {
            //     $('.event-delete-form').submit();
            // }


            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {

                    $('.event-delete-form').submit();

                    // Swal.fire({
                    //     title: "Deleted!",
                    //     text: "Your file has been deleted.",
                    //     icon: "success"
                    // });
                }
            });

        })

    });
</script>
@endsection
