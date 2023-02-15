<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'course_category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_courses', 'course_id', 'users_id');
    }
}
