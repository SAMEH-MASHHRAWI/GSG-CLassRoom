@props([
    'value'=>'','name'
])
@php
    $old_name=str_replace('[' , '.' ,'$old_name');
    $old_name=str_replace(']' , '.' ,'$old_name');
@endphp
<input
    value="{{ old($old_name , $value)}}"
    name="{{$name}}"
    id="{{$id ?? $name}}"
    {{$attributes->merge([
        'type'=>'text'
    ])
    ->class(['form-control','is-invalid'=>$errors->has($old_name)])}}
>

