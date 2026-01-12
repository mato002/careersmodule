@php
    $action = $action ?? route('admin.candidates.update-biodata', $candidate);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-8">
    @csrf
    @method('PATCH')

    <!-- Personal Employment Data -->
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Personal Employment Data</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                <input type="text" id="position" name="position" value="{{ old('position', $candidate->position) }}" 
                       placeholder="e.g., Relationship Officer, Manager"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('position')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="national_id" class="block text-sm font-medium text-gray-700 mb-1">Identity Number</label>
                <input type="text" id="national_id" name="national_id" value="{{ old('national_id', $candidate->national_id) }}" 
                       placeholder="e.g., 29200570"
                       pattern="[0-9]*" inputmode="numeric"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('national_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
                <input type="text" id="nationality" name="nationality" value="{{ old('nationality', $candidate->nationality) }}" 
                       placeholder="e.g., Kenyan" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('nationality')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sex" class="block text-sm font-medium text-gray-700 mb-1">Sex</label>
                <select id="sex" name="sex" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select...</option>
                    <option value="male" {{ old('sex', $candidate->sex) === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('sex', $candidate->sex) === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('sex', $candidate->sex) === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('sex')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="religion" class="block text-sm font-medium text-gray-700 mb-1">Religion</label>
                <input type="text" id="religion" name="religion" value="{{ old('religion', $candidate->religion) }}" 
                       placeholder="e.g., Christian, Muslim, Hindu" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('religion')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kra_pin" class="block text-sm font-medium text-gray-700 mb-1">KRA PIN</label>
                <input type="text" id="kra_pin" name="kra_pin" value="{{ old('kra_pin', $candidate->kra_pin) }}" 
                       placeholder="e.g., A123456789X"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('kra_pin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nssf_number" class="block text-sm font-medium text-gray-700 mb-1">NSSF Number</label>
                <input type="text" id="nssf_number" name="nssf_number" value="{{ old('nssf_number', $candidate->nssf_number) }}" 
                       placeholder="e.g., 12345678"
                       pattern="[0-9]*" inputmode="numeric"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('nssf_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nhif_number" class="block text-sm font-medium text-gray-700 mb-1">NHIF Number</label>
                <input type="text" id="nhif_number" name="nhif_number" value="{{ old('nhif_number', $candidate->nhif_number) }}" 
                       placeholder="e.g., 12345678"
                       pattern="[0-9]*" inputmode="numeric"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('nhif_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Address Information</h3>
        
        <div class="space-y-4">
            <div>
                <label for="current_address" class="block text-sm font-medium text-gray-700 mb-1">Current Address</label>
                <input type="text" id="current_address" name="current_address" value="{{ old('current_address', $candidate->current_address) }}" 
                       placeholder="e.g., 74 Embu" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('current_address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-slate-50 p-4 rounded-lg">
                <h4 class="text-sm font-semibold text-slate-900 mb-3">Home Physical Address</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="home_county" class="block text-sm font-medium text-gray-700 mb-1">County</label>
                        <input type="text" id="home_county" name="home_county" value="{{ old('home_county', $candidate->home_county) }}" 
                               placeholder="e.g., Embu, Nairobi"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('home_county')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="home_sub_county" class="block text-sm font-medium text-gray-700 mb-1">Sub-County</label>
                        <input type="text" id="home_sub_county" name="home_sub_county" value="{{ old('home_sub_county', $candidate->home_sub_county) }}" 
                               placeholder="e.g., Embu West"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('home_sub_county')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="home_ward" class="block text-sm font-medium text-gray-700 mb-1">Ward</label>
                        <input type="text" id="home_ward" name="home_ward" value="{{ old('home_ward', $candidate->home_ward) }}" 
                               placeholder="e.g., Kirimari"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('home_ward')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="home_estate" class="block text-sm font-medium text-gray-700 mb-1">Estate</label>
                        <input type="text" id="home_estate" name="home_estate" value="{{ old('home_estate', $candidate->home_estate) }}" 
                               placeholder="e.g., Gakwegori"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('home_estate')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="home_house_number" class="block text-sm font-medium text-gray-700 mb-1">House Number</label>
                        <input type="text" id="home_house_number" name="home_house_number" value="{{ old('home_house_number', $candidate->home_house_number) }}" 
                               placeholder="e.g., 2"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('home_house_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Marital Status & Family -->
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Marital Status & Family</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                <select id="marital_status" name="marital_status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onchange="toggleSpouseFields()">
                    <option value="">Select...</option>
                    <option value="single" {{ old('marital_status', $candidate->marital_status) === 'single' ? 'selected' : '' }}>Single</option>
                    <option value="married" {{ old('marital_status', $candidate->marital_status) === 'married' ? 'selected' : '' }}>Married</option>
                    <option value="divorced" {{ old('marital_status', $candidate->marital_status) === 'divorced' ? 'selected' : '' }}>Divorced</option>
                    <option value="widowed" {{ old('marital_status', $candidate->marital_status) === 'widowed' ? 'selected' : '' }}>Widowed</option>
                </select>
                @error('marital_status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="spouse_fields" style="display: {{ old('marital_status', $candidate->marital_status) === 'married' ? 'block' : 'none' }};">
                <label for="spouse_name" class="block text-sm font-medium text-gray-700 mb-1">Spouse Name</label>
                <input type="text" id="spouse_name" name="spouse_name" value="{{ old('spouse_name', $candidate->spouse_name) }}" 
                       placeholder="e.g., Millicent Gakii"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('spouse_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="spouse_phone_fields" style="display: {{ old('marital_status', $candidate->marital_status) === 'married' ? 'block' : 'none' }};">
                <label for="spouse_phone" class="block text-sm font-medium text-gray-700 mb-1">Spouse Phone Number</label>
                <div class="flex gap-2">
                    <div class="w-32">
                        @include('candidate.biodata._country_code_dropdown', [
                            'name' => 'spouse_phone_country_code',
                            'id' => 'spouse_phone_country_code',
                            'selected' => old('spouse_phone_country_code', $candidate->spouse_phone_country_code ?? '254')
                        ])
                    </div>
                    <div class="flex-1">
                        <input type="tel" id="spouse_phone" name="spouse_phone" value="{{ old('spouse_phone', $candidate->spouse_phone) }}" 
                               placeholder="e.g., 701810171"
                               pattern="[0-9]*" inputmode="numeric"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('spouse_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="number_of_children" class="block text-sm font-medium text-gray-700 mb-1">Number of Children</label>
                <input type="number" id="number_of_children" name="number_of_children" value="{{ old('number_of_children', $candidate->number_of_children ?? 0) }}" 
                       min="0" placeholder="e.g., 1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('number_of_children')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="children_names" class="block text-sm font-medium text-gray-700 mb-1">Name(s) of Children</label>
                <textarea id="children_names" name="children_names" rows="2" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter children names separated by commas">{{ old('children_names', $candidate->children_names) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Separate multiple names with commas</p>
                @error('children_names')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Parents' Information -->
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Parents' Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="father_name" class="block text-sm font-medium text-gray-700 mb-1">Father's Name</label>
                <input type="text" id="father_name" name="father_name" value="{{ old('father_name', $candidate->father_name) }}" 
                       placeholder="e.g., Stanley Mature"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('father_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="father_phone" class="block text-sm font-medium text-gray-700 mb-1">Father's Phone Number</label>
                <div class="flex gap-2">
                    <div class="w-32">
                        @include('candidate.biodata._country_code_dropdown', [
                            'name' => 'father_phone_country_code',
                            'id' => 'father_phone_country_code',
                            'selected' => old('father_phone_country_code', $candidate->father_phone_country_code ?? '254')
                        ])
                    </div>
                    <div class="flex-1">
                        <input type="tel" id="father_phone" name="father_phone" value="{{ old('father_phone', $candidate->father_phone) }}" 
                               placeholder="e.g., 720892135"
                               pattern="[0-9]*" inputmode="numeric"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('father_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="father_county" class="block text-sm font-medium text-gray-700 mb-1">Father's County</label>
                <input type="text" id="father_county" name="father_county" value="{{ old('father_county', $candidate->father_county) }}" 
                       placeholder="e.g., Muranga"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('father_county')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="father_sub_county" class="block text-sm font-medium text-gray-700 mb-1">Father's Sub-County</label>
                <input type="text" id="father_sub_county" name="father_sub_county" value="{{ old('father_sub_county', $candidate->father_sub_county) }}" 
                       placeholder="e.g., Kandara"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('father_sub_county')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="father_ward" class="block text-sm font-medium text-gray-700 mb-1">Father's Ward</label>
                <input type="text" id="father_ward" name="father_ward" value="{{ old('father_ward', $candidate->father_ward) }}" 
                       placeholder="e.g., Ward name"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('father_ward')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-1">Mother's Name</label>
                <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name', $candidate->mother_name) }}" 
                       placeholder="e.g., Gladys Rnamba"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('mother_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="mother_phone" class="block text-sm font-medium text-gray-700 mb-1">Mother's Phone Number</label>
                <div class="flex gap-2">
                    <div class="w-32">
                        @include('candidate.biodata._country_code_dropdown', [
                            'name' => 'mother_phone_country_code',
                            'id' => 'mother_phone_country_code',
                            'selected' => old('mother_phone_country_code', $candidate->mother_phone_country_code ?? '254')
                        ])
                    </div>
                    <div class="flex-1">
                        <input type="tel" id="mother_phone" name="mother_phone" value="{{ old('mother_phone', $candidate->mother_phone) }}" 
                               placeholder="e.g., 727164415"
                               pattern="[0-9]*" inputmode="numeric"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('mother_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Health and Emergency Contact -->
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Health and Emergency Contact</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="health_physical_condition" class="block text-sm font-medium text-gray-700 mb-1">Health/Physical Condition</label>
                <input type="text" id="health_physical_condition" name="health_physical_condition" value="{{ old('health_physical_condition', $candidate->health_physical_condition) }}" 
                       placeholder="e.g., Healthy" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('health_physical_condition')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="blood_group" class="block text-sm font-medium text-gray-700 mb-1">Blood Group</label>
                <input type="text" id="blood_group" name="blood_group" value="{{ old('blood_group', $candidate->blood_group) }}" 
                       placeholder="e.g., O+, A-, B+" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('blood_group')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-1">Person to be Contacted in Case of Emergency</label>
                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $candidate->emergency_contact_name) }}" 
                       placeholder="e.g., Millicent"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('emergency_contact_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact Phone Number</label>
                <div class="flex gap-2">
                    <div class="w-32">
                        @include('candidate.biodata._country_code_dropdown', [
                            'name' => 'emergency_contact_phone_country_code',
                            'id' => 'emergency_contact_phone_country_code',
                            'selected' => old('emergency_contact_phone_country_code', $candidate->emergency_contact_phone_country_code ?? '254')
                        ])
                    </div>
                    <div class="flex-1">
                        <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $candidate->emergency_contact_phone) }}" 
                               placeholder="e.g., 701810171"
                               pattern="[0-9]*" inputmode="numeric"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('emergency_contact_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $candidate->emergency_contact_relationship) }}" 
                       placeholder="e.g., Spouse, Parent, Sibling" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('emergency_contact_relationship')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="medical_conditions" class="block text-sm font-medium text-gray-700 mb-1">Medical Conditions</label>
                <textarea id="medical_conditions" name="medical_conditions" rows="2" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="List any medical conditions or leave blank if none">{{ old('medical_conditions', $candidate->medical_conditions) }}</textarea>
                @error('medical_conditions')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="allergies" class="block text-sm font-medium text-gray-700 mb-1">Allergies</label>
                <textarea id="allergies" name="allergies" rows="2" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="List any allergies or leave blank if none">{{ old('allergies', $candidate->allergies) }}</textarea>
                @error('allergies')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Educational Qualification -->
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Educational Qualification</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="primary_school" class="block text-sm font-medium text-gray-700 mb-1">Primary School</label>
                <input type="text" id="primary_school" name="primary_school" value="{{ old('primary_school', $candidate->primary_school) }}" 
                       placeholder="e.g., Modern Green"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('primary_school')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="primary_graduation_year" class="block text-sm font-medium text-gray-700 mb-1">Year Graduated (Primary)</label>
                <input type="number" id="primary_graduation_year" name="primary_graduation_year" value="{{ old('primary_graduation_year', $candidate->primary_graduation_year) }}" 
                       min="1900" max="{{ date('Y') + 10 }}" placeholder="e.g., 2006"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('primary_graduation_year')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="secondary_school" class="block text-sm font-medium text-gray-700 mb-1">Secondary School</label>
                <input type="text" id="secondary_school" name="secondary_school" value="{{ old('secondary_school', $candidate->secondary_school) }}" 
                       placeholder="e.g., Kamiu Secondary"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('secondary_school')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="secondary_graduation_year" class="block text-sm font-medium text-gray-700 mb-1">Year Graduated (Secondary)</label>
                <input type="number" id="secondary_graduation_year" name="secondary_graduation_year" value="{{ old('secondary_graduation_year', $candidate->secondary_graduation_year) }}" 
                       min="1900" max="{{ date('Y') + 10 }}" placeholder="e.g., 2011"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('secondary_graduation_year')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="university_college" class="block text-sm font-medium text-gray-700 mb-1">University/College</label>
                <input type="text" id="university_college" name="university_college" value="{{ old('university_college', $candidate->university_college) }}" 
                       placeholder="e.g., Mount Kenya University"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('university_college')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="university_graduation_year" class="block text-sm font-medium text-gray-700 mb-1">Year Graduated (University/College)</label>
                <input type="number" id="university_graduation_year" name="university_graduation_year" value="{{ old('university_graduation_year', $candidate->university_graduation_year) }}" 
                       min="1900" max="{{ date('Y') + 10 }}" placeholder="e.g., 2015"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('university_graduation_year')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="professional_qualifications" class="block text-sm font-medium text-gray-700 mb-1">Professional Qualifications (Degree/Diploma)</label>
                <input type="text" id="professional_qualifications" name="professional_qualifications" value="{{ old('professional_qualifications', $candidate->professional_qualifications) }}" 
                       placeholder="e.g., Diploma, Bachelor's Degree" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('professional_qualifications')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Special Skills -->
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Special Skills</h3>
        
        <div>
            <label for="special_skills" class="block text-sm font-medium text-gray-700 mb-1">Special Skills</label>
            <textarea id="special_skills" name="special_skills" rows="4" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="List your special skills, certifications, or competencies">{{ old('special_skills', $candidate->special_skills) }}</textarea>
            @error('special_skills')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Next of Kin -->
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">Next of Kin</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="next_of_kin_name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" id="next_of_kin_name" name="next_of_kin_name" value="{{ old('next_of_kin_name', $candidate->next_of_kin_name) }}" 
                       placeholder="e.g., Full name"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('next_of_kin_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="next_of_kin_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <div class="flex gap-2">
                    <div class="w-32">
                        @include('candidate.biodata._country_code_dropdown', [
                            'name' => 'next_of_kin_phone_country_code',
                            'id' => 'next_of_kin_phone_country_code',
                            'selected' => old('next_of_kin_phone_country_code', $candidate->next_of_kin_phone_country_code ?? '254')
                        ])
                    </div>
                    <div class="flex-1">
                        <input type="tel" id="next_of_kin_phone" name="next_of_kin_phone" value="{{ old('next_of_kin_phone', $candidate->next_of_kin_phone) }}" 
                               placeholder="e.g., 712345678"
                               pattern="[0-9]*" inputmode="numeric"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('next_of_kin_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="next_of_kin_relationship" class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                <input type="text" id="next_of_kin_relationship" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship', $candidate->next_of_kin_relationship) }}" 
                       placeholder="e.g., Spouse, Parent, Sibling" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('next_of_kin_relationship')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="next_of_kin_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea id="next_of_kin_address" name="next_of_kin_address" rows="2" 
                          placeholder="Enter full address"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('next_of_kin_address', $candidate->next_of_kin_address) }}</textarea>
                @error('next_of_kin_address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
        <a href="{{ route('admin.candidates.show', $candidate) }}?tab=biodata" 
           class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
            Cancel
        </a>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
            Save Bio Data
        </button>
    </div>
</form>

<script>
    function toggleSpouseFields() {
        const maritalStatus = document.getElementById('marital_status').value;
        const spouseFields = document.getElementById('spouse_fields');
        const spousePhoneFields = document.getElementById('spouse_phone_fields');
        
        if (maritalStatus === 'married') {
            spouseFields.style.display = 'block';
            spousePhoneFields.style.display = 'block';
        } else {
            spouseFields.style.display = 'none';
            spousePhoneFields.style.display = 'none';
        }
    }
</script>
