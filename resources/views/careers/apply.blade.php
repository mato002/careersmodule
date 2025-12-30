@extends('layouts.website')

@section('title', 'Apply for ' . $job->title . ' - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900 text-white py-12 sm:py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">Apply for {{ $job->title }}</h1>
            <p class="text-lg sm:text-xl text-teal-100">Complete the application form below</p>
        </div>
    </section>

    <!-- Application Form -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50 overflow-x-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8 md:p-10">
                <!-- Progress Indicator -->
                <div class="mb-8 overflow-x-auto">
                    <div class="flex items-center justify-between mb-4 min-w-max sm:min-w-0">
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-teal-800 text-white flex items-center justify-center font-semibold text-xs sm:text-sm" id="step-1-indicator">1</div>
                            <span class="ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-900 whitespace-nowrap">Personal & Education</span>
                        </div>
                        <div class="flex-1 mx-1 sm:mx-2 h-1 bg-gray-200 min-w-[20px] sm:min-w-[40px]">
                            <div class="h-1 bg-teal-800 transition-all duration-300" id="progress-bar-1" style="width: 0%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold text-xs sm:text-sm" id="step-2-indicator">2</div>
                            <span class="ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-600 whitespace-nowrap">Job Questions</span>
                        </div>
                        <div class="flex-1 mx-1 sm:mx-2 h-1 bg-gray-200 min-w-[20px] sm:min-w-[40px]">
                            <div class="h-1 bg-gray-200 transition-all duration-300" id="progress-bar-2" style="width: 0%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold text-xs sm:text-sm" id="step-3-indicator">3</div>
                            <span class="ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-600 whitespace-nowrap">Support Details</span>
                        </div>
                        <div class="flex-1 mx-1 sm:mx-2 h-1 bg-gray-200 min-w-[20px] sm:min-w-[40px]">
                            <div class="h-1 bg-gray-200 transition-all duration-300" id="progress-bar-3" style="width: 0%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold text-xs sm:text-sm" id="step-4-indicator">4</div>
                            <span class="ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-600 whitespace-nowrap">References & Agreement</span>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        <p class="font-semibold mb-1">Please review the highlighted fields and try again.</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('careers.apply.store', $job) }}" method="POST" enctype="multipart/form-data" id="application-form">
                    @csrf

                    <!-- Page 1: Personal & Education -->
                    <div id="page-1" class="form-page">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Personal Information & Education</h2>

                        <div class="space-y-6">
                            <!-- Personal Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                    @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                    <p class="text-sm text-gray-500 mb-2">Include country code (e.g., +254712345678)</p>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                           pattern="^\+[1-9]\d{1,14}$"
                                           placeholder="+254712345678"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                    @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    <p class="text-xs text-gray-500 mt-1" id="phone-hint"></p>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                    @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <!-- Education Details -->
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Education</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="education_level" class="block text-sm font-medium text-gray-700 mb-1">Education Level</label>
                                        <select id="education_level" name="education_level"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            <option value="">Select Education Level</option>
                                            <option value="High School" {{ old('education_level') == 'High School' ? 'selected' : '' }}>High School</option>
                                            <option value="Certificate" {{ old('education_level') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                            <option value="Diploma" {{ old('education_level') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                            <option value="Bachelor's Degree" {{ old('education_level') == "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                                            <option value="Master's Degree" {{ old('education_level') == "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                                            <option value="PhD" {{ old('education_level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                        </select>
                                    </div>

                                    <!-- Conditional fields - shown when education_level is selected -->
                                    <div id="education-details-group" class="hidden md:col-span-2">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                            <div>
                                                <label for="area_of_study" class="block text-sm font-medium text-gray-700 mb-1">Area of Study</label>
                                                <input type="text" id="area_of_study" name="area_of_study" value="{{ old('area_of_study') }}"
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                                       placeholder="e.g., Business Administration">
                                            </div>

                                            <div>
                                                <label for="institution" class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                                                <input type="text" id="institution" name="institution" value="{{ old('institution') }}"
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            </div>

                                            <div>
                                                <label for="education_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                                <select id="education_status" name="education_status"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                                    <option value="">Select Status</option>
                                                    <option value="Completed" {{ old('education_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="In Progress" {{ old('education_status') == 'In Progress' || old('education_status') == 'Ongoing' ? 'selected' : '' }}>In Progress</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Education Years - Conditional based on status -->
                                        <div id="education-years-group" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div id="education-start-year-group">
                                                <label for="education_start_year" class="block text-sm font-medium text-gray-700 mb-1">Start Year</label>
                                                <input type="number" id="education_start_year" name="education_start_year" 
                                                       value="{{ old('education_start_year') }}" min="1950" max="{{ date('Y') + 5 }}"
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                                       placeholder="e.g., 2020">
                                            </div>

                                            <!-- Completed Education - Show End Year -->
                                            <div id="education-completed-group" class="hidden">
                                                <label for="education_end_year" class="block text-sm font-medium text-gray-700 mb-1">Completion Year</label>
                                                <input type="number" id="education_end_year" name="education_end_year" 
                                                       value="{{ old('education_end_year') }}" min="1950" max="{{ date('Y') }}"
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                                       placeholder="e.g., 2024">
                                            </div>

                                            <!-- In Progress - Show Expected Completion -->
                                            <div id="education-expected-group" class="hidden">
                                                <label for="education_expected_completion_year" class="block text-sm font-medium text-gray-700 mb-1">Expected Completion Year</label>
                                                <input type="number" id="education_expected_completion_year" name="education_expected_completion_year" 
                                                       value="{{ old('education_expected_completion_year') }}" min="{{ date('Y') }}" max="{{ date('Y') + 10 }}"
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                                       placeholder="e.g., 2025">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="other_achievements" class="block text-sm font-medium text-gray-700 mb-1">Other Achievements</label>
                                        <textarea id="other_achievements" name="other_achievements" rows="3"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('other_achievements') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Work Experience - Conditional based on education level and status -->
                            <div id="work-experience-section" class="border-t border-gray-200 pt-6 hidden">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Work Experience</h3>
                                
                                <!-- For lower education levels (High School, Certificate) - Show simplified version -->
                                <div id="simple-work-experience" class="hidden">
                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" id="currently_working_simple" name="currently_working" value="1" {{ old('currently_working') ? 'checked' : '' }}
                                                   class="currently-working-checkbox rounded border-gray-300 text-teal-800 focus:ring-teal-800">
                                            <span class="ml-2 text-sm text-gray-700">I am currently working</span>
                                        </label>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div id="simple-current-job-group" class="hidden">
                                            <div>
                                                <label for="current_job_title" class="block text-sm font-medium text-gray-700 mb-1">Current Job Title</label>
                                                <input type="text" id="current_job_title" name="current_job_title" value="{{ old('current_job_title') }}"
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            </div>
                                        </div>

                                        <div class="md:col-span-2">
                                            <label for="other_experiences" class="block text-sm font-medium text-gray-700 mb-1">Work Experience</label>
                                            <p class="text-sm text-gray-500 mb-2">Please list any work experiences, internships, or volunteer work</p>
                                            <textarea id="other_experiences" name="other_experiences" rows="3"
                                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('other_experiences') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- For higher education levels (Diploma, Bachelor's, Master's, PhD) - Show detailed version -->
                                <div id="detailed-work-experience" class="hidden">
                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" id="currently_working_detailed" name="currently_working" value="1" {{ old('currently_working') ? 'checked' : '' }}
                                                   class="currently-working-checkbox rounded border-gray-300 text-teal-800 focus:ring-teal-800">
                                            <span class="ml-2 text-sm text-gray-700">I am currently working</span>
                                        </label>
                                    </div>

                                    <!-- Current Job Fields - Shown when currently_working is checked AND education is completed or higher level -->
                                    <div id="current-job-group" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <label for="current_job_title" class="block text-sm font-medium text-gray-700 mb-1">Current Job Title</label>
                                            <input type="text" id="current_job_title" name="current_job_title" value="{{ old('current_job_title') }}"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                        </div>

                                        <div>
                                            <label for="current_company" class="block text-sm font-medium text-gray-700 mb-1">Current Company</label>
                                            <input type="text" id="current_company" name="current_company" value="{{ old('current_company') }}"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                        </div>

                                        <div class="md:col-span-2">
                                            <label for="duties_and_responsibilities" class="block text-sm font-medium text-gray-700 mb-1">Duties and Responsibilities</label>
                                            <textarea id="duties_and_responsibilities" name="duties_and_responsibilities" rows="4"
                                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('duties_and_responsibilities') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="md:col-span-2">
                                            <label for="other_experiences" class="block text-sm font-medium text-gray-700 mb-1">Other Professional Experiences</label>
                                            <p class="text-sm text-gray-500 mb-2">Please list any other relevant work experiences, internships, or volunteer work</p>
                                            <textarea id="other_experiences" name="other_experiences" rows="3"
                                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('other_experiences') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CV Upload -->
                            <div class="border-t border-gray-200 pt-6">
                                <label for="cv" class="block text-sm font-medium text-gray-700 mb-1">Upload CV <span class="text-red-500">*</span></label>
                                <p class="text-sm text-gray-500 mb-3">Accepted formats: PDF, DOC, DOCX (Max: 5MB)</p>
                                
                                <div class="relative">
                                    <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-800 file:text-white hover:file:bg-teal-900 file:cursor-pointer"
                                           onchange="displayFileName(this)">
                                    @error('cv')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    
                                    <!-- File name display -->
                                    <div id="cv-file-name" class="mt-2 text-sm text-gray-600 hidden">
                                        <div class="flex items-center gap-2 p-2 bg-teal-50 border border-teal-200 rounded-lg">
                                            <svg class="w-5 h-5 text-teal-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span id="cv-file-name-text" class="flex-1"></span>
                                            <button type="button" onclick="clearFileInput()" class="text-red-600 hover:text-red-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="nextPage()" class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold">
                                Next: Job Questions →
                            </button>
                        </div>
                    </div>

                    <!-- Page 2: Job-Related Questions -->
                    <div id="page-2" class="form-page hidden">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Job-Related Questions</h2>

                        <div class="space-y-6">
                            <div>
                                <label for="why_interested" class="block text-sm font-medium text-gray-700 mb-1">Why are you interested in this position? <span class="text-red-500">*</span></label>
                                <p class="text-sm text-gray-500 mb-2">Please explain what attracts you to this role and our company.</p>
                                <textarea id="why_interested" name="why_interested" rows="4" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('why_interested') }}</textarea>
                                @error('why_interested')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="why_good_fit" class="block text-sm font-medium text-gray-700 mb-1">What makes you a good fit for this role? <span class="text-red-500">*</span></label>
                                <p class="text-sm text-gray-500 mb-2">Highlight your relevant skills, experience, and qualities that align with this position.</p>
                                <textarea id="why_good_fit" name="why_good_fit" rows="4" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('why_good_fit') }}</textarea>
                                @error('why_good_fit')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="career_goals" class="block text-sm font-medium text-gray-700 mb-1">What are your career goals? <span class="text-red-500">*</span></label>
                                <p class="text-sm text-gray-500 mb-2">Share your career aspirations and what you hope to achieve professionally.</p>
                                <textarea id="career_goals" name="career_goals" rows="3" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('career_goals') }}</textarea>
                                @error('career_goals')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="salary_expectations" class="block text-sm font-medium text-gray-700 mb-1">Salary Expectations</label>
                                    <p class="text-sm text-gray-500 mb-2">Please indicate your expected salary range (optional)</p>
                                    <input type="text" id="salary_expectations" name="salary_expectations" value="{{ old('salary_expectations') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                           placeholder="e.g., 50,000 - 70,000 KES">
                                    @error('salary_expectations')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="availability_date" class="block text-sm font-medium text-gray-700 mb-1">When can you start? <span class="text-red-500">*</span></label>
                                    <p class="text-sm text-gray-500 mb-2">Your earliest available start date</p>
                                    <input type="date" id="availability_date" name="availability_date" value="{{ old('availability_date') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                    @error('availability_date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div>
                                <label for="relevant_skills" class="block text-sm font-medium text-gray-700 mb-1">Relevant Skills for This Position <span class="text-red-500">*</span></label>
                                <p class="text-sm text-gray-500 mb-2">List the key skills, technologies, or competencies you possess that are relevant to this role.</p>
                                <textarea id="relevant_skills" name="relevant_skills" rows="4" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                          placeholder="e.g., Project Management, Customer Service, Microsoft Office, etc.">{{ old('relevant_skills') }}</textarea>
                                @error('relevant_skills')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevPage()" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                                ← Previous
                            </button>
                            <button type="button" onclick="nextPage()" class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold">
                                Next: Support Details →
                            </button>
                        </div>
                    </div>

                    <!-- Page 3: Support Details -->
                    <div id="page-3" class="form-page hidden">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Additional Information</h2>

                        <div class="space-y-6">
                            <!-- Certifications & Licenses -->
                            <div>
                                <label for="certifications" class="block text-sm font-medium text-gray-700 mb-1">Certifications & Licenses</label>
                                <p class="text-sm text-gray-500 mb-2">List any professional certifications, licenses, or credentials you hold (e.g., CPA, PMP, etc.)</p>
                                <textarea id="certifications" name="certifications" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                          placeholder="e.g., Certified Public Accountant (CPA), Project Management Professional (PMP)">{{ old('certifications') }}</textarea>
                            </div>

                            <!-- Languages -->
                            <div>
                                <label for="languages" class="block text-sm font-medium text-gray-700 mb-1">Languages Spoken</label>
                                <p class="text-sm text-gray-500 mb-2">List languages you speak and your proficiency level (e.g., English - Fluent, Swahili - Native, French - Basic)</p>
                                <textarea id="languages" name="languages" rows="2"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                          placeholder="e.g., English - Fluent, Swahili - Native">{{ old('languages') }}</textarea>
                            </div>

                            <!-- Professional Memberships -->
                            <div>
                                <label for="professional_memberships" class="block text-sm font-medium text-gray-700 mb-1">Professional Memberships</label>
                                <p class="text-sm text-gray-500 mb-2">List any professional associations or organizations you belong to</p>
                                <textarea id="professional_memberships" name="professional_memberships" rows="2"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                          placeholder="e.g., Kenya Institute of Management, Association of Chartered Accountants">{{ old('professional_memberships') }}</textarea>
                            </div>

                            <!-- Awards & Recognition -->
                            <div>
                                <label for="awards_recognition" class="block text-sm font-medium text-gray-700 mb-1">Awards & Recognition</label>
                                <p class="text-sm text-gray-500 mb-2">List any awards, honors, or recognition you have received</p>
                                <textarea id="awards_recognition" name="awards_recognition" rows="2"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                          placeholder="e.g., Employee of the Year 2023, Best Sales Performance Award">{{ old('awards_recognition') }}</textarea>
                            </div>

                            <!-- Portfolio/Additional Links -->
                            <div>
                                <label for="portfolio_links" class="block text-sm font-medium text-gray-700 mb-1">Portfolio or Additional Links</label>
                                <p class="text-sm text-gray-500 mb-2">Share links to your portfolio, LinkedIn profile, GitHub, or other relevant online profiles</p>
                                <textarea id="portfolio_links" name="portfolio_links" rows="2"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                          placeholder="e.g., LinkedIn: https://linkedin.com/in/yourname, Portfolio: https://yourportfolio.com">{{ old('portfolio_links') }}</textarea>
                            </div>

                            <!-- Availability -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="availability_travel" class="block text-sm font-medium text-gray-700 mb-1">Willingness to Travel</label>
                                    <select id="availability_travel" name="availability_travel"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                        <option value="">Select</option>
                                        <option value="Yes" {{ old('availability_travel') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ old('availability_travel') == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Limited" {{ old('availability_travel') == 'Limited' ? 'selected' : '' }}>Limited</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="availability_relocation" class="block text-sm font-medium text-gray-700 mb-1">Willingness to Relocate</label>
                                    <select id="availability_relocation" name="availability_relocation"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                        <option value="">Select</option>
                                        <option value="Yes" {{ old('availability_relocation') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ old('availability_relocation') == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Maybe" {{ old('availability_relocation') == 'Maybe' ? 'selected' : '' }}>Maybe</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Additional Support Details -->
                            <div>
                                <label for="support_details" class="block text-sm font-medium text-gray-700 mb-1">Additional Information</label>
                                <p class="text-sm text-gray-500 mb-2">Please provide any other information that would support your application</p>
                                <textarea id="support_details" name="support_details" rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                          placeholder="Any additional information you'd like to share...">{{ old('support_details') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevPage()" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                                ← Previous
                            </button>
                            <button type="button" onclick="nextPage()" class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold">
                                Next: References & Agreement →
                            </button>
                        </div>
                    </div>

                    <!-- Page 4: References & Agreement -->
                    <div id="page-4" class="form-page hidden">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">References & Agreement</h2>

                        <div class="space-y-6">
                            <!-- References -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">References</h3>
                                <p class="text-sm text-gray-500 mb-4">Please provide at least two professional references</p>
                                
                                <div id="referrers-container">
                                    <div class="referrer-entry mb-4 p-4 border border-gray-200 rounded-lg">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                                <input type="text" name="referrers[0][name]" 
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                                <input type="text" name="referrers[0][position]" 
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                                                <input type="text" name="referrers[0][company]" 
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                                                <input type="text" name="referrers[0][contact]" 
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" onclick="addReferrer()" class="text-teal-800 hover:text-teal-900 font-semibold text-sm">
                                    + Add Another Reference
                                </button>
                            </div>

                            <!-- Notice Period -->
                            <div>
                                <label for="notice_period" class="block text-sm font-medium text-gray-700 mb-1">Notice Period</label>
                                <input type="text" id="notice_period" name="notice_period" value="{{ old('notice_period') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent"
                                       placeholder="e.g., 2 weeks, 1 month">
                            </div>

                            <!-- Application Message -->
                            <div>
                                <label for="application_message" class="block text-sm font-medium text-gray-700 mb-1">Application Message</label>
                                <p class="text-sm text-gray-500 mb-3">Any additional message you'd like to include with your application</p>
                                <textarea id="application_message" name="application_message" rows="5"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">{{ old('application_message') }}</textarea>
                            </div>

                            <!-- Agreement -->
                            <div class="border-t border-gray-200 pt-6">
                                <label class="flex items-start">
                                    <input type="checkbox" id="agreement_accepted" name="agreement_accepted" value="1" required
                                           class="mt-1 rounded border-gray-300 text-teal-800 focus:ring-teal-800">
                                    <span class="ml-2 text-sm text-gray-700">
                                        I confirm that all information provided is accurate and I agree to the terms and conditions <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                @error('agreement_accepted')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevPage()" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                                ← Previous
                            </button>
                            <button type="submit" class="px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold">
                                Submit Application
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        let currentPage = 1;
        let referrerCount = 1;

        // Ensure DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize progress on page load
            updateProgress();
            updateProgressBars();
        });

        function updateProgress() {
            // Update main progress bar
            const progress = ((currentPage - 1) / 3) * 100;
            const progressBar = document.getElementById('progress-bar-1');
            if (progressBar) {
                progressBar.style.width = progress + '%';
                if (currentPage > 1) {
                    progressBar.className = 'h-1 bg-teal-800 transition-all duration-300';
                }
            }

            // Update step indicators
            for (let i = 1; i <= 4; i++) {
                const indicator = document.getElementById(`step-${i}-indicator`);
                if (!indicator) continue;
                
                const parentDiv = indicator.closest('.flex.items-center');
                const label = parentDiv ? parentDiv.querySelector('span') : null;
                
                if (i < currentPage) {
                    // Completed step - show checkmark
                    indicator.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-semibold flex-shrink-0';
                    // Use a checkmark SVG that's more visible
                    indicator.innerHTML = '<svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>';
                    if (label) {
                        label.className = 'ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-900 whitespace-nowrap';
                    }
                } else if (i === currentPage) {
                    // Current step - show number
                    indicator.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-teal-800 text-white flex items-center justify-center font-semibold text-xs sm:text-sm flex-shrink-0';
                    indicator.innerHTML = String(i);
                    if (label) {
                        label.className = 'ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-900 whitespace-nowrap';
                    }
                } else {
                    // Future step - show number in gray
                    indicator.className = 'w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold text-xs sm:text-sm flex-shrink-0';
                    indicator.innerHTML = String(i);
                    if (label) {
                        label.className = 'ml-2 sm:ml-3 text-xs sm:text-sm font-medium text-gray-600 whitespace-nowrap';
                    }
                }
            }
        }

        function showPage(page) {
            for (let i = 1; i <= 4; i++) {
                const pageElement = document.getElementById(`page-${i}`);
                if (pageElement) {
                    pageElement.classList.toggle('hidden', i !== page);
                }
            }
            currentPage = page;
            // Force immediate update
            setTimeout(function() {
                updateProgress();
                updateProgressBars();
            }, 10);
        }

        function nextPage() {
            if (currentPage < 4) {
                // Validate current page before proceeding
                if (validatePage(currentPage)) {
                    showPage(currentPage + 1);
                }
            }
        }

        function prevPage() {
            if (currentPage > 1) {
                showPage(currentPage - 1);
            }
        }

        function validatePage(page) {
            const pageElement = document.getElementById(`page-${page}`);
            const requiredFields = pageElement.querySelectorAll('[required]');
            let isValid = true;
            let errorMessages = [];

            requiredFields.forEach(field => {
                const value = field.value.trim();
                let fieldValid = true;
                let errorMessage = '';

                // Check if field is empty
                if (!value) {
                    field.classList.add('border-red-500');
                    isValid = false;
                    fieldValid = false;
                } else {
                    field.classList.remove('border-red-500');
                    
                    // Validate phone number format
                    if (field.type === 'tel' || field.id === 'phone') {
                        const phonePattern = /^\+[1-9]\d{1,14}$/;
                        if (!phonePattern.test(value)) {
                            field.classList.add('border-red-500');
                            errorMessage = 'Phone number must include country code (e.g., +254712345678)';
                            isValid = false;
                            fieldValid = false;
                        }
                    }
                    
                    // Validate email format
                    if (field.type === 'email') {
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailPattern.test(value)) {
                            field.classList.add('border-red-500');
                            errorMessage = 'Please enter a valid email address';
                            isValid = false;
                            fieldValid = false;
                        }
                    }
                    
                    // Validate name (letters, spaces, hyphens, and apostrophes)
                    if (field.id === 'name') {
                        const namePattern = /^[a-zA-Z\s\-\']+$/;
                        if (!namePattern.test(value)) {
                            field.classList.add('border-red-500');
                            errorMessage = 'Name should only contain letters, spaces, hyphens, and apostrophes';
                            isValid = false;
                            fieldValid = false;
                        }
                    }
                    
                    // Validate date
                    if (field.type === 'date') {
                        const selectedDate = new Date(value);
                        const today = new Date();
                        if (isNaN(selectedDate.getTime())) {
                            field.classList.add('border-red-500');
                            errorMessage = 'Please enter a valid date';
                            isValid = false;
                            fieldValid = false;
                        }
                    }
                }

                if (fieldValid) {
                    field.classList.remove('border-red-500');
                    field.classList.add('border-green-500');
                }
            });

            if (!isValid) {
                const errorMsg = errorMessages.length > 0 
                    ? errorMessages.join('\n') 
                    : 'Please fill in all required fields correctly before proceeding.';
                alert(errorMsg);
            }

            return isValid;
        }

        // Real-time phone validation and conditional fields
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            const phoneHint = document.getElementById('phone-hint');
            
            if (phoneInput) {
                phoneInput.addEventListener('input', function() {
                    const value = this.value.trim();
                    const phonePattern = /^\+[1-9]\d{1,14}$/;
                    
                    if (value === '') {
                        phoneHint.textContent = '';
                        this.classList.remove('border-red-500', 'border-green-500');
                    } else if (phonePattern.test(value)) {
                        phoneHint.textContent = '✓ Valid phone number format';
                        phoneHint.className = 'text-xs text-green-600 mt-1';
                        this.classList.remove('border-red-500');
                        this.classList.add('border-green-500');
                    } else {
                        phoneHint.textContent = 'Phone must start with + and country code (e.g., +254712345678)';
                        phoneHint.className = 'text-xs text-red-600 mt-1';
                        this.classList.remove('border-green-500');
                        this.classList.add('border-red-500');
                    }
                });
            }

            // Education Level - Show/hide education details
            const educationLevel = document.getElementById('education_level');
            const educationDetailsGroup = document.getElementById('education-details-group');
            const educationStatus = document.getElementById('education_status');
            const educationYearsGroup = document.getElementById('education-years-group');
            const educationCompletedGroup = document.getElementById('education-completed-group');
            const educationExpectedGroup = document.getElementById('education-expected-group');
            
            function updateEducationYearFields() {
                if (!educationStatus || !educationYearsGroup) return;
                
                // Only show years if education details are visible
                const educationDetailsVisible = educationDetailsGroup && !educationDetailsGroup.classList.contains('hidden');
                if (!educationDetailsVisible) {
                    if (educationYearsGroup) educationYearsGroup.classList.add('hidden');
                    return;
                }
                
                const status = educationStatus.value;
                const startYearGroup = document.getElementById('education-start-year-group');
                
                if (status === 'Completed') {
                    if (educationYearsGroup) educationYearsGroup.classList.remove('hidden');
                    if (startYearGroup) startYearGroup.classList.remove('hidden');
                    if (educationCompletedGroup) educationCompletedGroup.classList.remove('hidden');
                    if (educationExpectedGroup) educationExpectedGroup.classList.add('hidden');
                } else if (status === 'In Progress') {
                    if (educationYearsGroup) educationYearsGroup.classList.remove('hidden');
                    if (startYearGroup) startYearGroup.classList.remove('hidden');
                    if (educationCompletedGroup) educationCompletedGroup.classList.add('hidden');
                    if (educationExpectedGroup) educationExpectedGroup.classList.remove('hidden');
                } else {
                    if (educationYearsGroup) educationYearsGroup.classList.add('hidden');
                    if (educationCompletedGroup) educationCompletedGroup.classList.add('hidden');
                    if (educationExpectedGroup) educationExpectedGroup.classList.add('hidden');
                }
            }
            
            function toggleEducationDetails() {
                if (educationLevel && educationDetailsGroup) {
                    if (educationLevel.value) {
                        educationDetailsGroup.classList.remove('hidden');
                        // Update year fields after showing details
                        setTimeout(updateEducationYearFields, 100);
                        // Update work experience section
                        updateWorkExperienceSection();
                    } else {
                        educationDetailsGroup.classList.add('hidden');
                        if (educationYearsGroup) educationYearsGroup.classList.add('hidden');
                        // Hide work experience section
                        const workExpSection = document.getElementById('work-experience-section');
                        if (workExpSection) workExpSection.classList.add('hidden');
                    }
                }
            }
            
            // Work Experience - Conditional based on education level and status
            function updateWorkExperienceSection() {
                const workExpSection = document.getElementById('work-experience-section');
                const simpleWorkExp = document.getElementById('simple-work-experience');
                const detailedWorkExp = document.getElementById('detailed-work-experience');
                
                if (!educationLevel || !workExpSection) return;
                
                const eduLevel = educationLevel.value;
                const eduStatus = educationStatus ? educationStatus.value : '';
                
                // Only show work experience if education level is selected
                if (!eduLevel) {
                    workExpSection.classList.add('hidden');
                    return;
                }
                
                workExpSection.classList.remove('hidden');
                
                // Lower education levels (High School, Certificate) - Show simple version
                if (eduLevel === 'High School' || eduLevel === 'Certificate') {
                    if (simpleWorkExp) simpleWorkExp.classList.remove('hidden');
                    if (detailedWorkExp) detailedWorkExp.classList.add('hidden');
                    
                    // For simple version, show current job if currently working is checked
                    const currentlyWorkingSimple = document.getElementById('currently_working_simple');
                    const simpleCurrentJob = document.getElementById('simple-current-job-group');
                    if (currentlyWorkingSimple && simpleCurrentJob) {
                        if (currentlyWorkingSimple.checked) {
                            simpleCurrentJob.classList.remove('hidden');
                        } else {
                            simpleCurrentJob.classList.add('hidden');
                        }
                    }
                } 
                // Higher education levels (Diploma, Bachelor's, Master's, PhD) - Show detailed version
                else if (eduLevel === 'Diploma' || eduLevel === "Bachelor's Degree" || 
                         eduLevel === "Master's Degree" || eduLevel === 'PhD') {
                    if (simpleWorkExp) simpleWorkExp.classList.add('hidden');
                    if (detailedWorkExp) detailedWorkExp.classList.remove('hidden');
                    
                    // For detailed version, show current job fields if currently working is checked
                    // But only if education is completed or if status is not "In Progress"
                    const currentlyWorkingDetailed = document.getElementById('currently_working_detailed');
                    const currentJobGroup = document.getElementById('current-job-group');
                    
                    if (currentlyWorkingDetailed && currentJobGroup) {
                        // Show current job fields if:
                        // 1. Currently working is checked AND
                        // 2. Education is completed OR education status is empty (not in progress)
                        if (currentlyWorkingDetailed.checked && (eduStatus === 'Completed' || !eduStatus || eduStatus === '')) {
                            currentJobGroup.classList.remove('hidden');
                        } else {
                            currentJobGroup.classList.add('hidden');
                        }
                    }
                }
                
                // Call toggle function to ensure state is correct
                setTimeout(toggleCurrentJobFields, 100);
            }
            
            if (educationLevel) {
                educationLevel.addEventListener('change', function() {
                    toggleEducationDetails();
                    updateWorkExperienceSection();
                });
                toggleEducationDetails(); // Initial check
                // Also update work experience on initial load
                setTimeout(updateWorkExperienceSection, 200);
            }
            
            if (educationStatus) {
                educationStatus.addEventListener('change', function() {
                    updateEducationYearFields();
                    updateWorkExperienceSection();
                });
                updateEducationYearFields(); // Initial check
                // Also update work experience on initial load
                setTimeout(updateWorkExperienceSection, 200);
            }
            
            // Initial work experience update
            setTimeout(updateWorkExperienceSection, 300);

            // Currently Working - Show/hide current job fields (works for both simple and detailed)
            const currentJobGroup = document.getElementById('current-job-group');
            const simpleCurrentJob = document.getElementById('simple-current-job-group');
            
            function toggleCurrentJobFields() {
                // Find the visible checkbox (either simple or detailed)
                const currentlyWorkingSimple = document.getElementById('currently_working_simple');
                const currentlyWorkingDetailed = document.getElementById('currently_working_detailed');
                const simpleWorkExp = document.getElementById('simple-work-experience');
                const detailedWorkExp = document.getElementById('detailed-work-experience');
                
                // Determine which checkbox is visible and get its checked state
                let isChecked = false;
                let currentlyWorking = null;
                
                if (simpleWorkExp && !simpleWorkExp.classList.contains('hidden') && currentlyWorkingSimple) {
                    currentlyWorking = currentlyWorkingSimple;
                    isChecked = currentlyWorking.checked;
                } else if (detailedWorkExp && !detailedWorkExp.classList.contains('hidden') && currentlyWorkingDetailed) {
                    currentlyWorking = currentlyWorkingDetailed;
                    isChecked = currentlyWorking.checked;
                }
                
                if (!currentlyWorking) return;
                
                const eduLevel = educationLevel ? educationLevel.value : '';
                const eduStatus = educationStatus ? educationStatus.value : '';
                
                // For simple version (High School, Certificate)
                if (eduLevel === 'High School' || eduLevel === 'Certificate') {
                    if (simpleCurrentJob) {
                        if (isChecked) {
                            simpleCurrentJob.classList.remove('hidden');
                        } else {
                            simpleCurrentJob.classList.add('hidden');
                        }
                    }
                }
                // For detailed version (Diploma, Bachelor's, Master's, PhD)
                else if (eduLevel === 'Diploma' || eduLevel === "Bachelor's Degree" || 
                         eduLevel === "Master's Degree" || eduLevel === 'PhD') {
                    if (currentJobGroup) {
                        // Show if checked AND (completed OR no status selected)
                        if (isChecked && (eduStatus === 'Completed' || !eduStatus || eduStatus === '')) {
                            currentJobGroup.classList.remove('hidden');
                        } else {
                            currentJobGroup.classList.add('hidden');
                        }
                    }
                }
            }
            
            // Add event listeners to both checkboxes
            const currentlyWorkingSimple = document.getElementById('currently_working_simple');
            const currentlyWorkingDetailed = document.getElementById('currently_working_detailed');
            
            if (currentlyWorkingSimple) {
                currentlyWorkingSimple.addEventListener('change', toggleCurrentJobFields);
            }
            
            if (currentlyWorkingDetailed) {
                currentlyWorkingDetailed.addEventListener('change', toggleCurrentJobFields);
            }
            
            // Also call on initial load
            setTimeout(toggleCurrentJobFields, 400);
        });

        function addReferrer() {
            const container = document.getElementById('referrers-container');
            const newEntry = document.createElement('div');
            newEntry.className = 'referrer-entry mb-4 p-4 border border-gray-200 rounded-lg';
            newEntry.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-gray-900">Reference ${referrerCount + 1}</h4>
                    <button type="button" onclick="removeReferrer(this)" class="text-red-600 hover:text-red-800 text-sm font-semibold">Remove</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="referrers[${referrerCount}][name]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <input type="text" name="referrers[${referrerCount}][position]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                        <input type="text" name="referrers[${referrerCount}][company]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                        <input type="text" name="referrers[${referrerCount}][contact]" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent">
                    </div>
                </div>
            `;
            container.appendChild(newEntry);
            referrerCount++;
        }

        function removeReferrer(button) {
            button.closest('.referrer-entry').remove();
        }

        // CV File Upload Functions
        function displayFileName(input) {
            const fileNameDiv = document.getElementById('cv-file-name');
            const fileNameText = document.getElementById('cv-file-name-text');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size exceeds 5MB. Please upload a smaller file.');
                    input.value = '';
                    fileNameDiv.classList.add('hidden');
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid file type. Please upload a PDF, DOC, or DOCX file.');
                    input.value = '';
                    fileNameDiv.classList.add('hidden');
                    return;
                }
                
                fileNameText.textContent = `${fileName} (${fileSize} MB)`;
                fileNameDiv.classList.remove('hidden');
            } else {
                fileNameDiv.classList.add('hidden');
            }
        }

        function clearFileInput() {
            const fileInput = document.getElementById('cv');
            const fileNameDiv = document.getElementById('cv-file-name');
            fileInput.value = '';
            fileNameDiv.classList.add('hidden');
        }

        // Update progress bars between steps
        function updateProgressBars() {
            for (let i = 1; i < 4; i++) {
                const progressBar = document.getElementById(`progress-bar-${i}`);
                if (progressBar) {
                    if (i < currentPage) {
                        // Completed - show full green bar
                        progressBar.className = 'h-1 bg-teal-800 transition-all duration-300';
                        progressBar.style.width = '100%';
                    } else if (i === currentPage - 1) {
                        // Current step - show partial progress
                        progressBar.className = 'h-1 bg-teal-800 transition-all duration-300';
                        progressBar.style.width = '50%';
                    } else {
                        // Future step - show gray
                        progressBar.className = 'h-1 bg-gray-200 transition-all duration-300';
                        progressBar.style.width = '0%';
                    }
                }
            }
        }

        // Initialize on page load (after DOM is ready)
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                updateProgress();
                updateProgressBars();
            });
        } else {
            // DOM is already ready
            updateProgress();
            updateProgressBars();
        }
    </script>
    @endpush
@endsection

