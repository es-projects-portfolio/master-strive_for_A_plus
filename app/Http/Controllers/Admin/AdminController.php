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
     * List all users, courses, and sections.
     * Adds a quick note on displaying admin data.
     * Note: Fetches all items for the admin view.
     */
    public function index()
    {
        $users = User::all();
        $courses = Course::all();
        $sections = Section::all();

        return view('admin.index', compact('users', 'courses', 'sections'));
    }

    /**
     * Create a new course.
     * Adds a short summary about course creation.
     * Note: Validates 'course_name' and stores a new course record.
     */
    public function createCourse(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
        ]);

        $course = Course::create([
            'course_name' => $request->course_name,
        ]);

        return redirect()->back()->with('success_course', 'Course created successfully!');
    }

    /**
     * Create a new section for a course.
     * Explains how section is validated and persisted.
     * Note: Validates the form, checks tutor role, then creates the section.
     */
    public function createSection(Request $request)
    {
        $request->validate([
            'section_number' => 'required|integer',
            'course_id' => 'required|exists:courses,id',
            'tutor_id' => 'required|exists:users,id',
        ]);

        $tutor = User::where('id', $request->tutor_id)->where('role', 'tutor')->firstOrFail();

        $section = Section::create([
            'section_number' => $request->section_number,
            'course_id' => $request->course_id,
            'tutor_id' => $tutor->id,
        ]);

        return redirect()->back()->with('success_section', 'Section created successfully!');
    }

    /**
     * Assign students to a section.
     * Adds a pointer to how students are linked to sections.
     * Note: Validates the section and student array, then attaches them.
     */
    public function assignStudentsToSection(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'student_id' => 'required|array',
            'student_id.*' => 'exists:users,id',
        ]);

        $section = Section::findOrFail($request->section_id);

        foreach ($request->student_id as $student_id) {
            $student = User::where('id', $student_id)->where('role', 'student')->firstOrFail();
            StudentInSection::create([
                'section_id' => $section->id,
                'student_id' => $student->id,
            ]);
        }

        return redirect()->back()->with('success_assign', 'Students assigned successfully!');
    }

    
}
