@extends('layouts.auth')

@section('title', 'Notifications')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Notifications </h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Notifications</a></li>
      </ol>
    </nav>
  </div>
  <div class="row">



    <div class="col-lg-12 grid-margin stretch-card">

      <div class="card">
        <div class="card-body">

          <div class="table-responsive">
            @if(count($notifications) > 0)
            <table id="event-table" class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Is Read</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($notifications as $notification)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $notification->title }}</td>
                            <td>{{ $notification->pivot->is_read == 1 ? 'Yes': 'No' }}</td>

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
@endsection
