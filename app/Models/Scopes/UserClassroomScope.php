<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class UserClassroomScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {

        if($id = Auth::id()){
            $builder
            ->where(function(Builder $query) use($id){
                $query->where('user_id', '=', $id)
                ->orWhereExists(function($query) use($id){
                   $query->select(DB::raw('1'))
                    ->from('classroom_user')
                    ->whereColumn('classroom_id','=','classrooms.id')
                    ->where('user_id',$id);
                });
            });

        // // ->orWhereRow('exists (select 1 form classroom_user where classroom_id=classrooms,id and user_id = ?)',[
        // //     $id
        // ]);
        }
    }
}
