<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Classwork;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Database\QueryException;
use Illuminate\Database\Events\QueryExecuted;

class ClassworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Classroom $classroom)
    {

        $classworks = $classroom->classworks()
        ->with('topic')
        ->orderBy('published_at')
        ->get();

        return view('classworks.index',[
            'classroom'=>$classroom,
            'classworks'=>$classworks->groupBy('topic_id'),
        ]);
    }

    public function gettype(){
        $type=request()->query('type');
        $allowed_types = [
            Classwork::TYPE_ASSIGNMENT,
             Classwork::TYPE_MATERIAL,
             Classwork::TYPE_QUESTION
        ];
        if (!in_array($type, $allowed_types)) {
            $type = Classwork::TYPE_ASSIGNMENT;
        }
        return $type;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Classroom $classroom)
    {
        $type=$this->getType($request);

        $classwork=new Classwork();

        return view('classworks.create',compact('classroom','classwork','type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Classroom $classroom)
    {
        $type = $this->getType($request);

        $request->validate([
            'title'=>['required','string','max:255'],
            'description'=>['nullable','string'],
            'topic_id'=> ['nullable','int','exists:topics,id'],
            'options.grade' =>[Rule::requiredIf(fn() => $type =='assignment'
        ),'numeric','min:0'],
        ]);
        $request->merge([
            'user_id'=>Auth::id(),
            'type'=>$type,
            // 'classrom'=>$classroom->id,
        ]);

        try{
           DB::transaction(function () use( $classroom, $request){

            $classwork = $classroom->classworks()->create($request->all());

            $classwork->users()->attach($request->input('studants'));

        });
        }catch(QueryException $e){
            return back()->with('error',$e->getMessage());
        }

// dd('classworks');
        return redirect()->route('classrooms.index',$classroom->id)
        ->with('success','Classwork Created!');
        // Classroom::create($request->all() );
    }

    /**
     * Display the specified resource.
     */
    // public function show(Classroom $classroom,Classwork $classwork)
    // {
    //     $classwork->load('comments.user');
    //     return view('classworks.show',compact('classroom','classwork'));
    // }
    public function show(Classroom $classroom, classwork $classwork)
    {
        return View::make('classworks.show', compact('classroom', 'classwork'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request ,Classroom $classroom, Classwork $classwork)
    {
        $type = $classwork->type->value;

        $assigned= $classwork->users()->pluck('id')->toArray();
        return view('classworks.edit', compact('assigned','classwork' ,'classroom', 'type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom, Classwork $classwork)
    {
        $type =$classwork->type;

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable', 'int', 'exists:topics,id'],
            'options.grade' => [Rule::requiredIf(
                fn () => $type == 'assignment'
            ), 'numeric', 'min:0'],
        ]);
        $classwork->update($request->all());
        $classwork->users()->sync($request->input('studants'));

        return back()
        ->with('success', 'Classwork Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classwork $classwork)
    {
        //
    }
}

