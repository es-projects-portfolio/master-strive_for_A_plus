<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentInSection extends Model
{
    protected $fillable = ['student_id', 'section_id'];

    /**
     * Get the student associated with the section.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id')->where('role', 'student');
    }

    /**
     * Get the section associated with the student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
