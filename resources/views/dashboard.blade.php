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
