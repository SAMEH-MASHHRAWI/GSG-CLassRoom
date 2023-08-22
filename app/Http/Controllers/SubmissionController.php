<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use App\Models\Classwork;
use App\Models\Submission;
use App\Rules\ForbiddenFile;
use Illuminate\Http\Request;
use App\Models\ClassworkUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;

class SubmissionController extends Controller
{

    public function store(Request $request, Classwork $classwork)
    {
        Gate::authorize('submissions.create',[$classwork]);
        $request->validate([
            'files' => 'required',
            'files.*' => ['file', new ForbiddenFile('text/x-php', 'application/x-msdownload', 'application/x-httpd-php')]
        ]);
        // dd($request);
        $assigned = $classwork->users()->where('id', Auth::id())->exists();
        // dd($assigned);
        if (!$assigned) {
            abort(403);
        }
        DB::beginTransaction();
        try {
            $data = [];
            foreach ($request->file('files') as $file) {
                $data[] = [
                    'user_id' => Auth::id(),
                    'classwork_id' => $classwork->id,
                    'content' => $file->store("submissions/{$classwork->id}"),
                    'type' => 'file',
                    'created_at' => now(),
                    'updated_at' => now()

                ];
            }
            $user = Auth::user();
            $user->submissions()->createMany($data);

            ClassworkUser::where([
                'user_id' => $user->id,
                'classwork' => $classwork->id,
            ])->update([
                'status' => 'submited',
                'submited_at' => now(),
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Work submited');
    }

    public function file(Submission $submission)
    {
        $user = Auth::user();
        $collection=DB::select('SELECT * FORM classroom_user
        WHERE user_id=?
        AND role=?
        AND EXISTS(
            SELECT 1 FORM classworks WHERE classworks.classroom_id =classroom_user.classroom_id
                AND EXISTS(
                        SELECT 1 form sumbissions where submission.classwork_id = classworks.id AND id=?
                )
        )',[$user->id,'teacher',$submission->id]);
          dd($collection);

        $isTeacher = $submission
        ->classwork
        ->classroom
        ->teachers()->where('id',$user->id)->exists();

        $isOwner=$submission->user_id == $user->id;

        if(!$isTeacher && !$isOwner){
            abort(403);
        }


        return response()->file(storage_path('app/' . $submission->content));
    }
}
