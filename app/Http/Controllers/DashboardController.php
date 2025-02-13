<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Section;
use App\Models\Material;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with counts and course data.
     * Adds a concise explanation of the overview data loaded.
     * Note: Fetches totals for students, tutors, courses, sections, and materials.
     */
    public function index()
    {
        // Fetch total number of students
        $totalStudents = User::where('role', 'student')->count();
        
        // Fetch total number of tutors who are not admins
        $totalTutors = User::where('role', 'tutor')->where('is_admin', false)->count();
        
        // Fetch total number of courses
        $totalCourses = Course::count();
        
        // Fetch total number of sections
        $totalSections = Section::count();
        
        // Fetch total number of materials
        $totalMaterials = Material::count();

        // Fetch courses with related sections, tutors, and students
        $courses = Course::with(['sections.tutor', 'sections.studentsInSection.student'])->get();

        return view('dashboard', compact('totalStudents', 'totalTutors', 'totalCourses', 'totalSections', 'courses', 'totalMaterials'));
    }
}
