<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Scopes\UserClassroomScope;
use App\Observers\ClassroomObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{

    use HasFactory,SoftDeletes;

    public static string $disk = 'public';

    protected $fillable = [
        'name',
        'section',
        'subject',
        'code',
        'room',
        'theme',
        'cover_image_path',
        'user_id'
    ];
    protected static function booted()
    {
        static::observe(ClassroomObserver::class);

        static::addGlobalScope(new UserClassroomScope);

    }

    public function Classroom(): HasMany
    {
        return $this->hasMany(Classroom::class, 'classroom_id', 'id');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'topic_id', 'id');
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function uploadeCoverImage($file)
    {
        $path = $file->store('/storge', [
            'disk' => static::$disk
        ]);
        return $path;
    }
    public static  function deleteCoverImage($path)
    {
        if(!$path||Storage::disk(Classroom::$disk)->exists($path)){
            return;
        }
        return Storage::disk(Classroom::$disk)->delete($path);
    }

    public function scopeActive(Builder $query){
        $query->where('status','=','active');
    }

    public function scopeRecent(Builder $query)
    {
        $query->orderBy('updated_at', 'DESC');
    }
    public function scopStatus(Builder $query ,$status='active')
    {
        $query->where('status','=',$status);
    }
    public function join($user_id,$role='studant')
    {
        return DB::table('classroom_user')->insert([
            'classroom_id'=>$this->id,
            'user_id'=>$user_id,
            'role'=>$role,
            'created_at'=>now()
        ]);
    }
    public function getnameAttribute($value){
        return strtoupper($value);
    }

    public function getCoverImageUrlAttribute($value)
    {
        if ($this->cover_image_path) {
            return;
        }
        return 'https://placeholder.co/800x300';
    }
    public function getUrlAttribute()
    {
        return route('classrooms.show', $this->id);
    }


}
