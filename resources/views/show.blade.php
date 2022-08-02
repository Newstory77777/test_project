@extends('layouts.show')
@section('content')
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Open</th>
            <th scope="col">High</th>
            <th scope="col">Low</th>
            <th scope="col">Close</th>
            <th scope="col">Volume</th>
        </tr>
        </thead>
        <tbody>
        @foreach($prices as $price)
            <tr>
                <th scope="row">{{$price['format_date']}}</th>
                <td>{{$price['open']}}</td>
                <td>{{$price['high']}}</td>
                <td>{{$price['low']}}</td>
                <td>{{$price['close']}}</td>
                <td>{{$price['volume']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
