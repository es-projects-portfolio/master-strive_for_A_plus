<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;
use App\Models\Section;
use App\Models\StudentInSection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 3 tutors
        User::create([
            'name' => 'Tutor One',
            'username' => 'tutor1',
            'email' => 'tutor1@example.com',
            'password' => Hash::make('password'),
            'role' => 'tutor',
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Tutor Two',
            'username' => 'tutor2',
            'email' => 'tutor2@example.com',
            'password' => Hash::make('password'),
            'role' => 'tutor',
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Tutor Three',
            'username' => 'tutor3',
            'email' => 'tutor3@example.com',
            'password' => Hash::make('password'),
            'role' => 'tutor',
            'is_admin' => false,
        ]);

        // Create 6 students
        for ($i = 1; $i <= 6; $i++) {
            User::create([
                'name' => "Student $i",
                'username' => "student$i",
                'email' => "student$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'student',
                'is_admin' => false,
            ]);
        }

        // Create 1 admin
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
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

        /**
         * Assign students to sections.
         * 
         * This code assigns students to different sections by creating entries in the StudentInSection model.
         * 
         * The first loop assigns students with IDs 1 to 3 to the first section.
         * The second loop assigns students with IDs 4 to 6 to the second section.
         * The third loop assigns students with IDs 1 to 3 to the third section.
         * The fourth loop assigns students with IDs 4 to 6 to the fourth section.
         * 
         * Each loop iterates over a range of student IDs and creates a new StudentInSection entry
         * with the corresponding section ID and student ID.
         */
        // Assign students to sections
        for ($i = 1; $i <= 3; $i++) {
            StudentInSection::create([
                'section_id' => $section1->id,
                'student_id' => $i,
            ]);
        }

        for ($i = 4; $i <= 6; $i++) {
            StudentInSection::create([
                'section_id' => $section2->id,
                'student_id' => $i,
            ]);
        }

        for ($i = 1; $i <= 3; $i++) {
            StudentInSection::create([
                'section_id' => $section3->id,
                'student_id' => $i,
            ]);
        }

        for ($i = 4; $i <= 6; $i++) {
            StudentInSection::create([
                'section_id' => $section4->id,
                'student_id' => $i,
            ]);
        }
    }
}
