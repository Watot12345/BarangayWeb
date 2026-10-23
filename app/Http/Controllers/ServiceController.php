<?php

namespace App\Http\Controllers;

use App\Models\ReliefRequest;
use App\Models\HealthRecord;
use App\Models\JobApplication;
use App\Models\ScholarshipApplication;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function storeRelief(Request $request)
    {
        $validated = $request->validate([
            'assistance_type' => ['required', 'string', 'max:100'],
            'details' => ['nullable', 'string', 'max:1000'],
        ]);

        $record = ReliefRequest::create([
            'user_id' => Auth::id(),
            'assistance_type' => $validated['assistance_type'],
            'details' => $validated['details'] ?? null,
            'status' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Disaster relief assistance request submitted successfully. Barangay officers will review it shortly.',
            'record' => $record,
        ]);
    }

    public function storeHealth(Request $request)
    {
        $validated = $request->validate([
            'blood_pressure' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $record = HealthRecord::create([
            'user_id' => Auth::id(),
            'blood_pressure' => $validated['blood_pressure'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Health record saved successfully!',
            'record' => $record,
        ]);
    }

    public function applyJob(Request $request)
    {
        $validated = $request->validate([
            'job_title' => ['required', 'string', 'max:150'],
        ]);

        $application = JobApplication::create([
            'user_id' => Auth::id(),
            'job_title' => $validated['job_title'],
            'status' => 'Submitted',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application for "' . $validated['job_title'] . '" submitted successfully! The Barangay PESO desk will contact you.',
            'application' => $application,
        ]);
    }

    public function applyScholarship(Request $request)
    {
        $application = ScholarshipApplication::create([
            'user_id' => Auth::id(),
            'status' => 'Submitted',
            'notes' => $request->input('notes'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barangay San Agustin Scholarship Application submitted successfully! Please submit hard copy requirements to the education desk.',
            'application' => $application,
        ]);
    }

    public function requestDocument(Request $request)
    {
        $validated = $request->validate([
            'service_type' => ['required', 'string', 'max:100'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'purpose' => ['nullable', 'string', 'max:500'],
        ]);

        $docRequest = ServiceRequest::create([
            'user_id' => Auth::id(),
            'service_type' => $validated['service_type'],
            'full_name' => $validated['full_name'] ?? (Auth::user()?->name),
            'contact_number' => $validated['contact_number'] ?? (Auth::user()?->phone),
            'purpose' => $validated['purpose'] ?? null,
            'status' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document request for ' . $validated['service_type'] . ' has been recorded! Processing takes 1 to 2 business days.',
            'request' => $docRequest,
        ]);
    }
}
