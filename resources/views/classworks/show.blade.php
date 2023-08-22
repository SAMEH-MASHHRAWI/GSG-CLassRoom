<x-main-layout title="Create Classwork">
<div class="container">
                <h1>Classroom Details</h1>
                    <h2>{{$classroom->name}} (#{{$classroom->id}})</h2>
                    <h3>{{$classwork->title}} </h3>
                    <x-alert name="success" class="alert-success" />
                    <x-alert name="error" class="alert-danger" />
                    <hr>
        <div class="row">
            <div class="col-md-8">
                        <div>
                            <p>{{$classwork->description}}</p>
                        </div>
                        <h4>Comments</h4>
                    <form action="{{route('comments.store')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$classwork->id}}">
                    <input type="hidden" name="type" value="classwork">
                    <div class="d-flex">
                        <div class="col-8">
                            <x-form.floating-control name="description">
                            <x-slot:label>
                              <label for="description">Comment</label>
                            </x-slot:label>
                                <br><br>
                            <x-form.texteara  name="content" placeholder="Comment"/>
                        </x-form.floating-control>
                        </div>
                        <div class="ms-1">
                            <button type="submit" class="btn btn-primary">Comment</button>
                        </div>
                    </div>
                </form>
                <div class="mt-4">
                    @foreach ($classwork->comments as $comment)
                        <div class="row">
                        <div class="col-md-1 media-body">
                                <img src="https://ui-avatars.com/api/?name={{ $comment->user->name }}&size=70&background=5EBEF5&color=fff"
                                class="mr-3x` WqfsMd" alt="User Avatar">
                            </div>
                            <div class="col-md-10">
                                <p style="color:blue;">By: {{ $comment->user?->name }}</p>
                                <p style="font-weight: 500">Time:{{ $comment->created_at->diffForHumans(null, true, true)}}</p>
                                <p>{{ $comment->content }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
             </div>
             <div class="col-md-4">
                @can('submission.create', [$classwork])
                <div class="borderd rounded p-3 bg-ligh">
                    <h4>submission</h4>
                    @if ($submissions->count())
                    <ul>
                        @foreach ($submissions as $submission)
                        <li><a href="{{route('submissions.file  ',$submission->id)}}">File #{{$loop->iteration}}</a> </li>
                        @endforeach
                    </ul>
                    @else
                    <form action="{{route('submissions.store',$classwork->id)}}" enctype="multipart/form-data" method="post">
                        @csrf
                           <x-form.floating-control name="files">
                            <x-slot:label>
                              <label for="files">Upload File</label>
                            </x-slot:label>
                                <br><br>
                            <x-form.input  name="files[0]" type="file" muiltipla placeholder="Select Files"/>
                        </x-form.floating-control>
                        <button class="btn btn-primary">Submit</button>
                    </form>
                    @endif
                </div>
                @endcan
             </div>
        </div>
 </div>
</x-main-layout>
