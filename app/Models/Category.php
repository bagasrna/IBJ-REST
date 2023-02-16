<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use Carbon\Carbon;

class Category extends Model
{
    use HasFactory;

    protected $table = 'course_categories';

    protected $guarded = [];

    public function courses()
    {
        return $this->hasMany(Course::class, 'course_category_id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i');
    }
}
