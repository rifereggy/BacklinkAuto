<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $templates = Template::with(['team', 'creator'])->paginate();
        return response()->json($templates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string',
            'graph_json' => 'array',
            'is_public' => 'boolean',
        ]);

        $template = Template::create([
            ...$validated,
            'team_id' => $request->user()->currentTeam->id,
            'created_by' => $request->user()->id,
        ]);

        return response()->json($template, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Template $template): JsonResponse
    {
        $this->authorize('view', $template);
        return response()->json($template->load(['team', 'creator']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template): JsonResponse
    {
        $this->authorize('update', $template);
        
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'string',
            'graph_json' => 'array',
            'is_public' => 'boolean',
        ]);

        $template->update($validated);
        return response()->json($template);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template): JsonResponse
    {
        $this->authorize('delete', $template);
        $template->delete();
        return response()->json(null, 204);
    }
} 