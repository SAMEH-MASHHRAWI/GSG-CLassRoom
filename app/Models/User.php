<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Profil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Translation\HasLocalePreference;

class User extends Authenticatable implements MustVerifyEmail , HasLocalePreference
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    // public function classrooms()
    // {
    //     return $this->belongsToMany(
    //         Classroom::class
    //         ,'user_classroom'
    //         ,'user_id',
    //         'classroom_id'
    //     )->withPivot(['role','created_at']);

    // }
    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper($value),
            set: fn ($value) => strtolower($value),
        );
    }

    public function classrooms()
    {
        return  $this->belongsToMany(
            Classroom::class,
            'classroom_user',
            'classroom_id',
            'user_id',
            'id',
            'id',
        )->withPivot(['role', 'created_at']);
    }
    public function createdclassroom()
    {
        return $this->hasMany(Classroom::class, 'user_id');
    }

    // public function classworks()
    // {
    //     return $this->belongsToMany(Classwork::class)
    //     ->using(CLassworkUser::class)
    //     ->withPivot(['grade','status','submitted_at','created_at']);
    // }
    public function classworks()
    {
        return $this->belongsToMany(classwork::class)
            ->using(ClassworkUser::class)
            ->withPivot(['grade', 'status', 'submitted_at', 'created_at']);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
    public function Profil()
    {
        return $this->hasOne(Profil::class, 'user_id', 'id')
            ->withDefault();
    }

    public function routeNotificationForMail($notification=null){
        return $this->email_address;
    }
    public function routeNotificationForVonage($notification = null)
    {
        return '+970592421537';
    }
    public function routeNotificationForHadara($notification = null)
    {
        return '+970592421537';
    }
    public function receivesBroadcastNotificationsOn()
    {
        return 'Notifications.' .$this->id;
    }
    public function preferredLocale()
    {
        return $this->profil->locale;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
