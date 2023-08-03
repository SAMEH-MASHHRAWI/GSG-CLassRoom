<?php

namespace App\Http\Controllers;

use App\Models\Classwork;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Classroom $classroom)
    {
        $classworks=$classroom->classworks()
        ->orderBy('published_at')
        ->get();

        return view('classrooms.index',[
            'classroom'=>$classroom,
            'classworks'=>$classworks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Classroom $classroom)
    {
        return view('classrooms.create',compact('classroom'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'title'=>['required','string','max:255'],
            'description'=>['nullable','string'],
            'topic_id'=> ['nullable','int','exists:topics,id']
        ]);
        $request->mearge([
            'user_id'=>Auth::id(),
            // 'classrom'=>$classroom->id,
        ]);

        $classroom=$classroom()->classworks()->create()($request->all());
        return redirect()->route('classrooms.index',$classroom->id);
        // Classroom::create($request->all() );
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom,Classwork $classwork,)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom, Classwork $classwork)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom, Classwork $classwork)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classwork $classwork)
    {
        //
    }
}
