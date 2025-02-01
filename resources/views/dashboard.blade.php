<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ __("You're logged in!") }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Total Students: {{ $totalStudents }}</p>
                    <p>Total Tutors: {{ $totalTutors }}</p>
                    <p>Total Courses: {{ $totalCourses }}</p>
                    <p>Total Sections: {{ $totalSections }}</p>
                </div>
            </div>
        </div>
    </div>

    @foreach ($courses as $course)
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2>Course: {{ $course->course_name }}</h2>
                        @foreach ($course->sections as $section)
                            <div class="p-6 text-gray-900">
                                <h3>Section: {{ $section->section_number }}</h3>
                                <p>Tutor: {{ $section->tutor->name }}</p>
                                <p>Number of Students: {{ $section->studentsInSection->count() }}</p>
                                <ul>
                                    @foreach ($section->studentsInSection as $studentInSection)
                                        <li>{{ optional($studentInSection->student)->name ?? 'Unknown Student' }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
