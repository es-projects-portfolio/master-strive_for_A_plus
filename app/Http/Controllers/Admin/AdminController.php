<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use App\Models\StudentInSection;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new course.
     */
    public function createCourse(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
        ]);

        $course = Course::create([
            'course_name' => $request->course_name,
        ]);

        return response()->json($course, 201);
    }

    /**
     * Create a new section for a course.
     */
    public function createSection(Request $request)
    {
        $request->validate([
            'section_number' => 'required|integer',
            'course_id' => 'required|exists:courses,course_id',
            'tutor_id' => 'required|exists:users,user_id',
        ]);

        $tutor = User::where('user_id', $request->tutor_id)->where('role', 'tutor')->firstOrFail();

        $section = Section::create([
            'section_number' => $request->section_number,
            'course_id' => $request->course_id,
            'tutor_id' => $tutor->user_id,
        ]);

        return response()->json($section, 201);
    }

    /**
     * Assign students to a section.
     */
    public function assignStudentsToSection(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,section_id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,user_id',
        ]);

        $section = Section::findOrFail($request->section_id);

        foreach ($request->student_ids as $student_id) {
            $student = User::where('user_id', $student_id)->where('role', 'student')->firstOrFail();
            StudentInSection::create([
                'section_id' => $section->section_id,
                'student_id' => $student->user_id,
            ]);
        }

        return response()->json(['message' => 'Students assigned successfully'], 200);
    }
}
