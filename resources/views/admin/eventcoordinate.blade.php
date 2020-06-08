@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Dashboard')
@section('cardheading','Create Event Co-Ordinate')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
@endpush

@section('content')

    @if(count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

<div align="left">
       <form method="post" enctype="multipart/form-data" action="{{ route('admin.eventcoordinate.store') }}">
        {{ csrf_field() }}
        <div class="form-group row">
          <label for="event_coordinator_price" class="col-sm-2 col-form-label">Update Price</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="event_coordinator_price" id="event_coordinator_price" placeholder="event coordinator price" value="{{ $eventcoordinate->event_coordinator_price }}">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-10">
            {{--  <button type="submit" class="btn btn-primary">Submit</button>  --}}
            <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary" />
          </div>
        </div>
      </form>
</div>

@endsection


@push('js')
<script>
$(function () {
    $("#example1").DataTable();
});
</script>
@endpush
