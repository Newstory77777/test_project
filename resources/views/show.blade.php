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
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Charts</b></div>
                <div class="panel-body">
                    <canvas id="canvas" height="280" width="600"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        var Dates = new Array();
        var Opens = new Array();
        var Closes = new Array();
        $(document).ready(function () {
            var data = @json($prices);
            data.forEach(function (d) {
                Dates.push(d.format_date);
                Closes.push(d.close);
                Opens.push(d.open);
            });
            var ctx = document.getElementById("canvas").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Dates,
                    datasets: [{
                        label: 'Open price',
                        data: Opens,
                        borderColor: "#de4f54"
                    }, {
                        label: 'Close price',
                        data: Closes,
                        borderColor: "blue"

                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
    </script>

@endsection
