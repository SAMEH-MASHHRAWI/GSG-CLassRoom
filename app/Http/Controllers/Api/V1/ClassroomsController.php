<?php

namespace App\Http\Controllers\Api\V1;

use Throwable;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassroomResource;
use Illuminate\Support\Facades\Response;

class ClassroomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            return (!Auth::guard('sanctum')->user()->tokenCan('classrooms.read')){
            abort(403);
             }
        $classrooms= Classroom::with('user:id,name','topics')
        ->withCount('studants as studants')
        ->get(3);

        return ClassroomResource::collection($classrooms);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => ['required'],
            ]);
        }catch(Throwable $e){
            return Response::json([
                'message'=>$e->getMessage(),
            ],422);
        }
        $classroom=Classroom::create($request->all());
        return Response::json([
            'code'=>100,
            'message'=>__('Classoom created .'),
            'classsroom'=> $classroom,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
            return (!Auth::guard('sanctum')->user()->tokenCan('classrooms.read')){
            abort(403);
             }
        $classroom->load('user')->loadCount('studants');
        return new ClassroomResource($classroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
            return (!Auth::guard('sanctum')->user()->tokenCan('classrooms.uodate')){
            abort(403);
             }
        $request->validate([
            'name'=>['sometimes', 'required',"unique:classrooms,name,$classroom->id"],
            'section'=>['sometimes', 'required'],
        ]);
        $classroom->update($request->all());
        return[
            'code'=>100,
            'message'=> __('Classoom Updated .'),
            'classsroom' => $classroom,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            return (!Auth::guard('sanctum')->user()->tokenCan('classrooms.delete')){
            abort(403,'You Cannt Delete this Classroom');
             }
        Classroom::destroy($id);
        return Response::json([],204);
    }
}
