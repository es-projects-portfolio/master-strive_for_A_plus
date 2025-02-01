<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentInSection extends Model
{
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id')->where('role', 'student');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
