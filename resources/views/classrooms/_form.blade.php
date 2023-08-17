<x-alert name="error" id="error" class="alert-danger" />
          <x-form.floating-control name="name">
                <x-slot:label>
                    <label for="name">ClassRoom Name</label>
                </x-slot:label>
                <x-form.input  name="name" value="{{$classroom->name}}" placeholder="ClassRoom Name"/>
            </x-form.floating-control>

           <div class="form-floating mb-3" name="section">
                <x-form.input  name="section" value="{{$classroom->section}}" placeholder="ClassRoom section"/>
                    <label for="section">ClassRoom section</label>

            <x-form.error name="sction" />
         </div>

         <div class="form-floating mb-3">
                <x-form.input  name="subject" value="{{$classroom->subject}}" placeholder="ClassRoom subject"/>
                <label for="subject">subject</label>
            <x-form.error name="subject" />
         </div>
            <div class="form-floating mb-3">
                <x-form.input  name="room" value="{{$classroom->room}}" placeholder="ClassRoom Room"/>
                <label for="room">Room</label>
            <x-form.error name="room" />

            </div>

            <div class="form-floating mb-3 mb-3">
                    <img src="{{ Storage::disk('public')->url($classroom->cover_image_path)}}" alt="">
                <x-form.input type="file"  name="cover_image" value="{{$classroom->cover_image}}" placeholder="ClassRoom Cover_image"/>
            <x-form.error name="cover_image"/>

                <label for="cover_image">Cover_image</label>
                     @error('cover_image')
                        <div class="text-danger">{{$message}}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">{{$button_lable}}</button>
