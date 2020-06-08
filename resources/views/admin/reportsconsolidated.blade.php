@extends('layouts.backend.app')
@section('title','dashboard')
@section('pageheading','Consolidated Reports')
@section('cardheading','Consolidated Reports')
@section('breadcrum','Home')
@section('subheading','dashboard')

@push('css')
<style>
#accordion
{
    border: 1px solid gray;
    margin-bottom: 20px;
}
.panel-title
{
    font-size: 20px;
    padding: 6px;
}
</style>
@endpush

@section('content')

    @foreach ($data_array as $key => $value)

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading{{ $key }}">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapse{{ $key }}">
                            <i class="fas fa-plus"></i>
                            Year {{ $key }}
                        </a>
                    </h4>
                </div>
                <div id="collapse{{ $key }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $key }}">
                    <div class="panel-body">

                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Month</th>
                                    <th scope="col">No Of Events</th>
                                    <th scope="col">Total Sales</th>
                                    <th scope="col">Net Profit Loss</th>
                                </tr>
                            </thead>
                            <tbody>

                                    @foreach ($value as $item_key => $item_value)

                                    <tr>
                                        <th scope="row">{{ $item_key }}</th>
                                        <td>{{ $item_value['total_monthly_events'] }}</td>
                                        <td>{{ $item_value['total_monthly_billing'] }}</td>
                                        <td>{{ $item_value['total_monthly_profit'] }}</td>
                                    </tr>

                                    @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @endforeach

@endsection

@push('js')

@endpush
