<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BiodataController extends Controller
{
    /**
     * Show the bio data form.
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();
        
        return view('candidate.biodata.index', compact('candidate'));
    }

    /**
     * Update the candidate's bio data.
     */
    public function update(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();
        
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
        
        // Mark as completed if all required fields are filled
        $requiredFields = [
            'national_id',
            'kra_pin',
            'emergency_contact_name',
            'emergency_contact_phone',
            'next_of_kin_name',
            'next_of_kin_phone',
        ];
        
        $allRequiredFilled = true;
        foreach ($requiredFields as $field) {
            if (empty($candidate->$field)) {
                $allRequiredFilled = false;
                break;
            }
        }
        
        if ($allRequiredFilled && !$candidate->biodata_completed) {
            $candidate->biodata_completed = true;
            $candidate->biodata_completed_at = now();
        }
        
        $candidate->save();

        return Redirect::route('candidate.biodata.index')
            ->with('success', 'Bio data updated successfully.');
    }
}
