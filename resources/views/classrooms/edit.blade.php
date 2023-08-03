@extends('layouts.master')
@section('title','Classroom')
@section('content')
          <div class="container">
            <h1>Create Classroom</h1>
                <form action="{{route('classrooms.update',$classroom->id)}} " method="post" enctype="multipart/form-data">
                    @csrf
                    {{-- <input type="hidden" name="_token" value="{{csrf_token()}}"> الطريقة اليدوية ل استبدال ال csrf --}}
                    @method('put')
                     @include('classrooms._form',[
                        'button_lable'=>'Update Room'
                    ])
                </form>
            </div>
@endsection
