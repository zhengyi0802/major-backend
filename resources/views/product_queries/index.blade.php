@extends('adminlte::page')

@section('title', __('product_queries.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('product_queries.header') }}</h1>
@stop

@section('content')
    <table class="table table-bordered">
        <tr class="form-group bg-blue">
            <th>{{ __('product_queries.id') }}</th>
            <th>{{ __('product_queries.serialno') }}</th>
            <th>{{ __('product_queries.ether_mac') }}</th>
            <th>{{ __('product_queries.wifi_mac') }}</th>
            <th>{{ __('product_queries.keywords') }}</th>
            <th>{{ __('product_queries.query') }}</th>
            <th width="280px">{{ __('tables.action') }}</th>
        </tr>
        @foreach ($records as $product_query)
        <tr>
            <td>{{ $product_query->id }}</td>
            <td>{{ $product_query->serialno }}</td>
            <td>{{ $product_query->ether_mac  }}</td>
            <td>{{ $product_query->wifi_mac  }}</td>
            <td>{{ $product_query->keywords }}</td>
            <td>{{ $product_query->query }}</td>
            <td>
                <a class="btn btn-info" href="{{ route('product_queries.show',$product_query->id) }}">{{ __('tables.details') }}</a>
            </td>
        </tr>
        @endforeach

    </table>
    {!! $records->links() !!}
@endsection
