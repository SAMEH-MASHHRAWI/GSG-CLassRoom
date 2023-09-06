
 <x-alert name="error" type="danger" />
 @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $message)
            <li>{{$message}} </li>
            @endforeach
        </ul>
    </div>

 @endif
 <div class="row">
                            <div class="col-md-8">
                                <div class="form-floating mb-3" value="{{$classwork->title}}" name="title">
                                <label for="title">Title</label>
                                <br><br>
                            <x-form.input  name="title" :value="$classwork->sction"  placeholder="title"/>
                        <x-form.error name="sction" />
                        </div>
                        <div class="form-floating mb-3" :value="$classwork->description" name="description">
                                <label for="description">Description</label>
                                <br><br>
                            <x-form.texteara id="#description" name="description" value="" placeholder="description"/>
                        <x-form.error name="description" />
                        </div>
                    </div>

                    <div class="col-md-4">
                          <x-form.floating-control name="published_at">
                                    <x-slot:label>
                                        <label for="published_at">Publish Date</label>
                                    </x-slot:label>
                                    <x-form.input name="published_at" :value="$classwork->published_date " type="date"/>
                            </x-form.floating-control>
                            <div class="mb-3">
                            @foreach ($classroom->studants as $studant )
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="studants[]" value="{{$studant->id}}" @checked(!isset($assigned) || in_array($studant->id,$assigned))
                                    id="std-{{$studant->id}}  ">
                                    <label class="form-check-label" for="std-{{$studant->id}}">
                                        {{$studant->name}}
                                    </label>
                                </div>
                            @endforeach
                            </div>

                        @if ($type == 'assignment')
                            <x-form.floating-control name="options.grade">
                                    <x-slot:label>
                                        <label for="grade">Grade</label>
                                    </x-slot:label>
                                    <x-form.input name="options[grade]" :value="$classwork->options['grade'] ?? '' " type="number" min="0" />
                            </x-form.floating-control>

                            <x-form.floating-control name="due">
                                    <x-slot:label>
                                        <label for="due">due</label>
                                    </x-slot:label>
                                    <x-form.input name="options[due]" :value="$classwork->options['due'] ?? '' " type="date"/>
                            </x-form.floating-control>
                        @endif

                        <x-form.floating-control name="topic_id">
                            <x-slot:label>
                                <label for="topic_id">Topic(Optional)</label>
                            </x-slot:label>
                        <select name="topic_id" id="topic_id" class="form-select">
                            <option value="">No Topic</option>
                                @foreach ($classroom->topics as $topic)
                                <option @selected($topic->id == $classwork->topic_id) value="{{$topic->id}}">{{$topic->name}}</option>
                                @endforeach
                            </select>
                        </x-form.floating-control>
                    </div>
                </div>
            </div>

         @push('script')
         <head>
            <script>
        tinymce.init({
            selector: '#description',
            plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
                "See docs to implement AI Assistant"))
        });
    </script>
        @endpush
