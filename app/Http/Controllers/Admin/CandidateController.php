<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CandidateController extends Controller
{
    /**
     * Apply company filter if user is a client
     */
    protected function applyCompanyFilter($query)
    {
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            // Filter candidates by their job applications' company_id
            return $query->whereHas('jobApplications', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            });
        }
        return $query;
    }

    /**
     * Display a listing of candidates.
     */
    public function index(Request $request): View
    {
        $query = Candidate::withCount(['jobApplications']);

        // Apply company filter for clients
        $query = $this->applyCompanyFilter($query);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by candidates with applications
        if ($request->filled('has_applications')) {
            $hasApplications = $request->boolean('has_applications');
            if ($hasApplications) {
                $query->has('jobApplications');
            } else {
                $query->doesntHave('jobApplications');
            }
        }

        // Get total counts (with company filter)
        $totalCandidates = $this->applyCompanyFilter(Candidate::query())->count();
        $withApplicationsCount = $this->applyCompanyFilter(Candidate::query())
            ->has('jobApplications')
            ->count();
        $withoutApplicationsCount = $totalCandidates - $withApplicationsCount;
        $filteredCount = $query->count();

        $candidates = $query->with([
            'jobApplications' => function ($q) {
                $q->select('id', 'candidate_id', 'job_post_id', 'status', 'created_at')
                  ->with(['jobPost:id,title,company_id']);
            }
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(15)
        ->withQueryString()
        ->setPath(route('admin.candidates.index'));

        return view('admin.candidates.index', compact(
            'candidates',
            'totalCandidates',
            'withApplicationsCount',
            'withoutApplicationsCount',
            'filteredCount'
        ));
    }

    /**
     * Display the specified candidate.
     */
    public function show(Candidate $candidate): View
    {
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        // Load all applications with relationships
        $applications = $candidate->jobApplications()
            ->with([
                'jobPost:id,title,slug,company_id',
                'jobPost.company:id,name',
                'aiSievingDecision',
                'aptitudeTestSession',
                'selfInterviewSession',
                'interviews' => function($q) {
                    $q->orderBy('scheduled_at', 'desc');
                },
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total' => $applications->count(),
            'pending' => $applications->where('status', 'pending')->count(),
            'sieving_passed' => $applications->where('status', 'sieving_passed')->count(),
            'sieving_rejected' => $applications->where('status', 'sieving_rejected')->count(),
            'stage_2_passed' => $applications->where('status', 'stage_2_passed')->count(),
            'shortlisted' => $applications->where('status', 'shortlisted')->count(),
            'interview_scheduled' => $applications->where('status', 'interview_scheduled')->count(),
            'interview_passed' => $applications->where('status', 'interview_passed')->count(),
            'hired' => $applications->where('status', 'hired')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
        ];

        // Group applications by company
        $applicationsByCompany = $applications->groupBy(function ($application) {
            return $application->jobPost && $application->jobPost->company 
                ? $application->jobPost->company->name 
                : 'Unknown Company';
        });

        return view('admin.candidates.show', compact(
            'candidate',
            'applications',
            'applicationsByCompany',
            'stats'
        ));
    }

    /**
     * Show the form for editing the specified candidate.
     */
    public function edit(Candidate $candidate): View
    {
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        return view('admin.candidates.edit', compact('candidate'));
    }

    /**
     * Update the specified candidate.
     */
    public function update(Request $request, Candidate $candidate): RedirectResponse
    {
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:candidates,email,' . $candidate->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update candidate
        $candidate->name = $validated['name'];
        $candidate->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $candidate->password = Hash::make($validated['password']);
        }

        $candidate->save();

        return redirect()->route('admin.candidates.show', $candidate)
            ->with('success', 'Candidate updated successfully.');
    }

    /**
     * Remove the specified candidate.
     */
    public function destroy(Candidate $candidate): RedirectResponse
    {
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        // Check if candidate has applications
        if ($candidate->jobApplications()->count() > 0) {
            return redirect()->route('admin.candidates.index')
                ->withErrors(['error' => 'Cannot delete candidate with existing job applications. Please delete or reassign applications first.']);
        }

        $candidate->delete();

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate deleted successfully.');
    }

    /**
     * Update candidate bio data.
     */
    public function updateBiodata(Request $request, Candidate $candidate): RedirectResponse
    {
        $user = auth()->user();
        
        // Check company access for clients
        if ($user && $user->isClient() && $user->company_id) {
            $hasAccess = $candidate->jobApplications()
                ->where('company_id', $user->company_id)
                ->exists();
            
            if (!$hasAccess) {
                abort(403, 'You do not have permission to access this candidate.');
            }
        }

        // Use same validation as candidate controller
        $validated = $request->validate([
            // Employment & Personal
            'position' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'sex' => ['nullable', 'string', 'in:male,female,other'],
            'religion' => ['nullable', 'string', 'max:100'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'kra_pin' => ['nullable', 'string', 'max:50'],
            'nssf_number' => ['nullable', 'string', 'max:50'],
            'nhif_number' => ['nullable', 'string', 'max:50'],
            'marital_status' => ['nullable', 'string', 'in:single,married,divorced,widowed'],
            
            // Address Details
            'current_address' => ['nullable', 'string', 'max:500'],
            'home_county' => ['nullable', 'string', 'max:100'],
            'home_sub_county' => ['nullable', 'string', 'max:100'],
            'home_ward' => ['nullable', 'string', 'max:100'],
            'home_estate' => ['nullable', 'string', 'max:100'],
            'home_house_number' => ['nullable', 'string', 'max:50'],
            
            // Spouse Information
            'spouse_name' => ['nullable', 'string', 'max:255'],
            'spouse_phone_country_code' => ['nullable', 'string', 'max:5'],
            'spouse_phone' => ['nullable', 'string', 'max:20'],
            
            // Children Information
            'number_of_children' => ['nullable', 'integer', 'min:0'],
            'children_names' => ['nullable', 'string', 'max:1000'],
            
            // Parents Information
            'father_name' => ['nullable', 'string', 'max:255'],
            'father_phone_country_code' => ['nullable', 'string', 'max:5'],
            'father_phone' => ['nullable', 'string', 'max:20'],
            'father_county' => ['nullable', 'string', 'max:100'],
            'father_sub_county' => ['nullable', 'string', 'max:100'],
            'father_ward' => ['nullable', 'string', 'max:100'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_phone_country_code' => ['nullable', 'string', 'max:5'],
            'mother_phone' => ['nullable', 'string', 'max:20'],
            
            // Emergency Contact
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_phone_country_code' => ['nullable', 'string', 'max:5'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
            
            // Health Information
            'health_physical_condition' => ['nullable', 'string', 'max:255'],
            'medical_conditions' => ['nullable', 'string', 'max:1000'],
            'allergies' => ['nullable', 'string', 'max:500'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            
            // Next of Kin
            'next_of_kin_name' => ['nullable', 'string', 'max:255'],
            'next_of_kin_phone' => ['nullable', 'string', 'max:20'],
            'next_of_kin_phone_country_code' => ['nullable', 'string', 'max:5'],
            'next_of_kin_relationship' => ['nullable', 'string', 'max:100'],
            'next_of_kin_address' => ['nullable', 'string', 'max:500'],
            
            // Educational Qualifications
            'primary_school' => ['nullable', 'string', 'max:255'],
            'primary_graduation_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'secondary_school' => ['nullable', 'string', 'max:255'],
            'secondary_graduation_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'university_college' => ['nullable', 'string', 'max:255'],
            'university_graduation_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'professional_qualifications' => ['nullable', 'string', 'max:255'],
            'special_skills' => ['nullable', 'string', 'max:1000'],
        ]);

        $candidate->fill($validated);
        $candidate->save();

        return redirect()->route('admin.candidates.show', $candidate)
            ->with('success', 'Bio data updated successfully.');
    }
}

