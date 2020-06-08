@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Dashboard')
@section('cardheading','Tosshead Admin Dashboard')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
@endpush

@section('content')

<p>1. Welcome to tosshead dashboard. Please feel free to use the features given. Kindly update us in case of any issues reported.</p>

<p>2. Change password and logout options are given on the top on the page.</p>

@endsection


@push('js')
<script>
$(function () {
    $("#example1").DataTable();
});
</script>
@endpush
