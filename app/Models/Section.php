<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['section_number', 'course_id', 'tutor_id'];

    /**
     * Get the course that owns the section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the materials for the section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Get the tutor that owns the section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tutor()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the students in the section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentsInSection()
    {
        return $this->hasMany(StudentInSection::class);
    }
}
