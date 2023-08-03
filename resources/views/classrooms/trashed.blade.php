<x-main-layout title="Trashed Classrooms" class="">

    <div class="container">
                    <h1>Trashed Classroom</h1>
                        <x-alert  name="success" id="success" class="alert-success"/>
                        <x-alert  name="error" id="danger" class="alert-danger"/>
                        <div class="row">

                    @foreach ($classrooms as $classroom)
                        <div class="col-md-3">
                            <div class="card" style="width: 18rem;">
                                <img src="storage/{{ $classroom->cover_image_path}}" class="card-img-top" alt="image">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $classroom->name}} </h5>
                                    <p class="card-text">{{ $classroom->section}}</p>
                                   <div class="d-flex justify-content-between" >
                                    <a href="{{route('classrooms.show', $classroom->id)}} " class="btn btn-primary">View</a>

                                     <form action="{{route('classroom.restore', $classroom->id )}} " method="post">
                                    @csrf
                                        @method( 'put')
                                        <button  class="btn btn-sm btn-success" >Restore</button>
                                </form>
                                <form action="{{route('classroom.force-delete',$classroom->id)}}" method="post">
                                    @csrf
                                        @method( 'delete')
                                        <button  class="btn btn-sm btn-danger" >Delete For Evere</button>
                                </form>
                                   </div>
                                </div>
                                </div>
                        </div>
                        @endforeach
                        </div>
                </div>

    @push('js')
    {{-- <script>alert() </script> --}}
    @endpush
</x-main-layout>
