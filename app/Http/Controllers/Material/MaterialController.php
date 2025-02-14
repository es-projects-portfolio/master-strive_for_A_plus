<?php

namespace App\Http\Controllers\Material;

use App\Models\Course;
use App\Models\Section;
use App\Models\Material;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     * Adds a concise explanation of how materials are filtered and shown.
     * Note: Applies visibility rules for tutors, students, or guests.
     */
    public function index(Request $request): View
    {
        $query = Material::query();

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'tutor') {
                // Tutors can see all materials they created
                
            } else {
                // Students can see materials based on the specified conditions
                $query->where(function ($query) use ($user) {
                    $query->where('visible_to_all', true)
                        ->orWhere(function ($query) use ($user) {
                            $query->where('visible_to_all', false)
                                  ->whereNotNull('category');
                        })
                        ->orWhereHas('section.studentsInSection', function ($query) use ($user) {
                            $query->where('student_id', $user->id);
                        });
                });
            }
        } else {
            // Unauthenticated users can see materials based on the specified conditions
            $query->where(function ($query) {
                $query->where('visible_to_all', false)
                      ->whereNotNull('category')
                      ->orWhere(function ($query) {
                          $query->where('visible_to_all', true)
                                ->whereNull('category')
                                ->whereNull('section_id');
                      });
            });
        }

        // Apply filters
        if ($request->filled('tag')) {
            $query->where('tag', $request->tag);
        }

        if ($request->filled('course_section')) {
            $query->where('section_id', $request->course_section);
        }

        if ($request->filled('visibility')) {
            if ($request->visibility === 'public') {
                $query->where('visible_to_all', true);
            } else {
                $query->where('visible_to_all', false);
            }
        }

        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Apply sorting
        $sortOrder = $request->get('sort', 'desc');
        $query->orderBy('created_at', $sortOrder);

        $materials = $query->get();

        $sections = Section::all();
        $authors = User::where('role', 'tutor')->get();

        return view('materials.index', [
            'materials' => $materials,
            'sections' => $sections,
            'authors' => $authors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * Notes that only tutors can access this form.
     * Note: Redirects non-tutors back with an error message.
     */
    public function create(): View
    {
        $user = Auth::user();

        // Ensure the user is a tutor
        if ($user->role !== 'tutor') {
            return redirect(route('materials.index'))->with('error', 'Only tutors can create materials.');
        }

        $sections = Section::where('tutor_id', $user->id)->get();

        return view('materials.upload', [
            'sections' => $sections,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * Explains how material data is validated and saved.
     * Note: Checks tutor role, saves uploaded files, and creates a record.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Ensure the user is a tutor
        if ($request->user()->role !== 'tutor') {
            return redirect(route('materials.index'))->with('error', 'Only tutors can create materials.');
        }

        // Validate the request data
        $validated = $request->validate([
            'visible_to_all' => 'nullable|boolean',
            'category' => 'nullable|in:primary,lower_secondary,upper_secondary',
            'section_id' => 'nullable|exists:sections,id',
            'message' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240',
            'file_path' => 'nullable|file|mimes:csv,json,pdf,docx,xlsx|max:5120',
            'tag' => 'required|in:past-year,assignment,quiz,exam,notes,announcement',
        ]);

        // Set visibility to 'all' if checkbox is checked
        $validated['visible_to_all'] = $request->has('visible_to_all');

        // Ensure category and section_id are set correctly
        if ($validated['visible_to_all']) {
            $validated['category'] = null;
            $validated['section_id'] = null;
        } else {
            if ($validated['category']) {
                $validated['section_id'] = null;
            } else {
                $request->validate([
                    'section_id' => 'required|exists:sections,id',
                ]);
            }
        }

        // Store the uploaded files
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $filename);

            $validated['image'] = 'images/' . $filename;
        }

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $filename = time() . '.' . $video->getClientOriginalExtension();
            $video->storeAs('public/videos', $filename);

            $validated['video'] = 'videos/' . $filename;
        }

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/files', $filename);
            $validated['file_path'] = 'files/' . $filename;
        }

        // Create the material
        $request->user()->materials()->create($validated);

        return redirect(route('materials.index'));
    }

    /**
     * Display the specified resource.
     * Adds a note about material visibility checks.
     * Note: Restricts viewing to rightful users (public, tutor, etc.).
     */
    public function show(Material $material): View
    {
        $user = Auth::user();

        // Check if the material is visible to all
        if ($material->visible_to_all) {
            return view('materials.show', ['material' => $material]);
        }

        // Check if the user is a tutor or admin
        if ($user->role === 'tutor' || $user->is_admin) {
            return view('materials.show', ['material' => $material]);
        }

        // Check if the user is a student in the section
        $isStudentInSection = $material->section->studentsInSection()->where('student_id', $user->id)->exists();

        if ($isStudentInSection) {
            return view('materials.show', ['material' => $material]);
        }

        return redirect(route('materials.index'))->with('error', 'You do not have permission to view this material.');
    }

    /**
     * Show the form for editing the specified resource.
     * Adds a short comment about tutor-only editing access.
     * Note: Redirects if the user is not a tutor or unauthorized.
     */
    public function edit(Material $material): View
    {
        $user = Auth::user();

        // Ensure the user is the owner of the material or a tutor
        if ($user->id !== $material->user_id && $user->role !== 'tutor') {
            return redirect(route('materials.index'))->with('error', 'You do not have permission to edit this material.');
        }
        // Authorize the user
        Gate::authorize('update', $material);

        $sections = Section::where('tutor_id', $user->id)->get();

        return view('materials.edit', [
            'material' => $material,
            'sections' => $sections,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * Describes how updated data is validated and stored.
     * Note: Checks tutor role, validates input, and updates material info.
     */
    public function update(Request $request, Material $material): RedirectResponse
    {
        $user = Auth::user();

        // Ensure the user is the owner of the material or a tutor
        if ($user->id !== $material->user_id && $user->role !== 'tutor') {
            return redirect(route('materials.index'))->with('error', 'You do not have permission to update this material.');
        }

        // Authorize the user
        Gate::authorize('update', $material);

        // Validate the request data
        $validated = $request->validate([
            'visible_to_all' => 'nullable|boolean',
            'category' => 'nullable|in:primary,lower_secondary,upper_secondary',
            'section_id' => 'nullable|exists:sections,id',
            'message' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240',
            'file_path' => 'nullable|file|mimes:csv,json,pdf,docx,xlsx|max:5120',
            'tag' => 'required|in:past-year,assignment,quiz,exam,notes,announcement',
        ]);

        // Set visibility to 'all' if checkbox is checked
        $validated['visible_to_all'] = $request->has('visible_to_all');

        // Ensure category and section_id are set correctly
        if ($validated['visible_to_all']) {
            $validated['category'] = null;
            $validated['section_id'] = null;
        } else {
            if ($validated['category']) {
                $validated['section_id'] = null;
            } else {
                $request->validate([
                    'section_id' => 'required|exists:sections,id',
                ]);
            }
        }

        // Store the uploaded files
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $filename);

            $validated['image'] = 'images/' . $filename;
        }

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $filename = time() . '.' . $video->getClientOriginalExtension();
            $video->storeAs('public/videos', $filename);

            $validated['video'] = 'videos/' . $filename;
        }

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/files', $filename);
            $validated['file_path'] = 'files/' . $filename;
        }

        // Update the material
        $material->update($validated);

        return redirect(route('materials.index'))->with('success', 'Material updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * Explains tutor-only deletion capability.
     * Note: Ensures the user has the correct role before deleting.
     */
    public function destroy(Material $material): RedirectResponse
    {
        $user = Auth::user();

        // Ensure the user is a tutor
        if ($user->role !== 'tutor') {
            return redirect(route('materials.index'))->with('error', 'Only tutors can delete materials.');
        }

        // Authorize the user
        Gate::authorize('delete', $material);

        // Delete the material
        $material->delete();

        return redirect(route('materials.index'))->with('success', 'Material deleted successfully.');
    }
}
