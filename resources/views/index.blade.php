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
            <input name="start_date" type="text" class="form-control" id="start_date" value="{{old('start_date')}}">
            @error('start_date')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input name="end_date" type="text" class="form-control" id="end_date" value="{{old('end_date')}}">
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
@endsection
