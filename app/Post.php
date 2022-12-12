<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class Post extends Model
{
    use Sluggable;

    protected $fillable = ['title', 'description', 'content', 'category_id', 'thumbnail', 'category_id'];

    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function Category() {
        return $this->belongsTo(Category::class);
    }

    public function sluggable(){
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function uploadImage(Request $request, $image = null){
        if($request->hasFile('thumbnail')) {

            if($image) {
                Storage::delete($image);
            }

            $folder = date('Y-m-d');
            return $request->file('thumbnail')->store("images/{$folder}");
        }

        return null;
    }

    public function getImage() {
        if(!$this->thumbnail){
            return asset("No_image_available.svg.png");
        }
        return asset("uploads/{$this->thumbnail}");
    }

    public function getPostDate() {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d F, Y');
    }
}
