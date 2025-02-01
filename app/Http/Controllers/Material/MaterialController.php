<?php

namespace App\Http\Controllers\Material;

use App\Models\Course;
use App\Models\Section;
use App\Models\Material;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $courses = Course::with(['sections.tutor', 'sections.studentsInSection.student'])->get();

        $user = Auth::user();

        if ($user->role === 'tutor') {
            // Tutors can see all materials they created
            $materials = Material::where('user_id', $user->id)->get();
        } else {
            // Students can see materials visible to all or in their sections
            $materials = Material::where('visible_to_all', true)
                ->orWhereHas('section.studentsInSection', function ($query) use ($user) {
                    $query->where('student_id', $user->id);
                })
                ->get();
        }

        return view('materials.index', ['materials' => $materials]);
    }

    /**
     * Show the form for creating a new resource.
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
            'section_id' => 'nullable|exists:sections,id',
            'message' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240',
            'file_path' => 'nullable|file|mimes:csv,json,pdf,docx,xlsx|max:5120',
        ]);

        // Set visibility to 'all' if checkbox is checked
        $validated['visible_to_all'] = $request->has('visible_to_all');

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
     */
    public function edit(Material $material): View
    {
        $user = Auth::user();

        // Ensure the user is a tutor
        if ($user->role !== 'tutor') {
            return redirect(route('materials.index'))->with('error', 'Only tutors can edit materials.');
        }

        // Authorize the user
        Gate::authorize('update', $material);

        $user = Auth::user();
        $sections = Section::where('tutor_id', $user->id)->get();

        return view('materials.edit', [
            'material' => $material,
            'sections' => $sections,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material): RedirectResponse
    {
        // Ensure the user is a tutor
        if ($request->user()->role !== 'tutor') {
            return redirect(route('materials.index'))->with('error', 'Only tutors can update materials.');
        }

        // Authorize the user
        Gate::authorize('update', $material);

        // Validate the request data
        $validated = $request->validate([
            'visible_to_all' => 'nullable|boolean',
            'section_id' => 'nullable|exists:sections,id',
            'message' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240',
            'file_path' => 'nullable|file|mimes:csv,json,pdf,docx,xlsx|max:5120',
        ]);

        // Set visibility to 'all' if checkbox is checked
        $validated['visible_to_all'] = $request->has('visible_to_all');

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
