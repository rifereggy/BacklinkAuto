<?php

namespace App\Http\Controllers;

use App\Models\JobLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = JobLog::with(['campaign'])->paginate();
        
        return Inertia::render('Jobs/Index', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Jobs/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'node_id' => 'string|max:255',
            'job_type' => 'required|string|max:255',
            'status' => 'string|in:pending,running,completed,failed',
            'payload' => 'array',
            'result' => 'array',
            'attempts' => 'integer|min:0',
        ]);

        $job = JobLog::create($validated);

        return redirect()->route('jobs.show', $job);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobLog $job)
    {
        return Inertia::render('Jobs/Show', [
            'job' => $job->load(['campaign']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobLog $job)
    {
        return Inertia::render('Jobs/Edit', [
            'job' => $job,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobLog $job)
    {
        $validated = $request->validate([
            'status' => 'string|in:pending,running,completed,failed',
            'payload' => 'array',
            'result' => 'array',
            'attempts' => 'integer|min:0',
        ]);

        $job->update($validated);

        return redirect()->route('jobs.show', $job);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobLog $job)
    {
        $job->delete();

        return redirect()->route('jobs.index');
    }
} 