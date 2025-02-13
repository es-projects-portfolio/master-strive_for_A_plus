<!-- /**
 * This Blade template is used for the admin dashboard, providing functionalities to:
 * 1. Create a new course.
 * 2. Create a new section and assign a tutor to it.
 * 3. Assign students to a section.
 *
 * Components and functionalities:
 * - <x-app-layout>: Main layout component.
 * - <x-slot name="header">: Header section with the title "Admin".
 * - Success messages: Displayed if session variables 'success_course', 'success_section', or 'success_assign' are set.
 * - Create Course Form:
 *   - Action: Route 'admin.createCourse'.
 *   - Fields: Course Name (prefixed with "Course ").
 * - Create Section & Assign Tutor Form:
 *   - Action: Route 'admin.createSection'.
 *   - Fields: Section Number (numeric), Select Course (dropdown), Select Tutor (dropdown).
 * - Assign Students to Section Form:
 *   - Action: Route 'admin.assignStudentToSection'.
 *   - Fields: Select Course | Section (dropdown), Select Student (dropdown, multiple).
 *   - Dynamic addition of student selection fields using JavaScript.
 *
 * JavaScript:
 * - Adds functionality to dynamically add more student selection fields in the "Assign Students to Section" form.
 */ -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(session('success_course'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('success_course') }}
                    </div>
                @endif
                <!-- Create Course -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Create Course</h3>
                    <form action="{{ route('admin.createCourse') }}" method="POST">
                        @csrf
                        <div class="mt-4">
                            <x-input-label for="course_name">Course Name</x-input-label>
                            <input type="text" id="course_name" name="course_name" placeholder="Course Name" oninput="this.value = 'Course ' + this.value.replace(/^Course\s*/, '')" class="rounded">
                        </div>
                        <div>
                            <x-primary-button class="mt-4" type='submit'>{{ __('Submit') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(session('success_section'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('success_section') }}
                    </div>
                @endif
                <!-- Create Section & Assign Tutor -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Create Section & Assign Tutor</h3>
                    <form action="{{ route('admin.createSection') }}" method="POST">
                        @csrf
                        <div class="mt-4">
                            <x-input-label for="section_number">Section Number</x-input-label>
                            <input type="text" id="section_number" name="section_number" placeholder="Section Number" pattern="\d+" title="Section number must be a number" class="rounded">
                        </div>
                        <div class="mt-4">
                            <x-input-label for="course_id">Select Course</x-input-label>
                            <select id="course_id" name="course_id" class="rounded">
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="tutor_id">Select Tutor</x-input-label>
                            <select id="tutor_id" name="tutor_id" class="rounded">
                                @foreach($users->where('role', 'tutor') as $tutor)
                                    <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-primary-button class="mt-4" type='submit'>{{ __('Submit') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(session('success_assign'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('success_assign') }}
                    </div>
                @endif
                <!-- Assign Students to Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Assign Students to Section</h3>
                    <form action="{{ route('admin.assignStudentToSection') }}" method="POST">
                        @csrf
                        <div class="mt-4">
                            <x-input-label for="section_id">Select Course | Section</x-input-label>
                            <select id="section_id" name="section_id" class="rounded">
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->course->course_name }} | Section {{ $section->section_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="students-container">
                            <div class="student-select mt-4">
                                <x-input-label for="student_id">Select Student</x-input-label>
                                <select id="student_id" name="student_id[]" class="rounded">
                                    @foreach($users->where('role', 'student') as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-secondary-button type="button" id="add-student-btn" style="font-size: larger">+</x-secondary-button>
                        </div>
                        <div>
                            <x-primary-button class="mt-4" type='submit'>{{ __('Submit') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-student-btn').addEventListener('click', function() {
            var container = document.getElementById('students-container');
            var newSelect = document.querySelector('.student-select').cloneNode(true);
            container.appendChild(newSelect);
        });
    </script>
</x-app-layout>
