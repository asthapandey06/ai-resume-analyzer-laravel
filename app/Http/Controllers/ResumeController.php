<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResumeRequest;

use App\Models\Resume;
use App\Services\ResumeService;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function __construct(private ResumeService $resumeService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResumeRequest $request)
    {
        $file = $request->file('resume');

        $resume = $this->resumeService->upload($file);

        return response()->json([
            'message' => 'Resume uploaded successfully.',
            'data' => $resume,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Resume $resume)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resume $resume)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resume $resume)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resume $resume)
    {
        //
    }
}
