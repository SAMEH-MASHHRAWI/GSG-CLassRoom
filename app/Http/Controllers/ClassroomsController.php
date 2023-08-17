<?php

namespace App\Http\Controllers;

use PDO;


use Exception;
use App\Models\Classroom;
use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\FlareClient\View;
use PhpParser\Builder\Class_;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\ClassroomSeeder;
use Illuminate\View\View as BiscView;
use App\Http\Requests\ClassroomRequest;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Support\Facades\View as FacadesView;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ClassroomsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): Renderable
    {
        $classrooms = Classroom::active()
        ->recent()
        ->orderBy('created_at','DESC')
        ->withoutGlobalScope(UserClassroomScope::class)
        ->get(); //return collectionpf classsroom

        $success = session('success');
        return view('classrooms.index', compact('classrooms', 'success'));
    }


    public function create()
    {
        return view()->make('classrooms.create', [
            // 'name' => 'Classroom',
            'classroom' => new Classroom(),
        ]);
    }

    public function store(ClassroomRequest $request): RedirectResponse
    {

        $validated = $request->validated();



        if ($request->hasfile('cover_image')) {
            $file = $request->file('cover_image');
            $path = Classroom::uploadeCoverImage($file);
            $validated['cover_image_path'] = $path;
        }
            DB::beginTransaction();
            try{
            $classroom = Classroom::create($validated);
            $classroom->join(Auth::id(), 'teacher');

            DB::commit();
            }catch(QueryException $e){
                DB::rollBack();
                return back()
                ->with('error',$e->getMessage())
                ->withInput();
            }

        //PRG POST Redirect  Get
        return redirect(route('classrooms.index'))
            ->with('success', 'Classroom Created');
    }


    public function show(Classroom $classroom)
    {

        // $classroom = Classroom::withTrashed()->findOrFail($id);
        $initation_link=URL::signedRoute('classrooms.join', [
                'classroom'=> $classroom->id,
                'code'=> $classroom->code,
        ]);
        return view('classrooms.show')
            ->with([
                'classroom' => $classroom,
                'initation_link'=>$initation_link,
            ]);
    }


    public function edit(Classroom $classroom)
    {
        // $classroom=Classroom::find($classroom);
        return view('classrooms.edit', [
            'classroom' => $classroom,
        ]);
    }
    public function update(ClassroomRequest $request, Classroom $classroom)
    {

        $validated = $request->validated();

        if ($request->hasfile('cover_image')) {
            $file = $request->file('cover_image');
            $path = Classroom::uploadeCoverImage($file);
            $validated['cover_image_path'] = $path;
        }
        $old = $classroom->cover_image_path;
        $classroom->update($validated);

        if ($old && $old != $classroom->cover_image_path) {
            Classroom::deleteCoverImage($old);
        }

        Session::flash('success', 'Classroom Updated!');
        return Redirect::route('classrooms.index');
        // ->with('success', 'Classroom Updated');

    }

    public function destroy(Classroom $classroom)
    {
        // $count = Classroom::destroy($id);
        // $classroom=Classroom::find($id);
        $classroom->delete();
        // Classroom::deleteCoverImage($classroom->cover_omage_path);

        return redirect(route('classrooms.index'))
            ->with('success', 'Classroom Deleted');
    }
    public function trashed()
    {
        $classrooms = Classroom::onlyTrashed()
        ->latest('deleted_at')
        ->get();
        return view('classrooms.trashed', compact('classrooms'));
    }


    public function restore($id)
    {
        $classroom = Classroom::onlyTrashed()->findOrFail($id);
        $classroom->restore();

        return redirect()
        ->route('classrooms.index')
        ->with('success', "Classroom ({$classroom->name}) Restore");
    }


    public function forceDelete($id)
    {
        $classroom = Classroom::withTrashed()->findOrFail($id);
        $classroom->forceDelete();
        Classroom::deleteCoverImage($classroom->cover_image_path);

            return view('classrooms.index')
            ->with('success', "Classroom ({$classroom->name}) deleted foever");
    }
}
