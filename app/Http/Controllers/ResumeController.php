<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListResumeRequest;
use App\Http\Requests\StoreResumeRequest;

use App\Models\Resume;
use App\Services\Resume\ResumeService;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function __construct(private ResumeService $resumeService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ListResumeRequest $request)
    {
        $resumes = $this->resumeService->getAll(
            $request->get('sort', 'desc'),
            $request->get('limit', 10),
            $request->get('page', 1),
            $request->get('filters', null)
        );

        return response()->json($resumes);
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
        return response()->json($resume->load('analysis'));
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
        //delete the resume file from storage and the record from the database
        $this->resumeService->delete($resume);
        return response()->json(['message' => 'Resume deleted successfully.']);
    }
}
