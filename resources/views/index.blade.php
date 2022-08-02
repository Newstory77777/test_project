@extends('layouts.main')
@section('content')
    <form action="{{route('show')}}" method="post">
        @csrf
        <div class="mb-3">
            <label for="symbol" class="form-label">Country Symbol</label>
            <input name="symbol" type="text" class="form-control" id="symbol" value="{{old('symbol')}}">
            @error('symbol')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <div class="col-sm-4">
                <div class="input-group date" id="start_date_datepicker">
                    <input type="text" class="form-control" name="start_date" value="{{old('start_date')}}">
                    <span class="input-group-append">
                            <span class="input-group-text bg-white d-block">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </span>
                </div>
            </div>
            @error('start_date')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <div class="col-sm-4">
                <div class="input-group date" id="end_date_datepicker">
                    <input type="text" class="form-control" name="end_date" value="{{old('end_date')}}">
                    <span class="input-group-append">
                            <span class="input-group-text bg-white d-block">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </span>
                </div>
            </div>
            @error('end_date')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input name="email" type="text" class="form-control" id="email" value="{{old('email')}}">
            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <script type="text/javascript">
        $(function () {
            $('#start_date_datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#end_date_datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
