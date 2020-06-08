@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Dashboard')
@section('cardheading','DataTable with minimal features & hover style')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
@endpush

@section('content')

        <table id="example1" class="table table-bordered table-hover">
            <thead>
            <tr>
            <th>Rendering engine</th>
            <th>Browser</th>
            <th>Platform(s)</th>
            <th>Engine version</th>
            <th>CSS grade</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            <td>Trident</td>
            <td>Internet
                Explorer 4.0
            </td>
            <td>Win 95+</td>
            <td> 4</td>
            <td>X</td>
            </tr>
            <td>Other browsers</td>
            <td>All others</td>
            <td>-</td>
            <td>-</td>
            <td>U</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
            <th>Rendering engine</th>
            <th>Browser</th>
            <th>Platform(s)</th>
            <th>Engine version</th>
            <th>CSS grade</th>
            </tr>
            </tfoot>
        </table>

@endsection


@push('js')
<script>
$(function () {
    $("#example1").DataTable();
});
</script>
@endpush
