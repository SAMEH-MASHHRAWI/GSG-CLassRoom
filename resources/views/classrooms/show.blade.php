@extends('layouts.master')
@section('title','Classroom')
@section('content')

<div class="container">
                    {{-- <h1>Create Classroom</h1>  --}}
                <h1>Classroom Details</h1>
                    <h2>{{$classroom->name}} (#{{$classroom->id}}</h2>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <span class="text-success fs-2">
                                    {{$classroom->code}}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <p>Initation Link: <a href="{{$initation_link}} ">{{$initation_link}} </a> </p>
                        </div>
                    </div>
        </div>
@endsection
