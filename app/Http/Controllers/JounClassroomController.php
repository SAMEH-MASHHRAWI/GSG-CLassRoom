<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Scopes\UserClassroomScope;

class JounClassroomController extends Controller
{
    public function create($id)
    {

        $classroom = Classroom::withoutGlobalScope(UserClassroomScope::class)
            ->active()
            ->findOrFail($id);

        try {
            $this->exists($classroom, Auth::id());
        } catch (Exception $e) {
            return redirect()->route('classrooms.show', $id);
        }


        return view('classrooms.join', compact('classroom'));
    }


    public function store(Request $request, $id)
    {

        $request->validate([
            'role' => 'in:studant,teacher'
        ]);
        $classroom = Classroom::withoutGlobalScope(UserClassroomScope::class)
            ->active()
            ->findOrFail($id);

        try {
            $classroom->join(Auth::id(), $request->input('role', 'studant'));
        } catch (Exception $e) {
            return redirect()->route('classrooms.show', $id);
        }

        return redirect()->route('classrooms.show', $id);
    }
    protected function exists(Classroom $classroom, $user_id)
    {
        $exists = $classroom->users()->where('id','=',$user_id)->exists();

        // belowe the same thing at up but belowe by querybilduer
            // $exists = DB::table('classroom_user')
            // ->where('classroom_id', $classroom_id)
            // ->where('user_id', Auth::id())
            // ->exists();
        if ($exists) {
            throw new Exception('User aleready joined the classroom');
        }
    }
}
