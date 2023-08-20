    <x-main-layout :title="$classroom->name">

    <div class="container">
                <h1>Classroom Details</h1>
                    <h2>{{$classroom->name}} (#{{$classroom->id}})</h2>
                    <h3>Classworks</h3>

                    <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                + Create
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('classrooms.classworks.create' , [$classroom->id, 'type'=>'assignment'])}}">Assignment</a></li>
                                <li><a class="dropdown-item" href="{{route('classrooms.classworks.create' , [$classroom->id, 'type'=>'matirial'])}}">Matirial</a></li>
                                <li><a class="dropdown-item" href="{{route('classrooms.classworks.create' , [$classroom->id, 'type'=>'question'])}}">Question</a></li>
                            </ul>
                    </div>

                    <form action="{{URL::current()}}"method="get" class="row row-cols-lg-auto g-3 align-items-center">
                        <div class="co-12">
                            <input type="text" name="search" class="form-control">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary m-2" type="submit">find</button>
                        </div>
                    </form>



                    <div class="accordion accordion-flush" id="accordionFlushExample">
                    @foreach ($classworks as $classwork)

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                              <button class="accordion-button collapsed" type="button"
                                 data-bs-toggle="collapse"
                                data-bs-target="#flush-collapse{{ $classwork->id}}" aria-expanded="false"
                                aria-controls="flush-collapseThree">
                                    {{ $classwork->title }}
                              </button>
                            </h2>
                            <div id="flush-collapse{{ $classwork->id}}" class="accordion-collapse collapse"
                                     data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    {{ $classwork->description }}
                                    <div>   

                                        <a class="btn btn-sm btn-outline-dark" href="{{ route('classrooms.classworks.edit', [$classwork->id , $classwork->id])}}">
                                                Edit</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endforeach

                    </div>

                </div>
    {{ $classworks->withQueryString()->links() }}

</x-main-layout>
