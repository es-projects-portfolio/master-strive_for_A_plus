<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['section_number', 'course_id', 'tutor_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class);
    }

    public function studentsInSection()
    {
        return $this->hasMany(StudentInSection::class);
    }
}
