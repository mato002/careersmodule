@extends('layouts.website')

@section('title', 'Home - Fortress Lenders Ltd')

@section('meta_description', 'Fortress Lenders Ltd offers accessible microfinance and microcredit services across Kenya, empowering individuals and businesses with flexible loan products.')
@section('meta_keywords', 'Fortress Lenders, microfinance Kenya, loans Nakuru, business loans, group loans, emergency loans')

@section('content')
@php use Illuminate\Support\Str; @endphp
    <!-- Hero Section -->
    <section
        class="relative text-white overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900"
    >
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0 hidden md:block">
            <div class="absolute top-0 left-0 w-72 h-72 bg-teal-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 right-0 w-72 h-72 bg-amber-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-0 left-1/2 w-72 h-72 bg-teal-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 py-16 sm:py-20 md:py-24 lg:py-32">
            <div class="text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4 sm:mb-6 animate-fade-in-up leading-tight">
                <span class="hero-word-animate-block">WELCOME TO</span><br>
                <span class="text-amber-400 hero-word-animate-main">FORTRESS LENDERS LTD</span>
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl mb-6 sm:mb-8 text-teal-100 animate-fade-in-up animation-delay-200 px-2">
                The Force Of Possibilities!
            </p>
            <p class="text-base sm:text-lg md:text-xl mb-8 sm:mb-10 max-w-3xl mx-auto text-teal-50 animate-fade-in-up animation-delay-400 px-4">
                    Empowering communities through accessible financial solutions. We enable people to achieve their dreams through customer-centric microfinance and microcredit services.
            </p>
            
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 sm:py-16 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
                <div class="text-center animate-fade-in">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800 mb-1 sm:mb-2">3000+</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Active Clients</div>
                </div>
                <div class="text-center animate-fade-in animation-delay-200">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800 mb-1 sm:mb-2">5</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Branch Locations</div>
                </div>
                <div class="text-center animate-fade-in animation-delay-400">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800 mb-1 sm:mb-2">2019</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Established</div>
                </div>
                <div class="text-center animate-fade-in animation-delay-600">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800 mb-1 sm:mb-2">100%</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Customer Focused</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Overview -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50" id="services">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="text-center mb-8 sm:mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 px-4">Our Services</h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                    Comprehensive career solutions designed to connect talent with opportunities
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto">
                <!-- Career Opportunities Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all transform hover:-translate-y-2 animate-fade-in-up">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center mb-4 sm:mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Job Opportunities</h3>
                    <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">Explore diverse career opportunities across various industries and find your perfect match.</p>
                    <a href="{{ route('careers.index') }}" class="text-teal-800 font-semibold hover:text-teal-700 inline-flex items-center">
                        View Jobs
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <!-- Career Development Card -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all transform hover:-translate-y-2 animate-fade-in-up animation-delay-400">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Career Development</h3>
                    <p class="text-gray-600 mb-6">Professional growth programs and resources to help you advance in your career journey.</p>
                    <a href="{{ route('about') }}" class="text-blue-900 font-semibold hover:text-blue-700 inline-flex items-center">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Preview Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white" id="about-preview">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-10 md:gap-12 items-center">
                <div class="animate-fade-in px-4 lg:px-0">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4 sm:mb-6">About Fortress Lenders</h2>
                    <p class="text-base sm:text-lg text-gray-600 mb-3 sm:mb-4">
                        Fortress Lenders Ltd is a leading recruitment and career development platform in Kenya, established in 2019. We specialize in connecting talented professionals with exceptional career opportunities.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Our mission is to provide comprehensive career solutions aimed at empowering individuals and organizations, creating meaningful connections between talent and opportunity, and fostering professional growth across all industries.
                    </p>
                    <a href="{{ route('about') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-teal-800 to-teal-700 text-white rounded-lg font-semibold hover:from-teal-700 hover:to-teal-600 transition-all transform hover:scale-105">
                    Learn More About Us
                </a>
            </div>
                <div class="animate-fade-in animation-delay-200">
                <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-2xl p-6 sm:p-8 shadow-lg mx-4 lg:mx-0">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Our Core Values</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                            <svg class="w-6 h-6 text-teal-800 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                                <span class="text-gray-700"><strong>Integrity:</strong> We operate with honesty and transparency</span>
                    </li>
                    <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                                <span class="text-gray-700"><strong>Excellence:</strong> We strive for the highest standards</span>
                    </li>
                    <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                                <span class="text-gray-700"><strong>Prudence:</strong> We manage resources wisely</span>
                    </li>
                    <li class="flex items-start">
                                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                                <span class="text-gray-700"><strong>Commitment:</strong> We are dedicated to our clients</span>
                    </li>
                    <li class="flex items-start">
                                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                                <span class="text-gray-700"><strong>Teamwork:</strong> We work together for success</span>
                    </li>
                </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Team Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50" id="team">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="text-center mb-8 sm:mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 px-4">Meet Our Team</h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                    The people behind Fortress Lenders who keep branches running and customers supported every day.
                </p>
            </div>
            @if(isset($teamMembers) && $teamMembers->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach ($teamMembers as $member)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:-translate-y-1 transition">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gradient-to-br from-teal-600 to-teal-800 text-white flex items-center justify-center text-xl font-semibold">
                                    @if ($member->photo_path)
                                        <img src="{{ asset('storage/'.$member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-lg font-semibold text-gray-900 break-words">{{ ucwords(strtolower($member->name)) }}</p>
                                    <p class="text-sm text-teal-700 break-words line-clamp-2">{{ $member->role }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3 leading-relaxed">
                                @if($member->bio && strlen($member->bio) > 160)
                                    {{ Str::words($member->bio, 25, '...') }}
                                @else
                                    {{ $member->bio ?? '' }}
                                @endif
                            </p>
                            @if($member->bio && strlen(trim($member->bio)) > 160)
                                <button 
                                    class="w-full mt-2 px-4 py-2 bg-teal-50 border border-teal-200 text-teal-700 hover:bg-teal-100 hover:border-teal-300 font-semibold text-sm rounded-lg cursor-pointer transition-colors"
                                    data-team-member
                                    data-id="{{ $member->id }}"
                                    data-name="{{ $member->name }}"
                                    data-role="{{ $member->role }}"
                                    data-bio="{{ $member->bio }}"
                                    data-photo="{{ $member->photo_path ? asset('storage/'.$member->photo_path) : '' }}"
                                    data-email="{{ $member->email ?? '' }}"
                                    data-phone="{{ $member->phone ?? '' }}"
                                    data-linkedin="{{ $member->linkedin_url ?? '' }}">
                                    Read Full Bio →
                                </button>
                            @endif
                            <div class="space-y-1 text-sm text-gray-600">
                                @if ($member->email)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <a href="mailto:{{ $member->email }}" class="hover:text-teal-700">{{ $member->email }}</a>
                                    </div>
                                @endif
                                @if ($member->phone)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2H6a3 3 0 01-3-3V5z"/></svg>
                                        <a href="tel:{{ preg_replace('/\s+/', '', $member->phone) }}" class="hover:text-teal-700">{{ $member->phone }}</a>
                                    </div>
                                @endif
                                @if ($member->linkedin_url)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-teal-700" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><path d="M2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                                        <a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener" class="hover:text-teal-700">LinkedIn</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 bg-white border border-dashed border-gray-200 rounded-2xl py-12">
                    Team profiles are being updated. Check back soon.
                </div>
            @endif
        </div>
    </section>

    <!-- Team Member Modal -->
    <div id="teamModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background overlay - transparent with blur effect -->
        <div class="fixed inset-0 transition-opacity z-40" onclick="closeTeamModal()" style="backdrop-filter: blur(10px) saturate(180%); -webkit-backdrop-filter: blur(10px) saturate(180%); pointer-events: auto;"></div>
        
        <!-- Modal container -->
        <div class="relative flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0 z-50">
            <!-- Modal panel -->
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                        <!-- Smaller photo on left -->
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            <div id="modalPhoto" class="w-20 h-20 sm:w-16 sm:h-16 rounded-full overflow-hidden bg-gradient-to-br from-teal-600 to-teal-800 text-white flex items-center justify-center text-lg font-semibold border-2 border-gray-100">
                                <!-- Photo will be inserted here -->
                            </div>
                        </div>
                        <!-- Content on right -->
                        <div class="flex-1 min-w-0 text-center sm:text-left">
                            <h3 id="modalName" class="text-xl sm:text-2xl font-bold text-gray-900 mb-1 break-words"></h3>
                            <p id="modalRole" class="text-base text-teal-700 mb-3 break-words"></p>
                            <div id="modalBio" class="text-sm sm:text-base text-gray-600 leading-relaxed mb-4 break-words whitespace-normal"></div>
                            <div id="modalContact" class="space-y-2 text-sm text-gray-600">
                                <!-- Contact info will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeTeamModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

     <!-- Testimonials Section -->
     <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-br from-gray-900 to-gray-800 text-white" id="testimonials">
         <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
             <div class="text-center mb-8 sm:mb-12 md:mb-16">
                 <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-3 sm:mb-4 px-4 text-amber-400">What Our Clients Say</h2>
                 <p class="text-base sm:text-lg text-gray-200 max-w-2xl mx-auto px-4">
                     Testimonials from our satisfied clients across Nakuru, Gilgil, Olkalou, Nyahururu, and Rumuruti
                 </p>
             </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="bg-gray-800 rounded-xl p-6 sm:p-8 hover:bg-gray-700 transition-all animate-fade-in-up">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">JM</span>
                        </div>
                        <div>
                            <div class="font-semibold">Jane Muthoni</div>
                            <div class="text-sm text-gray-400">Software Developer</div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic">"Fortress Lenders helped me find my dream job. Their recruitment process was smooth, and the career support services were invaluable. I'm grateful for their assistance!"</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-8 hover:bg-gray-700 transition-all animate-fade-in-up animation-delay-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">PK</span>
                        </div>
                        <div>
                            <div class="font-semibold">Peter Kariuki</div>
                            <div class="text-sm text-gray-400">Marketing Manager</div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic">"The career opportunities helped me advance in my field. Fortress Lenders made the application process smooth, and their customer service is excellent. I'm grateful for their support!"</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-8 hover:bg-gray-700 transition-all animate-fade-in-up animation-delay-400">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">AW</span>
                        </div>
                        <div>
                            <div class="font-semibold">Ann Wanjiru</div>
                            <div class="text-sm text-gray-400">HR Specialist</div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic">"The career platform transformed my professional journey. The user-friendly interface and excellent support make Fortress Lenders my preferred career partner."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-r from-teal-800 to-teal-700 text-white" id="careers">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6 px-4">Ready to Get Started?</h2>
            <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 text-teal-100 max-w-2xl mx-auto px-4">
                Whether you're looking for your next career opportunity or seeking talented professionals, we're here to help you achieve your goals.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
                <a href="mailto:{{ $generalSettings->company_email ?? 'info@example.com' }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-white text-teal-800 rounded-lg font-semibold hover:bg-teal-50 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    Contact Us Today
                </a>
                <a href="{{ route('careers.index') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-amber-500 text-white rounded-lg font-semibold hover:bg-amber-600 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    View Job Opportunities
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Add click handlers to all "Read More" buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-team-member]').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const role = this.getAttribute('data-role');
                const bio = this.getAttribute('data-bio');
                const photoPath = this.getAttribute('data-photo');
                const email = this.getAttribute('data-email');
                const phone = this.getAttribute('data-phone');
                const linkedinUrl = this.getAttribute('data-linkedin');
                
                openTeamModal(id, name, role, bio, photoPath, email, phone, linkedinUrl);
            });
        });
    });
    
    function openTeamModal(id, name, role, bio, photoPath, email, phone, linkedinUrl) {
        const modal = document.getElementById('teamModal');
        const modalName = document.getElementById('modalName');
        const modalRole = document.getElementById('modalRole');
        const modalBio = document.getElementById('modalBio');
        const modalPhoto = document.getElementById('modalPhoto');
        const modalContact = document.getElementById('modalContact');
        
        // Set name and role with proper formatting
        modalName.textContent = name;
        modalRole.textContent = role;
        // Set bio with proper text wrapping
        modalBio.textContent = bio;
        modalBio.style.wordWrap = 'break-word';
        modalBio.style.overflowWrap = 'break-word';
        
        // Set photo or initials
        if (photoPath) {
            modalPhoto.innerHTML = `<img src="${photoPath}" alt="${name}" class="w-full h-full object-cover">`;
        } else {
            modalPhoto.textContent = name.substring(0, 2).toUpperCase();
        }
        
        // Build contact info
        let contactHtml = '';
        if (email) {
            contactHtml += `
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <a href="mailto:${email}" class="hover:text-teal-700">${email}</a>
                </div>
            `;
        }
        if (phone) {
            const phoneClean = phone.replace(/\s+/g, '');
            contactHtml += `
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2H6a3 3 0 01-3-3V5z"/>
                    </svg>
                    <a href="tel:${phoneClean}" class="hover:text-teal-700">${phone}</a>
                </div>
            `;
        }
        if (linkedinUrl) {
            contactHtml += `
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-700" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/>
                        <path d="M2 9h4v12H2z"/>
                        <circle cx="4" cy="4" r="2"/>
                    </svg>
                    <a href="${linkedinUrl}" target="_blank" rel="noopener" class="hover:text-teal-700">LinkedIn</a>
                </div>
            `;
        }
        modalContact.innerHTML = contactHtml || '<p class="text-gray-400">No contact information available</p>';
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
    
    function closeTeamModal() {
        const modal = document.getElementById('teamModal');
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeTeamModal();
        }
    });
</script>
@endpush

@push('styles')
<style>
    @keyframes blob {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }

    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out forwards;
        opacity: 0;
    }
    
    .animation-delay-200 {
        animation-delay: 0.2s;
    }
    
    .animation-delay-400 {
        animation-delay: 0.4s;
    }
    
    .animation-delay-600 {
        animation-delay: 0.6s;
    }

    @keyframes fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    .animate-fade-in {
        animation: fade-in 1s ease-out forwards;
        opacity: 0;
    }

    @keyframes hero-word-pulse {
        0%, 100% {
            transform: translateY(0);
            letter-spacing: 0.05em;
        }
        50% {
            transform: translateY(-4px);
            letter-spacing: 0.18em;
        }
    }

    .hero-word-animate-main {
        display: inline-block;
        animation: hero-word-pulse 2.8s ease-in-out infinite;
    }

    .hero-word-animate-block {
        display: inline-block;
        animation: hero-word-pulse 3.2s ease-in-out infinite;
        animation-delay: 0.15s;
    }
</style>
@endpush
