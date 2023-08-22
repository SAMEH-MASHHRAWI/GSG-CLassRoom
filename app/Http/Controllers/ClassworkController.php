<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Classwork;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Database\QueryException;
use Illuminate\Database\Events\QueryExecuted;

class ClassworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected function create(Request $request, Classroom $classroom)
    {
        $resopnse=Gate::inspect('classworks.create', [$classroom]);
        if(!$resopnse->allowed()) {
            abort(403, $resopnse->message()  ?? '');
        }
                Gate::authorize('classworks.create', [$classroom]);
        // if (!Gate::allows('classworks.create', [$classroom])) {
        //     abort(403);
        // }
        $type = $this->getType($request);
        $classwork = new Classwork();
        return view('classworks.create', compact('classroom', 'classwork', 'type'));
    }

    public function index(Request $request, Classroom $classroom)
    {

        // $classworks = $classroom->classworks()
        $classworks=$classroom->classworks()
        ->with('topic')
        // ->filter($request->$query())
        ->orderBy('published_at')
        ->paginate(5);

        return view('classworks.index',[
            'classroom'=>$classroom,
            'classworks'=>$classworks,
        ]);
    }

    public function getType(Request $request){
        $type=request()->query('type');
        $allowed_types = [
            Classwork::TYPE_ASSIGNMENT , Classwork::TYPE_MATERIAL , Classwork::TYPE_QUESTION
        ];
        if (!in_array($type, $allowed_types)) {
            $type = Classwork::TYPE_ASSIGNMENT;
        }
        return $type;
    }
    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Classroom $classroom)
    {
        if(Gate::denies('classworks.create',[$classroom])){
            abort(403);
        }

        $type = $this->getType($request);

        $request->validate([
            'title'=>['required','string','max:255'],
            'description'=>['nullable','string'],
            'topic_id'=> ['nullable','int','exists:topics,id'],
            'options.grade' =>[Rule::requiredIf(fn() => $type =='assignment'
        ),'numeric','min:0'],
        ]);

        $request->merge([
            'user_id'=> Auth::id(),
            'type'  => $type->value,

        ]);

        try{
           DB::transaction(function () use( $classroom, $request){

            $classwork = $classroom->classworks()->create($request->all());

            $classwork->users()->attach($request->input('studants'));

        });
        }catch(QueryException $e){
            return back()->with('error',$e->getMessage());
        }


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
        Gate::authorize('classworks.view',[$classwork]);

        $submissions=Auth::user()
        ->submissions()
        ->where('classwork_id',$classwork->id)
        ->get();
        return View::make('classworks.show', compact('classroom', 'classwork', 'submissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request ,Classroom $classroom, Classwork $classwork)
    {
        $type = $classwork->type->value;

        $assigned= $classwork->users()->pluck('id')->toArray();
        return view('classworks.edit', compact('classwork' ,'classroom', 'type', 'assigned'));
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

