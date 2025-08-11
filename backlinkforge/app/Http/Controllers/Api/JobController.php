<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $jobs = JobLog::with(['campaign'])->paginate();
        return response()->json($jobs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
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
        return response()->json($job, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobLog $job): JsonResponse
    {
        return response()->json($job->load(['campaign']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobLog $job): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'string|in:pending,running,completed,failed',
            'payload' => 'array',
            'result' => 'array',
            'attempts' => 'integer|min:0',
        ]);

        $job->update($validated);
        return response()->json($job);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobLog $job): JsonResponse
    {
        $job->delete();
        return response()->json(null, 204);
    }

    /**
     * Execute the job.
     */
    public function execute(JobLog $job): JsonResponse
    {
        $job->update(['status' => 'running']);
        return response()->json(['message' => 'Job execution started']);
    }

    /**
     * Cancel the job.
     */
    public function cancel(JobLog $job): JsonResponse
    {
        $job->update(['status' => 'failed']);
        return response()->json(['message' => 'Job cancelled successfully']);
    }
} 