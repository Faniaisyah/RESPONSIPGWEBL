@extends('layouts.template')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-black-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="shadow card">
            <div class="card-header">
                <h3 class="card-title">Data</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="alert alert-primary" role="alert">
                            <h4><i class="fa-solid fa-location-dot"></i> Total Points</h4>
                            <p style="font-size: 28pt">{{$total_points}}</p>
                            <a href="{{ route('output-point') }}" class="btn btn-primary">View Points</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="alert alert-success" role="alert">
                            <h4><i class="fa-solid fa-route"></i> Total Polylines</h4>
                            <p style="font-size: 28pt">{{$total_polylines}}</p>
                            <a href="{{ route('output-polyline') }}" class="btn btn-success">View Polylines</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="alert alert-danger" role="alert">
                            <h4><i class="fa-solid fa-draw-polygon"></i> Total Polygons</h4>
                            <p style="font-size: 28pt">{{$total_polygons}}</p>
                            <a href="{{ route('output-polygon') }}" class="btn btn-danger">View Polygons</a>
                        </div>
                    </div>
                </div>

                <hr>
                <p>
                    Anda login sebagai <span class="fw-bold">{{ Auth::user()->name }}</span> dengan email <span class="fst-italic">{{ Auth::user()->email }}</span>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection
