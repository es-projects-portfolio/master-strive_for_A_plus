<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;
use App\Models\Section;
use App\Models\StudentInSection;
use App\Models\Material;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // Create 3 tutors
        User::create([
            'name' => 'Tutor One',
            'username' => 'tutor1',
            'email' => 'tutor1@example.com',
            'password' => $password,
            'role' => 'tutor',
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Tutor Two',
            'username' => 'tutor2',
            'email' => 'tutor2@example.com',
            'password' => $password,
            'role' => 'tutor',
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Tutor Three',
            'username' => 'tutor3',
            'email' => 'tutor3@example.com',
            'password' => $password,
            'role' => 'tutor',
            'is_admin' => false,
        ]);

        // Create 6 students
        for ($i = 1; $i <= 6; $i++) {
            User::create([
                'name' => "Student $i",
                'username' => "student$i",
                'email' => "student$i@example.com",
                'password' => $password,
                'role' => 'student',
                'is_admin' => false,
                'category' => $i <= 2 ? 'primary' : ($i <= 4 ? 'lower_secondary' : 'upper_secondary'),
            ]);
        }

        // Create 1 admin
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => $password,
            'role' => 'tutor',
            'is_admin' => true,
        ]);

        // Create 2 courses
        $course1 = Course::create([
            'course_name' => 'Course One',
        ]);

        $course2 = Course::create([
            'course_name' => 'Course Two',
        ]);

        // Create 2 sections for each course
        $section1 = Section::create([
            'section_number' => 1,
            'course_id' => $course1->id,
            'tutor_id' => 1, // Assuming Tutor One has ID 1
        ]);

        $section2 = Section::create([
            'section_number' => 2,
            'course_id' => $course1->id,
            'tutor_id' => 2, // Assuming Tutor Two has ID 2
        ]);

        $section3 = Section::create([
            'section_number' => 1,
            'course_id' => $course2->id,
            'tutor_id' => 3, // Assuming Tutor Three has ID 3
        ]);

        $section4 = Section::create([
            'section_number' => 2,
            'course_id' => $course2->id,
            'tutor_id' => 1, // Assuming Tutor One has ID 1
        ]);

        // Assign students to sections
        for ($i = 4; $i <= 6; $i++) {
            StudentInSection::create([
                'section_id' => $section1->id,
                'student_id' => $i,
            ]);
        }

        for ($i = 7; $i <= 9; $i++) {
            StudentInSection::create([
                'section_id' => $section2->id,
                'student_id' => $i,
            ]);
        }

        for ($i = 4; $i <= 6; $i++) {
            StudentInSection::create([
                'section_id' => $section3->id,
                'student_id' => $i,
            ]);
        }

        for ($i = 7; $i <= 9; $i++) {
            StudentInSection::create([
                'section_id' => $section4->id,
                'student_id' => $i,
            ]);
        }

        // Create materials
        Material::create([
            'visible_to_all' => true,
            'category' => null,
            'section_id' => $section1->id,
            'user_id' => 1, // Assuming Tutor One has ID 1
            'message' => 'Welcome to Course One, Section 1!',
            'image' => null,
            'video' => null,
            'file_path' => null,
            'tag' => 'announcement',
        ]);

        Material::create([
            'visible_to_all' => false,
            'category' => 'primary',
            'section_id' => null,
            'user_id' => 2, // Assuming Tutor Two has ID 2
            'message' => 'Course One, Section 2 materials are now available.',
            'image' => null,
            'video' => null,
            'file_path' => null,
            'tag' => 'notes',
        ]);

        Material::create([
            'visible_to_all' => false,
            'category' => null,
            'section_id' => $section3->id,
            'user_id' => 3, // Assuming Tutor Three has ID 3
            'message' => 'Welcome to Course Two, Section 1!',
            'image' => null,
            'video' => null,
            'file_path' => null,
            'tag' => 'announcement',
        ]);

        Material::create([
            'visible_to_all' => false,
            'category' => 'lower_secondary',
            'section_id' => null,
            'user_id' => 1, // Assuming Tutor One has ID 1
            'message' => 'Course Two, Section 2 materials are now available.',
            'image' => null,
            'video' => null,
            'file_path' => null,
            'tag' => 'assignment',
        ]);
    }
}
