<!-- /**
 * This Blade template is used to render the dashboard view for the application.
 * 
 * The dashboard displays the following information:
 * - A welcome message indicating the user is logged in.
 * - Summary statistics including total counts of students, tutors, courses, sections, and materials.
 * - Detailed information for each course, including:
 *   - Course name.
 *   - Sections within the course, each displaying:
 *     - Section number.
 *     - Tutor's name.
 *     - Number of students in the section.
 *     - Number of materials in the section.
 *     - List of students in the section.
 * 
 * Variables:
 * - $totalStudents: Total number of students.
 * - $totalTutors: Total number of tutors.
 * - $totalCourses: Total number of courses.
 * - $totalSections: Total number of sections.
 * - $totalMaterials: Total number of materials.
 * - $courses: Collection of courses, each containing sections, tutors, students, and materials.
 */ -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ __("You're logged in!") }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>Total Students: {{ $totalStudents }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>Total Tutors: {{ $totalTutors }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>Total Courses: {{ $totalCourses }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>Total Sections: {{ $totalSections }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>Total Materials: {{ $totalMaterials }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($courses as $course)
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="font-semibold text-l text-gray-800 leading-tight">Course: {{ $course->course_name }}</h2>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($course->sections as $section)
                                <div class="p-6 text-gray-900 shadow-lg rounded-lg bg-white mt-4">
                                    <h3 class="font-semibold text-l text-gray-600 leading-tight mb-2 pb-2">Section: {{ $section->section_number }}</h3>
                                    <p>Tutor: {{ $section->tutor->name }}</p>
                                    <p>Number of Students: {{ $section->studentsInSection->count() }}</p>
                                    <p>Number of Materials: {{ $section->materials->count() }}</p>
                                    <div class="m-2 p-2 bg-gray-100 rounded-lg divide-y">
                                        <p>Students:</p>
                                        <ul>
                                            @foreach ($section->studentsInSection as $studentInSection)
                                                <li>- {{ optional($studentInSection->student)->name ?? 'Unknown Student' }}</li>
                                            @endforeach
                                        </ul>
                                    </div>  
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
