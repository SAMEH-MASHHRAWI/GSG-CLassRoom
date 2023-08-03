<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class classroom extends Model
{
    use HasFactory;

    public static string $disk='public';

    protected $fillable=[
        'name','section','subject','code','room','theme','cover_image_path'
    ];
    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function uploadeCoverImage ($file)
    {
        $path = $file->storeAs('/covers', [
            'disk' => static::$disk
        ]);
        return $path;
    }
    public static  function deleteCoverImage($path)
    {
       return Storage::disk(Classroom::$disk)->delete($path);

    }
}
