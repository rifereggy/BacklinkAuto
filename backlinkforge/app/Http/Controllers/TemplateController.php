<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = Template::with(['team', 'category'])
            ->where('team_id', auth()->user()->currentTeam->id)
            ->latest()
            ->paginate(12);

        return Inertia::render('Templates/Index', [
            'templates' => $templates,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Templates/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'variables' => 'nullable|array',
            'variables.*.name' => 'required|string',
            'variables.*.type' => 'required|in:text,url,number,select',
            'variables.*.required' => 'boolean',
            'variables.*.options' => 'nullable|array',
            'is_public' => 'boolean',
        ]);

        $template = Template::create([
            ...$validated,
            'team_id' => auth()->user()->currentTeam->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('templates.show', $template)
            ->with('success', 'Template created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        $template->load(['team', 'category', 'campaigns']);

        return Inertia::render('Templates/Show', [
            'template' => $template,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
        return Inertia::render('Templates/Edit', [
            'template' => $template,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'variables' => 'nullable|array',
            'variables.*.name' => 'required|string',
            'variables.*.type' => 'required|in:text,url,number,select',
            'variables.*.required' => 'boolean',
            'variables.*.options' => 'nullable|array',
            'is_public' => 'boolean',
        ]);

        $template->update($validated);

        return redirect()->route('templates.show', $template)
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template deleted successfully.');
    }
} 