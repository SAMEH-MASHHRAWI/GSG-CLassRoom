<x-main-layout title="{{__('Classrooms')}}" class="">

    <div class="container">
                    <h1>{{__('Classrooms')}}</h1>
                        <x-alert  name="success" id="success" class="alert-success"/>
                        <x-alert  name="error" id="danger" class="alert-danger"/>
                        <ul id="classrooms"></ul>
                <div class="row">
                    @foreach ($classrooms as $classroom)
                        <div class="col-md-3">
                            <div class="card" style="width: 18rem;">
                                <img src="storage/{{$classroom->cover_image_path}}" class="card-img-top" alt="image">
                                <div class="card-body">
                                    <h5 class="card-title">{{$classroom->name}} </h5>
                                    <p class="card-text">{{$classroom->section}}</p>
                                <div class="d-flex justify-content-between" >

                                    <a href="{{$classroom->url }} " class="btn btn-primary">{{__('View')}}</a>
                                    <a href="{{route('classrooms.edit',$classroom->id)}} " class="btn btn-sm btn-dark ">{{__('Edit')}}</a>
                                    <form action="{{route('classrooms.destroy',$classroom->id)}}" method="post">
                                    @csrf
                                        @method( 'delete')
                                        <button  class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                                </div>
                                </form>
                                </div>
                                </div>
                        </div>
                        @endforeach
                        </div>
                </div>

    @push('scripts')
    <script>
        fetch('api/v1/classrooms')
        .then(res=>res.json())
        .then(json=>{
            let ul=document.getElementById('classrooms');
            for(let i in json){
                ul.innerHTML += `<li>${json[i].name}ؤ</li>`
            }
        })
    </script>
    @endpush
</x-main-layout>
