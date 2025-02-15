## Commands
### Install existing package
- `composer install`
- `npm i`
- `npm run build`
### Database migration and seeder
- Migrate refresh: `php artisan migrate:refresh`
- DB seeder: `php artisan db:seed`

## Pages
1. **Register**
   - Choose Tutor or Student
   - Student can select a category
2. **Login**
   - Use either username or email
3. **Forgot Password**
   - Enter email
   - Verification sent to email
4. **Dashboard**
   - Show counts (tutors, students, courses, sections)
   - List names of students and tutors in each section
5. **Admin (admin only)**
   - Create courses
   - Create sections for a selected course and assign a lecturer
   - Assign students to selected sections
6. **Upload (admin & tutor)**
   - Upload material text, file, image, or video
   - Set visibility:
     - Public
     - Category
     - Section (only one section)
   - Add tags
7. **Material (all users, including guests)**
   - Display public and categorized materials
   - Only students in assigned sections can view their section’s materials
   - Filters: sort, tag, visibility, category, author, course, and section

## Roles
- **admin**: Create courses, sections, assign students, upload, view all materials
- **tutor**: Upload (public, category, owned sections), view all materials
- **student**: View public and assigned section materials

## Credentials
- **admin**: username: admin, password: password
- **tutor**: username: tutor1, password: password
- **student**: username: student1, password: password


