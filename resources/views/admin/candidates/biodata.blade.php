@php
    $candidate = $candidate ?? null;
@endphp

<div class="space-y-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-slate-900">Bio Data</h2>
        <a href="{{ route('admin.candidates.show', $candidate) }}?tab=biodata&edit=1" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
            Edit Bio Data
        </a>
    </div>

    @if(request()->get('edit'))
        <!-- Edit Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            @include('admin.candidates._biodata_form', ['candidate' => $candidate])
        </div>
    @else
        <!-- View Mode -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div class="bg-slate-50 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Personal Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Position</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->position ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Identity Number</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->national_id ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Nationality</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->nationality ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Sex</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->sex ? ucfirst($candidate->sex) : 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Religion</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->religion ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">KRA PIN</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->kra_pin ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">NSSF Number</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->nssf_number ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">NHIF Number</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->nhif_number ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-slate-50 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Address Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Current Address</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->current_address ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Home Address</p>
                        <p class="text-sm font-medium text-slate-900">
                            @if($candidate->home_county || $candidate->home_sub_county || $candidate->home_ward || $candidate->home_estate || $candidate->home_house_number)
                                {{ implode(', ', array_filter([
                                    $candidate->home_house_number,
                                    $candidate->home_estate,
                                    $candidate->home_ward,
                                    $candidate->home_sub_county,
                                    $candidate->home_county
                                ])) }}
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Family Information -->
            <div class="bg-slate-50 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Family Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Marital Status</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->marital_status ? ucfirst($candidate->marital_status) : 'Not provided' }}</p>
                    </div>
                    @if($candidate->marital_status === 'married')
                        <div>
                            <p class="text-xs text-slate-500">Spouse Name</p>
                            <p class="text-sm font-medium text-slate-900">{{ $candidate->spouse_name ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Spouse Phone</p>
                            <p class="text-sm font-medium text-slate-900">
                                @if($candidate->spouse_phone_country_code)
                                    +{{ $candidate->spouse_phone_country_code }} 
                                @endif
                                {{ $candidate->spouse_phone ?? 'Not provided' }}
                            </p>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-slate-500">Number of Children</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->number_of_children ?? 0 }}</p>
                    </div>
                    @if($candidate->children_names)
                        <div>
                            <p class="text-xs text-slate-500">Children Names</p>
                            <p class="text-sm font-medium text-slate-900">{{ $candidate->children_names }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Parents Information -->
            <div class="bg-slate-50 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Parents Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Father's Name</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->father_name ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Father's Phone</p>
                        <p class="text-sm font-medium text-slate-900">
                            @if($candidate->father_phone_country_code)
                                +{{ $candidate->father_phone_country_code }} 
                            @endif
                            {{ $candidate->father_phone ?? 'Not provided' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Father's Address</p>
                        <p class="text-sm font-medium text-slate-900">
                            @if($candidate->father_county || $candidate->father_sub_county || $candidate->father_ward)
                                {{ implode(', ', array_filter([
                                    $candidate->father_ward,
                                    $candidate->father_sub_county,
                                    $candidate->father_county
                                ])) }}
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Mother's Name</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->mother_name ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Mother's Phone</p>
                        <p class="text-sm font-medium text-slate-900">
                            @if($candidate->mother_phone_country_code)
                                +{{ $candidate->mother_phone_country_code }} 
                            @endif
                            {{ $candidate->mother_phone ?? 'Not provided' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Health & Emergency -->
            <div class="bg-slate-50 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Health & Emergency</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Health/Physical Condition</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->health_physical_condition ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Blood Group</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->blood_group ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Emergency Contact</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->emergency_contact_name ?? 'Not provided' }}</p>
                        @if($candidate->emergency_contact_phone)
                            <p class="text-xs text-slate-500 mt-1">
                                @if($candidate->emergency_contact_phone_country_code)
                                    +{{ $candidate->emergency_contact_phone_country_code }} 
                                @endif
                                {{ $candidate->emergency_contact_phone }}
                            </p>
                        @endif
                    </div>
                    @if($candidate->medical_conditions)
                        <div>
                            <p class="text-xs text-slate-500">Medical Conditions</p>
                            <p class="text-sm font-medium text-slate-900">{{ $candidate->medical_conditions }}</p>
                        </div>
                    @endif
                    @if($candidate->allergies)
                        <div>
                            <p class="text-xs text-slate-500">Allergies</p>
                            <p class="text-sm font-medium text-slate-900">{{ $candidate->allergies }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Educational Qualifications -->
            <div class="bg-slate-50 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Educational Qualifications</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Primary School</p>
                        <p class="text-sm font-medium text-slate-900">
                            {{ $candidate->primary_school ?? 'Not provided' }}
                            @if($candidate->primary_graduation_year)
                                ({{ $candidate->primary_graduation_year }})
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Secondary School</p>
                        <p class="text-sm font-medium text-slate-900">
                            {{ $candidate->secondary_school ?? 'Not provided' }}
                            @if($candidate->secondary_graduation_year)
                                ({{ $candidate->secondary_graduation_year }})
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">University/College</p>
                        <p class="text-sm font-medium text-slate-900">
                            {{ $candidate->university_college ?? 'Not provided' }}
                            @if($candidate->university_graduation_year)
                                ({{ $candidate->university_graduation_year }})
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Professional Qualifications</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->professional_qualifications ?? 'Not provided' }}</p>
                    </div>
                    @if($candidate->special_skills)
                        <div>
                            <p class="text-xs text-slate-500">Special Skills</p>
                            <p class="text-sm font-medium text-slate-900">{{ $candidate->special_skills }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Next of Kin -->
            <div class="bg-slate-50 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Next of Kin</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Name</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->next_of_kin_name ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Phone</p>
                        <p class="text-sm font-medium text-slate-900">
                            @if($candidate->next_of_kin_phone_country_code)
                                +{{ $candidate->next_of_kin_phone_country_code }} 
                            @endif
                            {{ $candidate->next_of_kin_phone ?? 'Not provided' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Relationship</p>
                        <p class="text-sm font-medium text-slate-900">{{ $candidate->next_of_kin_relationship ?? 'Not provided' }}</p>
                    </div>
                    @if($candidate->next_of_kin_address)
                        <div>
                            <p class="text-xs text-slate-500">Address</p>
                            <p class="text-sm font-medium text-slate-900">{{ $candidate->next_of_kin_address }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
