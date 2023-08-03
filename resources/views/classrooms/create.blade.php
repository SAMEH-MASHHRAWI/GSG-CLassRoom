@extends('layouts.master')
@section('title','Classroom')
@section('content')
<div class="container">
                <h1>Create Classroom</h1>
                {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}} </li>
                        @endforeach
                    </ul>

                </div> --}}

                {{-- @endif --}}
                <form action="{{route('classrooms.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{-- <input type="hidden" name="_token" value="{{csrf_token()}}"> الطريقة اليدوية ل استبدال ال csrf --}}
                    @include('classrooms._form',[
                        'button_lable'=>'Create Room'
                    ])

                </form>
            </div>
@endsection
