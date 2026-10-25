@extends('layouts.app')

@section('title', 'Barangay San Agustin - Community Focus Systems')

@section('body-class', 'bg-gray-50 text-gray-800')

@push('styles')
<style>
    /* Input icon styles */
    .input-icon {
        position: relative;
    }
    .input-icon i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #1e40af;
        font-size: 1rem;
    }
    .input-icon input,
    .input-icon select {
        padding-left: 2.8rem;
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.8rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        transition: all 0.3s;
        background-color: #f9fafb;
    }
    .input-icon input:focus,
    .input-icon select:focus {
        outline: none;
        border-color: #1e40af;
        box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        background-color: white;
    }

    #aiAssistant {
        position: fixed !important;
        bottom: 6rem !important;
        right: 1.5rem !important;
        top: auto !important;
        left: auto !important;
    }

    .ai-float-btn {
        position: fixed !important;
        bottom: 1.5rem !important;
        right: 1.5rem !important;
    }
</style>
@endpush

@section('content')
    <!-- Navigation Bar -->
    <nav class="navbar text-white py-4 px-6 shadow-lg sticky top-0 z-40" style="background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('1.jpg') }}" alt="Barangay Logo" class="w-10 h-10 rounded-full border-2 border-white mr-3 object-cover shadow-sm">
                <div>
                    <h1 class="text-base md:text-xl font-bold text-white tracking-wide">Barangay San Agustin</h1>
                    <p class="text-xs md:text-sm text-blue-100 hidden sm:block">Community-Focused Systems</p>
                </div>
            </div>
            
            <!-- Hamburger Menu Button -->
            <button type="button" id="hamburger" aria-label="Toggle navigation menu" class="md:hidden text-white text-2xl p-2 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer flex items-center justify-center transition">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex md:flex-row items-center space-x-1 lg:space-x-4 text-sm lg:text-base">
                <a href="#" class="nav-link text-white font-medium px-3 py-2 rounded-lg hover:bg-white/15 transition">Home</a>
                <a href="#services" class="nav-link text-white font-medium px-3 py-2 rounded-lg hover:bg-white/15 transition">Services</a>
                <a href="#officials" class="nav-link text-white font-medium px-3 py-2 rounded-lg hover:bg-white/15 transition">Officials</a>
                <a href="#news" class="nav-link text-white font-medium px-3 py-2 rounded-lg hover:bg-white/15 transition">News & Events</a>
                <a href="#contact" class="nav-link text-white font-medium px-3 py-2 rounded-lg hover:bg-white/15 transition">Contact</a>
                <div class="flex items-center space-x-2 ml-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-lg transition text-sm font-semibold flex items-center gap-2 shadow">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg transition text-sm cursor-pointer shadow">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    @else
                        <button type="button" onclick="openLoginModal()" class="bg-blue-700 hover:bg-blue-800 text-white px-3.5 py-2 rounded-lg transition text-sm font-semibold cursor-pointer shadow-sm flex items-center gap-1.5">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                        <button type="button" onclick="openRegisterModal()" class="btn-register bg-white text-blue-700 hover:bg-blue-50 px-3.5 py-2 rounded-lg transition text-sm font-bold cursor-pointer shadow flex items-center gap-1.5">
                            <i class="fas fa-user-plus text-blue-700" style="color: #1d4ed8 !important;"></i> Register
                        </button>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation Menu -->
        <div class="md:hidden hidden bg-blue-900/95 backdrop-blur-md rounded-xl mt-3 p-3 shadow-xl border border-blue-700/50" id="mobileMenu">
            <div class="flex flex-col space-y-1">
                <a href="#" class="block px-4 py-2.5 rounded-lg text-white font-medium hover:bg-white/10 transition text-sm">Home</a>
                <a href="#services" class="block px-4 py-2.5 rounded-lg text-white font-medium hover:bg-white/10 transition text-sm">Services</a>
                <a href="#officials" class="block px-4 py-2.5 rounded-lg text-white font-medium hover:bg-white/10 transition text-sm">Officials</a>
                <a href="#news" class="block px-4 py-2.5 rounded-lg text-white font-medium hover:bg-white/10 transition text-sm">News & Events</a>
                <a href="#contact" class="block px-4 py-2.5 rounded-lg text-white font-medium hover:bg-white/10 transition text-sm">Contact</a>
                <div class="flex flex-col space-y-2 pt-3 mt-1 border-t border-blue-700/60">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-center bg-blue-600 text-white px-4 py-2.5 rounded-lg transition text-sm font-semibold shadow">
                            Resident Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-center bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg transition text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <button type="button" onclick="openLoginModal()" class="w-full text-center bg-blue-700 hover:bg-blue-800 text-white px-4 py-2.5 rounded-lg transition text-sm cursor-pointer font-medium flex items-center justify-center gap-1.5 shadow">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                        <button type="button" onclick="openRegisterModal()" class="btn-register w-full text-center bg-white text-blue-700 hover:bg-blue-50 px-4 py-2.5 rounded-lg transition text-sm cursor-pointer font-bold shadow flex items-center justify-center gap-1.5">
                            <i class="fas fa-user-plus text-blue-700" style="color: #1d4ed8 !important;"></i> Register
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative py-8 md:py-12 px-4 md:px-6 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=1200" 
                 class="w-full h-full object-cover"
                 alt="Barangay community">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 to-blue-700/70"></div>
        </div>
        
        <div class="container mx-auto relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-0">
                <div class="lg:w-2/3 w-full text-white">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('1.jpg') }}" alt="Barangay Logo" class="w-16 h-16 rounded-full border-3 border-white mr-4 object-cover shadow-lg">
                        <h2 class="text-3xl md:text-4xl font-bold drop-shadow-lg">Community-Focused & Social Impact Systems</h2>
                    </div>
                    <p class="text-base md:text-lg text-blue-50 mb-6 md:mb-8 drop-shadow">Integrated platform for resident records, disaster relief, health monitoring, job matching, and scholarship assistance.</p>
                    
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-6 rounded-lg shadow-lg text-center text-sm md:text-base transition transform hover:scale-105">
                                <i class="fas fa-columns mr-2"></i>Enter Resident Hub
                            </a>
                        @else
                            <a href="javascript:void(0)" onclick="openRegisterModal()" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-6 rounded-lg shadow-lg text-center text-sm md:text-base transition transform hover:scale-105 cursor-pointer">
                                <i class="fas fa-file-alt mr-2"></i>Request Services & Register
                            </a>
                        @endauth
                        <a href="javascript:void(0)" onclick="openEmergencyModal()" class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg shadow-lg text-center transition text-sm md:text-base transform hover:scale-105 cursor-pointer">
                            <i class="fas fa-phone-alt mr-2"></i>Emergency Hotline
                        </a>
                    </div>
                </div>
                
                <div class="lg:w-1/3 w-full">
                    <div class="bg-white/95 backdrop-blur-sm p-6 md:p-8 rounded-xl shadow-2xl">
                        <div class="flex items-center mb-6">
                            <img src="{{ asset('1.jpg') }}" alt="Logo" class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-blue-600">
                            <h3 class="text-lg md:text-xl font-bold text-blue-900">Community Impact Stats</h3>
                        </div>
                        <div class="space-y-4 md:space-y-6">
                            <div class="flex justify-between items-center pb-3 md:pb-4 border-b border-blue-100">
                                <div class="flex items-center gap-3 md:gap-4">
                                    <div class="bg-blue-100 p-2 md:p-3 rounded-lg">
                                        <i class="fas fa-address-card text-blue-700 text-lg md:text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">Residents</p>
                                        <p class="text-xl md:text-2xl font-bold text-blue-800">{{ number_format($stats['total_residents'] + 12840) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pb-3 md:pb-4 border-b border-blue-100">
                                <div class="flex items-center gap-3 md:gap-4">
                                    <div class="bg-red-100 p-2 md:p-3 rounded-lg">
                                        <i class="fas fa-hand-holding-heart text-red-600 text-lg md:text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">Relief Operations</p>
                                        <p class="text-xl md:text-2xl font-bold text-red-600">5 Active</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pb-3 md:pb-4 border-b border-blue-100">
                                <div class="flex items-center gap-3 md:gap-4">
                                    <div class="bg-green-100 p-2 md:p-3 rounded-lg">
                                        <i class="fas fa-heartbeat text-green-600 text-lg md:text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">Health Records</p>
                                        <p class="text-xl md:text-2xl font-bold text-green-600">892 Logged</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pb-3 md:pb-4 border-b border-blue-100">
                                <div class="flex items-center gap-3 md:gap-4">
                                    <div class="bg-purple-100 p-2 md:p-3 rounded-lg">
                                        <i class="fas fa-briefcase text-purple-600 text-lg md:text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">Job Openings</p>
                                        <p class="text-xl md:text-2xl font-bold text-purple-600">12 Openings</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3 md:gap-4">
                                    <div class="bg-yellow-100 p-2 md:p-3 rounded-lg">
                                        <i class="fas fa-graduation-cap text-yellow-600 text-lg md:text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">Scholarships</p>
                                        <p class="text-xl md:text-2xl font-bold text-yellow-600">28 Available</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5 Focus Areas Section -->
    <section id="services" class="py-12 md:py-16 px-4 md:px-6 bg-white relative">
        <div class="container mx-auto relative z-10">
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('1.jpg') }}" alt="Logo" class="w-16 h-16 rounded-full mr-3 object-cover border-2 border-blue-600">
                <h2 class="text-2xl md:text-3xl font-bold text-center text-blue-900">Community-Focused Systems</h2>
            </div>
            <p class="text-center text-gray-600 mb-8 max-w-3xl mx-auto">A barangay-level information system for resident records, services, and announcements</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6">
                <!-- Focus 1: Resident Records -->
                <div class="bg-gradient-to-br from-blue-50 to-white p-5 md:p-6 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border-l-4 border-blue-600 flex flex-col justify-between">
                    <div>
                        <div class="bg-blue-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-address-card text-blue-700 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-blue-800 mb-2">Resident Records</h3>
                        <p class="text-gray-600 text-sm mb-3">Barangay clearance, certificates, and resident database management</p>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">3,421 records</span>
                        <a href="javascript:void(0)" onclick="openServiceRequestModal('Barangay Clearance')" class="text-blue-700 text-sm font-semibold hover:text-blue-900 cursor-pointer">Apply <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
                    </div>
                </div>
                
                <!-- Focus 2: Disaster Relief -->
                <div class="bg-gradient-to-br from-red-50 to-white p-5 md:p-6 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border-l-4 border-red-600 flex flex-col justify-between">
                    <div>
                        <div class="bg-red-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-hand-holding-heart text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-red-800 mb-2">Disaster Relief</h3>
                        <p class="text-gray-600 text-sm mb-3">Track requests, resources, and volunteers during emergencies</p>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">5 active ops</span>
                        <a href="javascript:void(0)" onclick="@auth window.location='{{ route('dashboard') }}' @else openRegisterModal() @endauth" class="text-red-700 text-sm font-semibold hover:text-red-900 cursor-pointer">Request <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
                    </div>
                </div>
                
                <!-- Focus 3: Health Monitoring -->
                <div class="bg-gradient-to-br from-green-50 to-white p-5 md:p-6 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border-l-4 border-green-600 flex flex-col justify-between">
                    <div>
                        <div class="bg-green-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-heartbeat text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-green-800 mb-2">Health Monitoring</h3>
                        <p class="text-gray-600 text-sm mb-3">Community wellness data and health record tracking</p>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">892 records</span>
                        <a href="javascript:void(0)" onclick="@auth window.location='{{ route('dashboard') }}' @else openRegisterModal() @endauth" class="text-green-700 text-sm font-semibold hover:text-green-900 cursor-pointer">Enroll <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
                    </div>
                </div>
                
                <!-- Focus 4: Job Matching -->
                <div class="bg-gradient-to-br from-purple-50 to-white p-5 md:p-6 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border-l-4 border-purple-600 flex flex-col justify-between">
                    <div>
                        <div class="bg-purple-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-briefcase text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-purple-800 mb-2">Job Matching</h3>
                        <p class="text-gray-600 text-sm mb-3">Connect local employers with qualified job seekers</p>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">12 openings</span>
                        <a href="javascript:void(0)" onclick="@auth window.location='{{ route('dashboard') }}' @else openRegisterModal() @endauth" class="text-purple-700 text-sm font-semibold hover:text-purple-900 cursor-pointer">Apply <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
                    </div>
                </div>
                
                <!-- Focus 5: Scholarship Assistance -->
                <div class="bg-gradient-to-br from-yellow-50 to-white p-5 md:p-6 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border-l-4 border-yellow-600 flex flex-col justify-between">
                    <div>
                        <div class="bg-yellow-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-graduation-cap text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-yellow-800 mb-2">Scholarship</h3>
                        <p class="text-gray-600 text-sm mb-3">Financial assistance management with eligibility tracking</p>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">28 available</span>
                        <a href="javascript:void(0)" onclick="@auth window.location='{{ route('dashboard') }}' @else openRegisterModal() @endauth" class="text-yellow-700 text-sm font-semibold hover:text-yellow-900 cursor-pointer">Apply <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Announcements -->
    <section id="news" class="py-16 px-6 relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1527529482837-4698179dc6ce?w=1200" 
                 class="w-full h-full object-cover"
                 alt="Community event">
            <div class="absolute inset-0 bg-gray-900/70"></div>
        </div>
        
        <div class="container mx-auto relative z-10">
            <div class="flex items-center mb-6">
                <img src="{{ asset('1.jpg') }}" alt="Logo" class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-white">
                <h2 class="text-3xl font-bold text-white">Community Updates</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">Health</span>
                            <span class="text-gray-500 text-sm">March 22, 2026</span>
                        </div>
                        <h3 class="text-xl font-bold text-blue-900 mb-3">Community Health Monitoring</h3>
                        <p class="text-gray-600 mb-4">Free wellness check-ups, dental services, and maintenance medicine distribution for seniors.</p>
                        <a href="javascript:void(0)" onclick="openAIAssistant(); sendMessageDirect('Tell me about the health monitoring schedule');" class="text-blue-700 text-sm font-semibold hover:text-blue-900 cursor-pointer">Ask Servi Assistant &rarr;</a>
                    </div>
                </div>
                
                <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">Jobs</span>
                            <span class="text-gray-500 text-sm">March 20, 2026</span>
                        </div>
                        <h3 class="text-xl font-bold text-blue-900 mb-3">Local Job Matching</h3>
                        <p class="text-gray-600 mb-4">12 job openings available - Connect with local employers and government administrative positions.</p>
                        <a href="{{ route('dashboard') }}" class="text-blue-700 text-sm font-semibold hover:text-blue-900">View Jobs in Dashboard &rarr;</a>
                    </div>
                </div>
                
                <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">Scholarship</span>
                            <span class="text-gray-500 text-sm">March 18, 2026</span>
                        </div>
                        <h3 class="text-xl font-bold text-blue-900 mb-3">Financial Assistance</h3>
                        <p class="text-gray-600 mb-4">28 scholarship slots available with eligibility tracking. Open for high school and college students.</p>
                        <a href="{{ route('dashboard') }}" class="text-blue-700 text-sm font-semibold hover:text-blue-900">Apply via Resident Portal &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Barangay Officials -->
    <section id="officials" class="py-16 px-6 bg-white">
        <div class="container mx-auto">
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('1.jpg') }}" alt="Logo" class="w-14 h-14 rounded-full mr-3 object-cover border-2 border-blue-600">
                <h2 class="text-3xl font-bold text-blue-900">Barangay Officials</h2>
            </div>
            <p class="text-gray-600 text-center mb-12 max-w-3xl mx-auto">Meet the dedicated leaders serving our community through these social impact systems.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-lg overflow-hidden text-center p-6 transform hover:scale-105 transition duration-300">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-tie text-white text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-2">Hon. Maria Santos</h3>
                    <p class="text-blue-700 font-medium mb-4">Barangay Captain</p>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-lg overflow-hidden text-center p-6 transform hover:scale-105 transition duration-300">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-tie text-white text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-2">Hon. Juan Dela Cruz</h3>
                    <p class="text-blue-700 font-medium mb-4">Barangay Kagawad</p>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-lg overflow-hidden text-center p-6 transform hover:scale-105 transition duration-300">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-tie text-white text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-2">Hon. Ana Rodriguez</h3>
                    <p class="text-blue-700 font-medium mb-4">Barangay Kagawad</p>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-lg overflow-hidden text-center p-6 transform hover:scale-105 transition duration-300">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-tie text-white text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-2">Jose Reyes</h3>
                    <p class="text-blue-700 font-medium mb-4">Barangay Secretary</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Office Hours -->
    <section id="contact" class="py-16 px-6 bg-blue-900 text-white relative overflow-hidden">
        <div class="container mx-auto relative z-10">
            <div class="flex items-center justify-center mb-8">
                <img src="{{ asset('1.jpg') }}" alt="Logo" class="w-14 h-14 rounded-full mr-3 object-cover border-2 border-white">
                <h2 class="text-3xl font-bold text-center">Contact Us</h2>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <p class="text-blue-100 text-lg mb-8">Get in touch for questions about our community systems: resident records, disaster relief, health monitoring, job matching, or scholarship assistance.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="bg-blue-800 p-3 rounded-lg mr-4">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Address</h4>
                                <p class="text-blue-100">Barangay Hall, San Agustin, City of San Jose del Monte (SJDM), Bulacan</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-blue-800 p-3 rounded-lg mr-4">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Phone</h4>
                                <p class="text-blue-100">(044) 901-4567 / 0908-123-4567</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-blue-800 p-3 rounded-lg mr-4">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Email</h4>
                                <p class="text-blue-100">info@brgy-sanagustin.gov.ph</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-800/90 backdrop-blur-sm p-8 rounded-xl">
                    <h3 class="text-2xl font-bold mb-6">Office Hours</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center pb-4 border-b border-blue-700">
                            <span>Monday - Friday</span>
                            <span class="font-medium">8:00 AM - 5:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-blue-700">
                            <span>Saturday</span>
                            <span class="font-medium">8:00 AM - 12:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Sunday</span>
                            <span class="font-medium">Closed (Hotlines 24/7)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-950 text-white py-8 px-6">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center">
                        <img src="{{ asset('1.jpg') }}" alt="Barangay Logo" class="w-12 h-12 rounded-full border-2 border-white mr-3 object-cover">
                        <div>
                            <h2 class="text-xl font-bold">Barangay San Agustin</h2>
                            <p class="text-sm text-blue-300">Community-Focused Systems (Laravel)</p>
                        </div>
                    </div>
                    <p class="mt-4 text-blue-200 max-w-md">Integrated platform for resident records, disaster relief, health monitoring, job matching, and scholarship assistance.</p>
                </div>
            </div>
            
            <div class="border-t border-blue-800 mt-8 pt-8 text-center text-blue-300">
                <p>&copy; {{ date('Y') }} Barangay San Agustin. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- EMERGENCY MODAL -->
    <div id="emergencyModal" class="modal-overlay">
        <div class="bg-white rounded-2xl max-w-lg w-[95%] sm:w-[90%] md:w-full overflow-hidden shadow-2xl mx-4">
            <div class="modal-header-red px-4 sm:px-6 py-4 flex justify-between items-center rounded-t-2xl">
                <div class="flex items-center">
                    <img src="{{ asset('1.jpg') }}" alt="Barangay Logo" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full mr-3 object-cover border-2 border-white shadow-lg">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-white">🚨 Emergency Hotlines</h3>
                </div>
                <button type="button" onclick="closeEmergencyModal()" class="modal-close-btn" aria-label="Close">&times;</button>
            </div>
            
            <div class="p-4 sm:p-6 bg-gray-50 max-h-[70vh] overflow-y-auto space-y-3">
                <div class="bg-red-50 p-4 rounded-xl flex items-center justify-between border-l-4 border-red-600">
                    <div>
                        <span class="font-bold text-gray-800">Philippine National Police</span>
                        <p class="text-xs text-gray-500">Emergency Hotline</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xl font-bold text-red-600">117</span>
                        <a href="tel:117" class="bg-red-600 text-white p-2.5 rounded-full hover:bg-red-700"><i class="fas fa-phone-alt"></i></a>
                    </div>
                </div>

                <div class="bg-red-50 p-4 rounded-xl flex items-center justify-between border-l-4 border-red-600">
                    <div>
                        <span class="font-bold text-gray-800">Bureau of Fire Protection</span>
                        <p class="text-xs text-gray-500">Fire Emergency</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xl font-bold text-red-600">160</span>
                        <a href="tel:160" class="bg-red-600 text-white p-2.5 rounded-full hover:bg-red-700"><i class="fas fa-phone-alt"></i></a>
                    </div>
                </div>

                <div class="bg-red-50 p-4 rounded-xl flex items-center justify-between border-l-4 border-red-600">
                    <div>
                        <span class="font-bold text-gray-800">Emergency Medical Services</span>
                        <p class="text-xs text-gray-500">Ambulance & Rescue</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xl font-bold text-red-600">911</span>
                        <a href="tel:911" class="bg-red-600 text-white p-2.5 rounded-full hover:bg-red-700"><i class="fas fa-phone-alt"></i></a>
                    </div>
                </div>

                <div class="bg-red-50 p-4 rounded-xl flex items-center justify-between border-l-4 border-red-600">
                    <div>
                        <span class="font-bold text-gray-800">Barangay Disaster Relief</span>
                        <p class="text-xs text-gray-500">24/7 Operations Desk</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-red-600">0908-123-4567</span>
                        <a href="tel:09081234567" class="bg-red-600 text-white p-2.5 rounded-full hover:bg-red-700"><i class="fas fa-phone-alt"></i></a>
                    </div>
                </div>

                <button onclick="closeEmergencyModal()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 rounded-lg mt-4 cursor-pointer">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- LOGIN MODAL -->
    <div id="loginModal" class="modal-overlay">
        <div class="bg-white rounded-2xl max-w-md w-[95%] sm:w-[90%] md:w-full overflow-hidden shadow-2xl mx-4">
            <div class="modal-header-blue px-6 py-5 flex justify-between items-center rounded-t-2xl">
                <div class="flex items-center">
                    <img src="{{ asset('1.jpg') }}" alt="Barangay Logo" class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-white shadow-lg">
                    <h3 class="text-xl sm:text-2xl font-bold text-white">Welcome Back! 👋</h3>
                </div>
                <button type="button" onclick="closeLoginModal()" class="modal-close-btn" aria-label="Close">&times;</button>
            </div>
            
            <form id="loginForm" onsubmit="handleAjaxLogin(event)" class="p-6 bg-gray-50">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-3.5 text-blue-600"></i>
                        <input type="email" name="email" id="loginEmail" value="maria@example.com" placeholder="Enter your email" 
                               class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-3.5 text-blue-600"></i>
                        <input type="password" name="password" id="loginPassword" value="password" placeholder="Enter password" 
                               class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-800 mb-4 leading-relaxed">
                    <p><i class="fas fa-info-circle mr-1"></i> <strong>Resident:</strong> maria@example.com / password</p>
                    <p class="mt-0.5"><i class="fas fa-shield-alt mr-1"></i> <strong>Staff / Admin:</strong> admin@sjdm.gov.ph / admin123</p>
                </div>
                
                <button type="submit" id="loginSubmitBtn" class="btn-submit-blue w-full py-3.5 px-6 rounded-xl text-white font-bold flex items-center justify-center cursor-pointer">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Dashboard
                </button>
                
                <p class="text-center text-gray-600 text-xs mt-4">
                    Don't have an account? 
                    <a href="javascript:void(0)" onclick="switchToRegister()" class="text-blue-600 font-bold hover:underline">
                        Create Account
                    </a>
                </p>
            </form>
        </div>
    </div>

    <!-- REGISTER MODAL -->
    <div id="registerModal" class="modal-overlay">
        <div class="bg-white rounded-2xl max-w-4xl w-[95%] overflow-hidden shadow-2xl mx-4">
            <div class="modal-header-blue px-6 py-4 flex justify-between items-center rounded-t-2xl">
                <div class="flex items-center">
                    <img src="{{ asset('1.jpg') }}" alt="Barangay Logo" class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-white shadow-lg">
                    <div>
                        <h3 class="text-xl font-bold text-white">Create Resident Account</h3>
                        <p class="text-xs text-blue-200">Barangay San Agustin Residency Verification</p>
                    </div>
                </div>
                <button type="button" onclick="closeRegisterModal()" class="modal-close-btn" aria-label="Close">&times;</button>
            </div>
            
            <form id="registerForm" onsubmit="handleAjaxRegister(event)" class="p-6 bg-gray-50 max-h-[80vh] overflow-y-auto">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Barangay ID Number *</label>
                        <input type="text" name="barangay_id" id="regBarangayId" placeholder="e.g. BRGY-8899" required
                               class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="name" id="regName" placeholder="Juan Dela Cruz" required
                               class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address *</label>
                        <input type="email" name="email" id="regEmail" placeholder="juan@example.com" required
                               class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Contact Phone</label>
                        <input type="tel" name="phone" id="regPhone" placeholder="0917-000-0000"
                               class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Birthdate</label>
                        <input type="date" name="birthdate" id="regBirthdate"
                               class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Gender</label>
                        <select name="gender" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Address / Street</label>
                        <input type="text" name="address" id="regAddress" placeholder="House #, Street name, Purok"
                               class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Password *</label>
                        <input type="password" name="password" id="regPassword" placeholder="Minimum 6 characters" required
                               class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Civil Status</label>
                        <select name="civil_status" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" id="regSubmitBtn" class="btn-submit-green w-full py-3.5 px-6 rounded-xl text-white font-bold flex items-center justify-center cursor-pointer">
                    <i class="fas fa-user-plus mr-2"></i>Create Resident Account & Verify
                </button>
            </form>
        </div>
    </div>

    <!-- DOCUMENT SERVICE REQUEST MODAL -->
    <div id="serviceRequestModal" class="modal-overlay">
        <div class="bg-white rounded-2xl max-w-md w-[95%] overflow-hidden shadow-2xl mx-4">
            <div class="modal-header-blue px-6 py-4 flex justify-between items-center text-white rounded-t-2xl">
                <h3 class="font-bold text-lg text-white" id="serviceModalTitle">Request Document</h3>
                <button type="button" onclick="closeServiceRequestModal()" class="modal-close-btn" aria-label="Close">&times;</button>
            </div>
            <form onsubmit="handleServiceRequest(event)" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="serviceTypeInput" name="service_type" value="Barangay Clearance">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name</label>
                    <input type="text" id="reqFullName" name="full_name" required value="{{ Auth::user()?->name }}" placeholder="Full Name" class="w-full p-2.5 border rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Contact Number</label>
                    <input type="tel" id="reqPhone" name="contact_number" required value="{{ Auth::user()?->phone }}" placeholder="09XX-XXX-XXXX" class="w-full p-2.5 border rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Purpose of Request</label>
                    <textarea id="reqPurpose" name="purpose" rows="3" placeholder="e.g. Employment, Bank requirement, Scholarship" class="w-full p-2.5 border rounded-lg text-sm" required></textarea>
                </div>
                <button type="submit" class="btn-submit-blue w-full py-3 rounded-xl font-bold text-white transition cursor-pointer">
                    Submit Request
                </button>
            </form>
        </div>
    </div>

    <!-- AI ASSISTANT FLOATING BUTTON -->
    <button onclick="toggleAIAssistant()" class="ai-float-btn group z-50 cursor-pointer bg-transparent border-0 p-0">
        <div class="relative">
            <div class="absolute inset-0 bg-blue-500 rounded-full animate-ping opacity-25"></div>
            <div class="relative bg-blue-600 bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:scale-110 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('1.png') }}" class="w-10 h-10 rounded-full object-cover" alt="AI Assistant">
            </div>
            <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
        </div>
    </button>

    <!-- AI CHAT MODAL -->
    <div id="aiAssistant" class="fixed bottom-24 right-6 w-80 bg-white shadow-2xl rounded-2xl flex flex-col overflow-hidden border border-slate-200" style="display:none; z-index:9999;">
        <div class="bg-blue-600 text-white p-3.5 flex justify-between items-center rounded-t-2xl">
            <span class="font-bold flex items-center gap-2 text-sm">
                <img src="{{ asset('1.png') }}" class="w-8 h-8 rounded-full object-cover border border-white" alt="AI Assistant">
                Servi · Barangay AI Assistant
            </span>
            <button onclick="closeAIAssistant()" class="hover:bg-blue-700 rounded-full w-7 h-7 flex items-center justify-center transition">&times;</button>
        </div>
        <div id="chatBox" class="p-3 h-72 overflow-y-auto space-y-2 text-sm bg-gray-50 flex flex-col">
            <div class="bg-gray-200 text-gray-800 p-2.5 rounded-xl w-fit max-w-[85%] text-xs leading-relaxed">
                Magandang araw! 👋 I'm Servi, your Barangay San Agustin AI assistant. How can I help you with barangay clearances, health schedules, jobs, or emergency numbers today?
            </div>
        </div>
        <div class="p-2.5 border-t flex gap-2 bg-white">
            <input type="text" id="userInput" placeholder="Ask about services, clearance..." 
                   onkeypress="if(event.key === 'Enter') sendAIMessage()"
                   class="flex-1 text-xs border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button onclick="sendAIMessage()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-xl text-xs font-semibold">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Navigation toggle
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Close mobile menu when a link or button is clicked
        mobileMenu.querySelectorAll('a, button').forEach(item => {
            item.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    }

    // Modal helpers
    function openEmergencyModal() { document.getElementById('emergencyModal').classList.add('active'); }
    function closeEmergencyModal() { document.getElementById('emergencyModal').classList.remove('active'); }
    function openLoginModal() { document.getElementById('loginModal').classList.add('active'); }
    function closeLoginModal() { document.getElementById('loginModal').classList.remove('active'); }
    function openRegisterModal() { document.getElementById('registerModal').classList.add('active'); }
    function closeRegisterModal() { document.getElementById('registerModal').classList.remove('active'); }
    function switchToRegister() { closeLoginModal(); openRegisterModal(); }
    function switchToLogin() { closeRegisterModal(); openLoginModal(); }

    function openServiceRequestModal(type) {
        document.getElementById('serviceModalTitle').innerText = 'Request ' + type;
        document.getElementById('serviceTypeInput').value = type;
        document.getElementById('serviceRequestModal').classList.add('active');
    }
    function closeServiceRequestModal() { document.getElementById('serviceRequestModal').classList.remove('active'); }

    // AI Assistant Widget
    function toggleAIAssistant() {
        const el = document.getElementById('aiAssistant');
        el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'flex' : 'none';
    }
    function openAIAssistant() { document.getElementById('aiAssistant').style.display = 'flex'; }
    function closeAIAssistant() { document.getElementById('aiAssistant').style.display = 'none'; }

    async function sendAIMessage() {
        const input = document.getElementById('userInput');
        const text = input.value.trim();
        if (!text) return;
        input.value = '';
        addChatBubble(text, 'user');

        const chatBox = document.getElementById('chatBox');
        const loadingId = 'loading-' + Date.now();
        const loadingDiv = document.createElement('div');
        loadingDiv.id = loadingId;
        loadingDiv.className = 'bg-gray-200 text-gray-500 p-2.5 rounded-xl w-fit max-w-[85%] text-xs italic';
        loadingDiv.innerText = 'Servi is thinking...';
        chatBox.appendChild(loadingDiv);
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch("{{ route('ai.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });
            const data = await res.json();
            document.getElementById(loadingId)?.remove();
            addChatBubble(data.reply || "Sorry, I couldn't process your request right now.", 'bot');
        } catch (e) {
            document.getElementById(loadingId)?.remove();
            addChatBubble("Sorry, connection error. Please try again shortly.", 'bot');
        }
    }

    function sendMessageDirect(msg) {
        document.getElementById('userInput').value = msg;
        sendAIMessage();
    }

    function addChatBubble(text, sender) {
        const chatBox = document.getElementById('chatBox');
        const msg = document.createElement('div');
        if (sender === 'user') {
            msg.className = 'bg-blue-600 text-white p-2.5 rounded-xl ml-auto w-fit max-w-[80%] text-xs';
        } else {
            msg.className = 'bg-gray-200 text-gray-800 p-2.5 rounded-xl w-fit max-w-[85%] text-xs leading-relaxed';
        }
        msg.textContent = text;
        chatBox.appendChild(msg);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // AJAX Login
    async function handleAjaxLogin(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('loginSubmitBtn');
        const origText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Logging in...';
        btn.disabled = true;

        const formData = new FormData(form);
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch("{{ route('login.post') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast(data.message || 'Login successful!');
                window.location.href = data.redirect || "{{ route('dashboard') }}";
            } else {
                showToast(data.message || 'Invalid email or password', 'error');
                btn.innerHTML = origText;
                btn.disabled = false;
            }
        } catch (err) {
            showToast('Login request failed', 'error');
            btn.innerHTML = origText;
            btn.disabled = false;
        }
    }

    // AJAX Register
    async function handleAjaxRegister(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('regSubmitBtn');
        const origText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating account...';
        btn.disabled = true;

        const formData = new FormData(form);
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch("{{ route('register.post') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast(data.message || 'Account created successfully!');
                window.location.href = data.redirect || "{{ route('dashboard') }}";
            } else {
                let msg = data.message || 'Registration failed';
                if (data.errors) {
                    msg = Object.values(data.errors).flat().join(' ');
                }
                showToast(msg, 'error');
                btn.innerHTML = origText;
                btn.disabled = false;
            }
        } catch (err) {
            showToast('Registration request failed', 'error');
            btn.innerHTML = origText;
            btn.disabled = false;
        }
    }

    // Handle document request
    async function handleServiceRequest(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch("{{ route('services.request') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message);
                closeServiceRequestModal();
                form.reset();
            }
        } catch(err) {
            showToast('Failed to submit request', 'error');
        }
    }
</script>
@endpush
