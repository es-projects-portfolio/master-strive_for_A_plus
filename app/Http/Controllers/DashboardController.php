<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalTutors = User::where('role', 'tutor')->where('is_admin', false)->count();
        $totalCourses = Course::count();
        $totalSections = Section::count();

        $courses = Course::with(['sections.tutor', 'sections.studentsInSection.student'])->get();

        return view('dashboard', compact('totalStudents', 'totalTutors', 'totalCourses', 'totalSections', 'courses'));
    }
}
