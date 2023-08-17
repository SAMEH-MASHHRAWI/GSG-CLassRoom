<?php

namespace App\Models;

use Exception;
use App\Models\Topic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Observers\ClassroomObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Scopes\UserClassroomScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

        // static::creating(function(Classroom $classroom){
        //     $classroom->code=Str::random(8);
        //     $classroom->user_id=Auth::id();
        // });

        // static::forceDeleted(function (Classroom $classroom){
        //     static::deleteCoverImage($classroom->cover_image_path);
        // });

        // static::deleted(function (Classroom $classroom){
        //     $classroom->status='deleted';
        //     $classroom->save();

        // });

        // static::restored(function (Classroom $classroom) {
        //     $classroom->status = 'active';
        //     $classroom->save();
        // });
    }

    public function Classroom(): HasMany
    {
        return $this->hasMany(Classroom::class, 'classroom_id', 'id');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'topic_id', 'id');
    }
    public function users()
    {
        return  $this->belongsToMany(
            User::class,
            'classroom_user',
            'classroom_id',
            'user_id',
            'id',
            'id',
        )->withPivot(['role','created_at']);
    }
    public function teachers(){
        return $this->users()->wherePivot('role','=','teacher');
    }
    public function studants ()
    {
        return $this->users()->wherePivot('role', '=', 'studant');
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function uploadeCoverImage($file)
    {
        $path = $file->store('/covers', [
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
    public function classworks(): HasMany
    {
        return $this->hasMany(Classwork::class,'classroom_id','id');
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
        $exists = $this->users()->where('id', '=', $user_id)->exists();
        if ($exists) {
            throw new Exception('User aleready joined the classroom');
        }

        return $this->users()->attach([$user_id],[
            'role'=>$role,
            'created_at'=>now()
        ]);

// نفس الي فوقها بس الي تحت كويري بلدر
        // return DB::table('classroom_user')->insert([
        //     'classroom_id'=>$this->id,
        //     'user_id'=>$user_id,
        //     'role'=>$role,
        //     'created_at'=>now()
        // ]);
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
