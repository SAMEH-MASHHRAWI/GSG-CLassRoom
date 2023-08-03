<x-main-layout title="Join Classroom" class="">

    <div class="d-flex align-items-center justify-content-center vh100">
     <div class="border p-5">
          <h2>{{$classroom->name}} </h2>

        <form class="border p-5" action="{{route('classrooms.join',$classroom->id)}}" method="post">
            @csrf

            <button type="submit" class="btn btn-primary">{{__('join')}} </button>
        </form>

    </div>

</x-main-layout>


