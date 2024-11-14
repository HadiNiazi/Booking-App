@extends('layouts.auth')

@section('title', 'Edit Event')

@section('css')
<style>
    textarea.form-control, textarea.asColorPicker-input, .select2-container--default textarea.select2-selection--single, .select2-container--default .select2-selection--single textarea.select2-search__field, textarea.typeahead, textarea.tt-query, textarea.tt-hint {
        min-height: 5rem;
    }
</style>
@endsection

@section('content')

<div class="row mb-3">
    <div class="col-2">
        <a href="{{ route('events.index') }}" class="btn btn-info">Go Back</a>
    </div>
</div>

<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit Event</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" class="forms-sample" action="{{ route('events.update', $event->id) }}">

        @csrf
        @method('PATCH')

          <div class="form-group">
            <label for="exampleInputName1">Name</label>
            <input type="text" name="name" value="{{ old('name', $event->name) }}" class="form-control" placeholder="Name">
          </div>
          <div class="form-group">
            <label for="exampleInputName1">Description</label>
            <textarea name="description" class="form-control" cols="13" rows="5" placeholder="Enter desciption here...">{{ old('description', $event->description) }}</textarea>
          </div>
          <div class="form-group">
            <label for="exampleSelectGender">Categories</label>
            <select name="category" class="form-control" id="exampleSelectGender">
              <option value="" disabled selected>Choose Option</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected( old('category', $event->category_id) == $category->id )>{{ $category->name }}</option>
                    {{-- <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected': '' }} >{{ $category->name }}</option> --}}
                @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Location</label>
            <select name="location" class="form-control">
              <option value="" disabled selected>Choose Option</option>
                <option value="islamabad" @selected( old('location', strtolower($event->location)) == 'islamabad' )>Islamabad</option>
                <option value="karachi" @selected( old('location', strtolower($event->location)) == 'karachi' )>Karachi</option>
                <option value="lahore" @selected( old('location', strtolower($event->location)) == 'lahore' )>Lahore</option>
                <option value="rawalpindi" @selected( old('location', strtolower($event->location)) == 'rawalpindi' )>Rawalpindi</option>
            </select>
          </div>

          <div class="form-group">
            <label>Type</label>
            <select name="type" class="form-control">
              <option value="" disabled selected>Choose Option</option>
                <option value="free" @selected( old('type', strtolower($event->type)) == 'free' )>Free</option>
                <option value="paid" @selected( old('type', strtolower($event->type)) == 'paid' )>Paid</option>
            </select>
          </div>

          <div class="form-group">
            <label for="exampleInputCity1">Price</label>
            <input type="number" name="price" value="{{ old('price', $event->price) }}" class="form-control" placeholder="Enter price">
          </div>

          <div class="form-group">
            <label for="exampleInputCity1">Start date</label>
            <input type="date" name="start_date" value="{{ old('start_date', $event->start_date) }}" class="form-control">
          </div>

          <div class="form-group">
            <label for="exampleInputCity1">End date</label>
            <input type="date" name="end_date" value="{{ old('end_date', $event->end_date) }}" class="form-control">
          </div>

          <div class="form-group">
            <label for="exampleInputCity1">Max Attendees</label>
            <input type="number" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees) }}" class="form-control" placeholder="1">
          </div>

          <button type="submit" class="btn btn-primary me-2">Submit</button>
          <button class="btn btn-dark">Cancel</button>
        </form>
      </div>
    </div>
  </div>
@endsection
