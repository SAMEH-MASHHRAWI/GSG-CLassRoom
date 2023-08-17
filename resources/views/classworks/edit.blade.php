<x-main-layout title="Create Classwork">
<div class="container">
                <h1>Classroom Details</h1>
                    <h2>{{$classroom->name}} (#{{$classroom->id}})</h2>
                    <h3>Update Classworks</h3>
                    <x-alert name="success" class="alert-success" />
            <form action="{{route('classrooms.classworks.update',[$classroom->id,$classwork->id, 'type'=>$type])}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
               @include('classworks._form')
                <button type="submit" class="btn btn-primary">Update  Classwork</button>
            </form>
</div>
</x-main-layout>
