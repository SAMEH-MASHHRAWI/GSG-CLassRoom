<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Classwork;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Events\ClassworkCreated;
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

    public function index(Request $request, Classroom $classroom)
    {
        // $this->authorize('view-Any', [Classwork::class, $classroom]);
        $classworks = $classroom->classworks()
            ->with('topic')
            ->filter($request->query())
            ->latest('published_at')
            ->where(function ($query) {
                $query->wherehas('users', function ($query) {
                    $query->where('id', '=', Auth::id());
                })
                    ->orwherehas('classroom.teachers', function ($query) {
                        $query->where('id', '=', Auth::id());
                    });
            })
            // ->where(function ($query){
            // $query->whereRow('EXISTS (SELECT 1 FROM classwork_user
            // WHERE classwork_user.classwork_id=classwork_id
            // AND classwork_user.user_id =?
            // )'[Auth::id()]);

            // $query->orWhereRow('EXISTS(SELECT 1 FORM classroom_user
            // WHERE classroom_user.classroom_id=classwork.classroom_id
            // AND classroom_user.user_id=?
            // AND classroom_user.role )',[Auth::id(),'teacher']);
            // })
            ->paginate(5);


        return view('classworks.index', [
            'classroom' => $classroom,
            'classworks' => $classworks,
        ]);
    }

    protected function create(Request $request, Classroom $classroom, Classwork $classwork)
    {
        // $resopnse=Gate::inspect('classworks.create', [$classroom]);
        // if(!$resopnse->allowed()) {
        //     abort(403, $resopnse->message()  ?? '');
        // }
        // Gate::authorize('classworks.create', [$classroom]);
        // if (!Gate::allows('classworks.create', [$classroom])) {
        //     abort(403);
        // }
        // dd('auth::user', $classroom);
        // $this->authorize('create', [Classwork::class, $classroom]);

        $type = $this->getType($request)->value;
        $classwork = new Classwork();
        return view('classworks.create', compact('classroom', 'classwork', 'type'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        // if (Gate::denies('classworks.create', [$classroom])) {
        //     abort(403);
        // }
        // $this->authorize('create', [Classwork::class, $classroom]);
        $type = $this->getType($request);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable', 'int', 'exists:topics,id'],
            'options.grade' => [Rule::requiredIf(
                fn () => $type == 'assignment'
            ), 'numeric', 'min:0'],
        ]);

        $request->merge([
            'user_id' => Auth::id(),
            'type'  => $type->value,

        ]);

        try {

            DB::transaction(function () use ($classroom, $request) {

                $classwork = $classroom->classworks()->create($request->all());

                $classwork->users()->attach($request->input('studants'));
                event(new ClassworkCreated($classwork));
                // ClassworkCreated::dispatch($classwork);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }


        return redirect()->route('classrooms.classworks.index', $classroom->id)
            ->with('success', __('Classwork Created!'));
        // Classroom::create($request->all() );
    }

    public function getType(Request $request)
    {
        try{
            return $type = Classwork::form(request()->query('type'));
            //    dd(!in_array((int)$type,$allowed_types));
        }catch(\Exception $e){
            return Classwork::TYPE_ASSIGNMENT;
        }
    }

    public function show(Classroom $classroom, classwork $classwork)
    {
        // $this->authorize('view',$classwork);
        // Gate::authorize('classworks.view',[$classwork]);

        $submissions = Auth::user()
            ->submissions()
            //            ->whereHasMorph('submissionable','App\Models\Submission')
            ->where('classwork_id', $classwork->id)
            ->get();
        return View::make('classworks.show', compact('classroom', 'classwork', 'submissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Classroom $classroom, Classwork $classwork)
    {
        $type = $classwork->type->value;

        $assigned = $classwork->users()->pluck('id')->toArray();
        return view('classworks.edit', compact('classwork', 'classroom', 'type', 'assigned'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom, Classwork $classwork)
    {
        $type = $classwork->type;

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
            ->with('success', __('Classwork Updated!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classwork $classwork)
    {
        //
    }
}
