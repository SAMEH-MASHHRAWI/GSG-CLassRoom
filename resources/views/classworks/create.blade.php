<x-main-layout title="Create Classwork">
<div class="container">
                <h1>Classroom Details</h1>
                    <h2>{{$classroom->name}} (#{{$classroom->id}})</h2>
                    <h3>Create Classworks</h3>
            <form action="{{route('classrooms.classworks.store',[$classroom->id, 'type'=>$type])}}" method="post" enctype="multipart/form-data">
                            @csrf

               @include('classworks._form')
                    <button type="submit" class="btn btn-primary">Create Classwork</button>

            </form>
            </div>
</x-main-layout>
