@extends('layouts.app')

@section('title', 'Barangay San Agustin - Resident Community Hub')

@section('body-class', 'bg-slate-100 min-h-screen text-slate-800 antialiased')

@push('styles')
<style>
    /* Bottom Navigation Item */
    .nav-item.active .nav-icon { color: #1d4ed8 !important; }
    .nav-item.active .nav-label { color: #1d4ed8 !important; font-weight: 700 !important; }
    .nav-item.active { border-top: 3px solid #1d4ed8 !important; }

    /* Desktop Sidebar Nav Item */
    .sidebar-nav-item.active {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        font-weight: 700 !important;
        border-left: 4px solid #1d4ed8;
    }

    /* Module and Service cards */
    .module-card {
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }
    .module-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
    }
    .module-card:active { transform: scale(0.98); }

    /* Post Card */
    .post-card {
        transition: all 0.2s ease;
    }
    .post-card:hover {
        background: #fafafc;
    }
    .like-btn.liked {
        color: #ef4444;
    }
    .like-btn.liked i {
        animation: heartPop 0.3s ease;
    }
    @keyframes heartPop {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    /* Guaranteed Welcome gradient */
    .welcome-gradient {
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%) !important;
        background-color: #1e3a8a !important;
        color: #ffffff !important;
    }

    /* Screen Transition Animation */
    .screen-view {
        animation: fadeInScreen 0.2s ease-in-out;
    }
    @keyframes fadeInScreen {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex flex-col md:flex-row bg-slate-100">

    <!-- ============================================== -->
    <!-- DESKTOP SIDEBAR (WEBSITE MODE: MD & ABOVE)     -->
    <!-- ============================================== -->
    <aside class="hidden md:flex md:w-64 lg:w-72 flex-col bg-white border-r border-slate-200/90 min-h-screen sticky top-0 h-screen overflow-y-auto p-5 z-30 flex-shrink-0 shadow-xs">
        
        <!-- Official Logo & Header -->
        <div class="flex items-center gap-3 pb-5 border-b border-slate-100">
            <img src="{{ asset('1.jpg') }}" alt="Barangay San Agustin Seal" class="w-11 h-11 rounded-full object-cover border-2 border-blue-600 shadow-sm flex-shrink-0">
            <div>
                <h1 class="text-sm font-extrabold text-blue-950 leading-tight">Brgy. San Agustin</h1>
                <p class="text-[11px] text-slate-500 font-medium">Community & Staff Hub</p>
            </div>
        </div>

        <!-- Resident Identity Card -->
        <div class="my-4 p-3 bg-blue-50/70 rounded-2xl border border-blue-100/80 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-extrabold text-xs flex items-center justify-center shadow-xs flex-shrink-0">
                {{ $user->initials }}
            </div>
            <div class="overflow-hidden flex-1">
                <p class="text-xs font-bold text-slate-900 truncate">{{ $user->name }}</p>
                <p class="text-[10px] text-blue-700 font-mono font-medium">{{ $user->barangay_id ?? 'BRGY-RESIDENT' }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] text-slate-500 font-medium">Online Resident</span>
                </div>
            </div>
        </div>

        <!-- New Post Action Button -->
        <button onclick="switchScreen('post')" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-4 rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-2 mb-4 cursor-pointer">
            <i class="fas fa-plus-circle"></i> Create Post
        </button>

        <!-- Navigation Menu -->
        <nav class="space-y-1 flex-1">
            <button onclick="switchScreen('home')" id="sidebar-nav-home" class="sidebar-nav-item active w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 transition cursor-pointer">
                <i class="fas fa-newspaper w-4 text-center"></i>
                <span>Community Feed</span>
            </button>
            <button onclick="switchScreen('health')" id="sidebar-nav-health" class="sidebar-nav-item w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-blue-700 transition cursor-pointer">
                <i class="fas fa-heartbeat w-4 text-center text-green-600"></i>
                <span>Health Records</span>
            </button>
            <button onclick="switchScreen('jobs')" id="sidebar-nav-jobs" class="sidebar-nav-item w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-blue-700 transition cursor-pointer">
                <i class="fas fa-briefcase w-4 text-center text-purple-600"></i>
                <span>Job Openings</span>
            </button>
            <button onclick="switchScreen('scholar')" id="sidebar-nav-scholar" class="sidebar-nav-item w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-blue-700 transition cursor-pointer">
                <i class="fas fa-graduation-cap w-4 text-center text-amber-600"></i>
                <span>Scholarship</span>
            </button>
            <button onclick="switchScreen('relief')" id="sidebar-nav-relief" class="sidebar-nav-item w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-blue-700 transition cursor-pointer">
                <i class="fas fa-hand-holding-heart w-4 text-center text-red-500"></i>
                <span>Disaster Relief</span>
            </button>
        </nav>

        <!-- Sidebar Footer Utility Actions -->
        <div class="pt-4 border-t border-slate-100 space-y-1">
            <button onclick="openModal('emergencyModal')" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                <i class="fas fa-phone-alt text-red-500 w-4 text-center"></i>
                <span>Emergency Hotlines</span>
            </button>
            <a href="{{ route('home') }}" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                <i class="fas fa-globe text-slate-400 w-4 text-center"></i>
                <span>Barangay Website</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="pt-1">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 transition cursor-pointer">
                    <i class="fas fa-sign-out-alt w-4 text-center"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ============================================== -->
    <!-- MAIN CONTENT AREA                              -->
    <!-- ============================================== -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">

        <!-- Mobile Top Navbar (Visible on Mobile < md) -->
        <header class="md:hidden bg-white border-b border-slate-200 px-4 h-16 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('1.jpg') }}" alt="Barangay Logo" class="w-9 h-9 rounded-full object-cover border border-blue-600 shadow-xs">
                <div>
                    <h1 class="text-sm font-bold text-blue-950 leading-tight">Brgy. San Agustin</h1>
                    <p class="text-[10px] text-slate-500 font-medium">Resident Hub</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="switchScreen('relief')" class="p-2 text-red-500 hover:bg-red-50 rounded-xl cursor-pointer" title="Relief Desk">
                    <i class="fas fa-hand-holding-heart text-base"></i>
                </button>
                <button onclick="openModal('emergencyModal')" class="p-2 text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer" title="Hotlines">
                    <i class="fas fa-phone-alt text-sm"></i>
                </button>
                
                <!-- Mobile User Avatar Dropdown -->
                <div class="relative">
                    <button onclick="toggleUserDropdown()" class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-xs border border-blue-200 cursor-pointer">
                        {{ $user->initials }}
                    </button>
                    <div id="userDropdown" class="hidden absolute right-0 top-11 bg-white rounded-2xl shadow-xl border border-slate-100 w-52 z-50 overflow-hidden py-1">
                        <div class="px-4 py-2.5 border-b border-slate-100 bg-slate-50">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $user->name }}</p>
                            <p class="text-[10px] text-blue-700 font-mono">{{ $user->barangay_id ?? 'BRGY-RESIDENT' }}</p>
                        </div>
                        <a href="{{ route('home') }}" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                            <i class="fas fa-globe text-slate-400"></i> Barangay Website
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 flex items-center gap-2 cursor-pointer">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Desktop Top Header Bar (Website mode status & quick links) -->
        <header class="hidden md:flex bg-white border-b border-slate-200 px-8 py-3.5 items-center justify-between sticky top-0 z-20 shadow-2xs">
            <div class="flex items-center gap-3">
                <span id="desktop-screen-badge" class="px-3.5 py-1 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-100">
                    Community Feed
                </span>
                <span class="text-xs text-slate-500 font-medium">Logged in as <strong class="text-slate-800">{{ $user->name }}</strong> · <span class="font-mono text-blue-700">{{ $user->barangay_id ?? 'BRGY-RESIDENT' }}</span></span>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="switchScreen('relief')" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3.5 py-1.5 rounded-xl transition flex items-center gap-1.5 cursor-pointer border border-red-100">
                    <i class="fas fa-hand-holding-heart"></i> Disaster Relief Desk
                </button>
                <button onclick="openModal('emergencyModal')" class="text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 px-3.5 py-1.5 rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fas fa-phone-alt text-red-500"></i> Hotlines
                </button>
            </div>
        </header>

        <!-- Main Screen Body -->
        <main class="flex-1 p-4 md:p-8 max-w-4xl w-full mx-auto pb-24 md:pb-12">

            <!-- ============================================== -->
            <!-- SCREEN 1: HOME & COMMUNITY FEED                -->
            <!-- ============================================== -->
            <div id="screen-home" class="screen-view space-y-4">
                <!-- Welcome Banner -->
                <div class="welcome-gradient rounded-3xl p-5 sm:p-6 shadow-md relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%) !important; background-color: #1e3a8a !important; color: #ffffff !important;">
                    <div class="absolute -right-8 -top-8 w-36 h-36 bg-white/10 rounded-full blur-xl"></div>
                    <div class="absolute -right-4 bottom-0 w-24 h-24 bg-white/5 rounded-full"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/20 text-white uppercase tracking-wider backdrop-blur-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Verified Resident
                            </span>
                            <span class="text-xs text-blue-100 font-mono">{{ $user->barangay_id ?? 'BRGY-001' }}</span>
                        </div>

                        <h2 class="text-xl sm:text-2xl font-extrabold mt-2 tracking-tight text-white" id="welcomeName">{{ $user->name }}</h2>
                        <p class="text-xs sm:text-sm text-blue-100 mt-0.5">{{ $user->address ?? 'Barangay San Agustin Community Member' }}</p>

                        <div class="grid grid-cols-3 gap-2.5 mt-4 pt-4 border-t border-white/20">
                            <div class="bg-white/15 rounded-2xl p-2.5 text-center">
                                <p class="text-base sm:text-lg font-bold text-white" id="totalActivityCount">{{ $userStats['posts_count'] }}</p>
                                <p class="text-[10px] sm:text-xs text-blue-200">Community Posts</p>
                            </div>
                            <div class="bg-white/15 rounded-2xl p-2.5 text-center">
                                <p class="text-base sm:text-lg font-bold text-emerald-300">Active</p>
                                <p class="text-[10px] sm:text-xs text-blue-200">Portal Status</p>
                            </div>
                            <div class="bg-white/15 rounded-2xl p-2.5 text-center">
                                <p class="text-base sm:text-lg font-bold text-white">{{ $userStats['online_count'] }}</p>
                                <p class="text-[10px] sm:text-xs text-blue-200">Residents Active</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Navigation Action Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <button onclick="switchScreen('health')" class="module-card bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs text-left flex items-center gap-3 cursor-pointer group">
                        <div class="w-11 h-11 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-105 transition">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Health Log</span>
                            <span class="text-[10px] text-slate-500 block">Vitals & Clinic</span>
                        </div>
                    </button>

                    <button onclick="switchScreen('jobs')" class="module-card bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs text-left flex items-center gap-3 cursor-pointer group">
                        <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-105 transition">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Job Openings</span>
                            <span class="text-[10px] text-slate-500 block">Local matching</span>
                        </div>
                    </button>

                    <button onclick="switchScreen('scholar')" class="module-card bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs text-left flex items-center gap-3 cursor-pointer group">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-105 transition">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Scholarship</span>
                            <span class="text-[10px] text-slate-500 block">Youth grant fund</span>
                        </div>
                    </button>

                    <button onclick="switchScreen('relief')" class="module-card bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs text-left flex items-center gap-3 cursor-pointer group">
                        <div class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-105 transition">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Disaster Relief</span>
                            <span class="text-[10px] text-slate-500 block">24/7 Operations</span>
                        </div>
                    </button>
                </div>

                <!-- Quick Post Box Trigger -->
                <button onclick="switchScreen('post')" class="w-full bg-white rounded-2xl p-3.5 shadow-xs border border-slate-200/80 flex items-center gap-3 hover:bg-slate-50/80 transition text-left cursor-pointer">
                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-xs flex-shrink-0">
                        {{ $user->initials }}
                    </div>
                    <span class="text-slate-500 text-xs sm:text-sm font-medium">What's happening in your purok, {{ strtok($user->name, " ") }}? Share an update...</span>
                    <span class="ml-auto bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                        <i class="fas fa-pen"></i> Post
                    </span>
                </button>

                <!-- Feed Tabs Header -->
                <div class="flex items-center justify-between border-b border-slate-200/80 pb-1">
                    <div class="flex gap-2">
                        <button onclick="switchFeedTab('recent', this)" class="feed-tab-btn px-4 py-2 text-xs sm:text-sm font-bold text-blue-700 border-b-2 border-blue-700 cursor-pointer">
                            <i class="fas fa-clock mr-1"></i> Recent Posts
                        </button>
                        <button onclick="switchFeedTab('popular', this)" class="feed-tab-btn px-4 py-2 text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-700 cursor-pointer">
                            <i class="fas fa-fire mr-1"></i> Most Liked
                        </button>
                        <button onclick="switchFeedTab('questions', this)" class="feed-tab-btn px-4 py-2 text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-700 cursor-pointer">
                            <i class="fas fa-question-circle mr-1"></i> Resident Questions
                        </button>
                    </div>
                </div>

                <!-- Community Feed List -->
                <div id="communityFeed" class="space-y-3.5 pb-4">
                    <!-- Dynamically rendered by JS -->
                </div>
            </div>


            <!-- ============================================== -->
            <!-- SCREEN 2: HEALTH & CLINIC RECORDS SCREEN       -->
            <!-- ============================================== -->
            <div id="screen-health" class="screen-view space-y-4 hidden">
                <!-- Screen Header -->
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <button onclick="switchScreen('home')" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition cursor-pointer" title="Back to Feed">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-heartbeat text-green-600"></i> Health & Clinic Records
                            </h2>
                            <p class="text-xs text-slate-500">Barangay San Agustin Health Station & Wellness Records</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 self-start sm:self-auto">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span> Station Open (8AM - 5PM)
                    </span>
                </div>

                <!-- Health Information Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                        <p class="text-xs font-semibold text-slate-500">Doctor On Duty</p>
                        <p class="text-sm font-bold text-slate-900 mt-1">Dr. Elena Santos, MD</p>
                        <p class="text-[11px] text-green-600 mt-0.5"><i class="fas fa-calendar-check mr-1"></i> Mon · Wed · Fri Consultation</p>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                        <p class="text-xs font-semibold text-slate-500">Free Maintenance Meds</p>
                        <p class="text-sm font-bold text-slate-900 mt-1">Available at Health Desk</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Hypertension & Diabetes allocation</p>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                        <p class="text-xs font-semibold text-slate-500">Emergency Rescue</p>
                        <p class="text-sm font-bold text-red-600 mt-1">911 / (02) 8888-4357</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Barangay Ambulance Standby</p>
                    </div>
                </div>

                <!-- Health Log Entry Form -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                    <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2">
                        <i class="fas fa-notes-medical text-green-600"></i> Log Your Vital Signs & Consultation
                    </h3>
                    <p class="text-xs text-slate-500 mb-4">Record your latest blood pressure or health observations for barangay health worker verification.</p>

                    <form onsubmit="handleHealthSubmit(event)" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1">Blood Pressure (BP) *</label>
                                <input type="text" id="healthBP" placeholder="e.g. 120/80 mmHg" required 
                                       class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1">Heart Rate / Pulse (optional)</label>
                                <input type="text" id="healthPulse" placeholder="e.g. 72 bpm" 
                                       class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-1">Symptoms, Medications, or Notes</label>
                            <textarea id="healthNotes" rows="3" placeholder="Describe any current symptoms, headache, fever, maintenance prescriptions, or doctor advice..." 
                                      class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-green-400 focus:outline-none transition resize-none"></textarea>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xs text-slate-400"><i class="fas fa-lock mr-1"></i> Data saved securely to your resident profile</span>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition flex items-center gap-2 cursor-pointer">
                                <i class="fas fa-save"></i> Save Health Record
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Health Center Advisories -->
                <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-200/80 text-emerald-900">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-bullhorn text-emerald-600 text-lg mt-0.5"></i>
                        <div>
                            <p class="font-bold text-sm">Monthly Immunization & Senior Wellness</p>
                            <p class="text-xs text-emerald-800 mt-1">Free flu vaccination and pediatric immunization happens every Wednesday at the Barangay Hall 2nd Floor. Bring your Barangay Health Booklet.</p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ============================================== -->
            <!-- SCREEN 3: JOB OPENINGS & LIVELIHOOD SCREEN     -->
            <!-- ============================================== -->
            <div id="screen-jobs" class="screen-view space-y-4 hidden">
                <!-- Screen Header -->
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <button onclick="switchScreen('home')" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition cursor-pointer" title="Back to Feed">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-briefcase text-purple-600"></i> Job Openings & Livelihood
                            </h2>
                            <p class="text-xs text-slate-500">Community employment opportunities & PESO livelihood partnerships</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 self-start sm:self-auto">
                        4 Active Postings
                    </span>
                </div>

                <!-- Job Listings Cards -->
                <div class="space-y-3">
                    <!-- Job Card 1 -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-purple-300 transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Barangay Administrative Assistant</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Barangay San Agustin Hall · Executive Secretariat</p>
                            </div>
                            <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full self-start">₱14,500 / month</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed mb-3">
                            Assist in resident records encoding, clearance validation, public inquiries, and general office administration. Computer literate (MS Word / Excel) preferred.
                        </p>
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <span><i class="fas fa-clock mr-1"></i> Full-Time</span>
                                <span>•</span>
                                <span><i class="fas fa-user-check mr-1"></i> Resident Priority</span>
                            </div>
                            <button onclick="applyJob('Barangay Administrative Assistant')" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs cursor-pointer">
                                Apply Now
                            </button>
                        </div>
                    </div>

                    <!-- Job Card 2 -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-purple-300 transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Community Health Worker (BHW)</h3>
                                <p class="text-xs text-slate-500 mt-0.5">San Agustin Health Center · Public Health Station</p>
                            </div>
                            <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full self-start">₱11,500 / month</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed mb-3">
                            Conduct community health monitoring, assist during doctor consultation days, record child immunizations, and assist seniors with maintenance medicine distributions.
                        </p>
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <span><i class="fas fa-clock mr-1"></i> Part-Time / Shift</span>
                                <span>•</span>
                                <span><i class="fas fa-heart mr-1"></i> Healthcare</span>
                            </div>
                            <button onclick="applyJob('Community Health Worker (BHW)')" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs cursor-pointer">
                                Apply Now
                            </button>
                        </div>
                    </div>

                    <!-- Job Card 3 -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-purple-300 transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Disaster Response Volunteer / Responder</h3>
                                <p class="text-xs text-slate-500 mt-0.5">BDRRMC Rescue Headquarters · Operations Team</p>
                            </div>
                            <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full self-start">Allowance + Training</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed mb-3">
                            Certified first-aid and emergency rescue training provided by CDRRMO. Serve on call during flood alerts, typhoons, and community safety drills with monthly meal and duty allowance.
                        </p>
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <span><i class="fas fa-shield-alt mr-1"></i> Emergency Service</span>
                                <span>•</span>
                                <span><i class="fas fa-certificate mr-1"></i> Free Certification</span>
                            </div>
                            <button onclick="applyJob('Disaster Response Volunteer / Responder')" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs cursor-pointer">
                                Apply Now
                            </button>
                        </div>
                    </div>

                    <!-- Job Card 4 -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-purple-300 transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Eco-Aide & Waste Management Officer</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Barangay Clean & Green Taskforce</p>
                            </div>
                            <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full self-start">₱11,000 / month</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed mb-3">
                            Maintain street cleanliness, monitor segregation schedules per purok, and support community tree planting and MRF recycling programs.
                        </p>
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <span><i class="fas fa-leaf mr-1"></i> Environmental</span>
                                <span>•</span>
                                <span><i class="fas fa-users mr-1"></i> Team Work</span>
                            </div>
                            <button onclick="applyJob('Eco-Aide & Waste Management Officer')" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs cursor-pointer">
                                Apply Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ============================================== -->
            <!-- SCREEN 4: SCHOLARSHIP ASSISTANCE SCREEN        -->
            <!-- ============================================== -->
            <div id="screen-scholar" class="screen-view space-y-4 hidden">
                <!-- Screen Header -->
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <button onclick="switchScreen('home')" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition cursor-pointer" title="Back to Feed">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-graduation-cap text-yellow-600"></i> Resident Scholarship Program
                            </h2>
                            <p class="text-xs text-slate-500">Educational financial grant for San Agustin youth and students</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 self-start sm:self-auto">
                        28 Slots Available
                    </span>
                </div>

                <!-- Grant Highlights Card (GUARANTEED HIGH CONTRAST) -->
                <div class="card-gradient-amber rounded-3xl p-6 text-white shadow-md relative overflow-hidden" 
                     style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important; background-color: #d97706 !important; color: #ffffff !important;">
                    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase font-extrabold tracking-wider text-amber-100">Semester Educational Grant</p>
                            <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">Up to ₱10,000 / sem</h3>
                            <p class="text-xs text-amber-100 mt-1 max-w-md">Financial grant allocated directly for tuition, project materials, uniforms, and book allowances for enrolled residents.</p>
                        </div>
                        <div class="bg-black/25 backdrop-blur-xs rounded-2xl p-3.5 text-center sm:min-w-40 border border-white/20">
                            <p class="text-xs font-bold text-amber-200">Eligibility Status</p>
                            <p class="text-sm font-extrabold text-white mt-0.5">Open For Enrollment</p>
                        </div>
                    </div>
                </div>

                <!-- Eligibility Checklist & Guidelines -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-yellow-600"></i> Program Eligibility & Requirements
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-600">
                        <div class="flex items-start gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>Bonafide resident of Barangay San Agustin for at least 1 continuous year</span>
                        </div>
                        <div class="flex items-start gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>Currently enrolled Senior High School or College undergraduate student</span>
                        </div>
                        <div class="flex items-start gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>General Weighted Average (GWA) of at least 83% (2.25) with no failing grades</span>
                        </div>
                        <div class="flex items-start gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>Barangay Certificate of Indigency or low-income household verification</span>
                        </div>
                    </div>
                </div>

                <!-- Interactive Scholarship Application Form -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 mb-1 flex items-center gap-2">
                        <i class="fas fa-file-signature text-yellow-600"></i> Submit Scholarship Application
                    </h3>
                    <p class="text-xs text-slate-500 mb-4">Complete your student details below. The Barangay Committee on Education will review your submission.</p>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1">Applicant Full Name</label>
                                <input type="text" id="scholarStudentName" value="{{ $user->name }}" class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1">School / College / University *</label>
                                <input type="text" id="scholarSchool" placeholder="e.g. Polytechnic University / High School" class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1">Course / Strand & Year Level *</label>
                                <input type="text" id="scholarCourse" placeholder="e.g. BS Information Technology - 2nd Year" class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1">Latest General Weighted Average (GWA) *</label>
                                <input type="text" id="scholarGWA" placeholder="e.g. 88.5% or 1.75" class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                            </div>
                        </div>

                        <button onclick="applyScholarship()" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fas fa-paper-plane"></i> Submit Application for Review
                        </button>
                    </div>
                </div>
            </div>


            <!-- ============================================== -->
            <!-- SCREEN 5: DISASTER RELIEF ASSISTANCE SCREEN    -->
            <!-- ============================================== -->
            <div id="screen-relief" class="screen-view space-y-4 hidden">
                <!-- Screen Header -->
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <button onclick="switchScreen('home')" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition cursor-pointer" title="Back to Feed">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-hand-holding-heart text-red-600"></i> Disaster Relief & Emergency Assistance
                            </h2>
                            <p class="text-xs text-slate-500">24/7 Operations Desk for Resident Welfare & Calamity Support</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 self-start sm:self-auto">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span> 24/7 Operations Active
                    </span>
                </div>

                <!-- Priority Notice Banner -->
                <div class="bg-red-50 border border-red-200/80 rounded-2xl p-4 text-red-900 flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg mt-0.5"></i>
                    <div class="text-xs">
                        <p class="font-bold text-sm">Emergency Assistance Notice</p>
                        <p class="mt-0.5">In case of severe typhoons, localized flooding, or fire emergencies, priority relief packages and evacuation transfers are deployed immediately by the Barangay Disaster Risk Reduction team.</p>
                    </div>
                </div>

                <!-- Relief Request Form Card -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                    <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2">
                        <i class="fas fa-box-open text-red-600"></i> Request Relief Assistance
                    </h3>
                    <p class="text-xs text-slate-500 mb-4">Please specify the exact emergency needs of your household.</p>

                    <form onsubmit="handleReliefSubmit(event)" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1">Type of Assistance Needed *</label>
                                <select id="reliefType" class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-red-400 focus:outline-none transition">
                                    <option value="Food Pack">Family Food Pack (Rice, Canned Goods, Water)</option>
                                    <option value="Emergency Shelter">Emergency Shelter / Tarpaulin Kit</option>
                                    <option value="Medical Assistance">First Aid & Medicine Support</option>
                                    <option value="Water Supply">Drinking Water Supply</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1">Household Members Count</label>
                                <input type="number" id="reliefHouseholdCount" placeholder="e.g. 4 family members" min="1" max="25"
                                       class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-red-400 focus:outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-1">Situation Details & Location Notes *</label>
                            <textarea id="reliefDetails" rows="3" required placeholder="State your exact purok, landmark, and present situation (e.g. waist-level flooding, damaged roof, seniors needing medicine)..." 
                                      class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-red-400 focus:outline-none transition resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fas fa-paper-plane"></i> Submit Urgent Relief Request
                        </button>
                    </form>
                </div>

                <!-- Direct Hotline Call Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <a href="tel:911" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 text-center hover:bg-red-50 hover:border-red-200 transition">
                        <i class="fas fa-ambulance text-red-600 text-xl mb-1 block"></i>
                        <span class="text-xs font-bold text-slate-800 block">Ambulance</span>
                        <span class="text-[11px] text-red-600 font-mono font-bold block">911</span>
                    </a>
                    <a href="tel:160" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 text-center hover:bg-orange-50 hover:border-orange-200 transition">
                        <i class="fas fa-fire-extinguisher text-orange-600 text-xl mb-1 block"></i>
                        <span class="text-xs font-bold text-slate-800 block">Fire Rescue</span>
                        <span class="text-[11px] text-orange-600 font-mono font-bold block">160</span>
                    </a>
                    <a href="tel:117" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 text-center hover:bg-blue-50 hover:border-blue-200 transition">
                        <i class="fas fa-shield-alt text-blue-700 text-xl mb-1 block"></i>
                        <span class="text-xs font-bold text-slate-800 block">Police Dept</span>
                        <span class="text-[11px] text-blue-700 font-mono font-bold block">117</span>
                    </a>
                    <a href="tel:09081234567" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 text-center hover:bg-slate-100 transition">
                        <i class="fas fa-phone-alt text-slate-700 text-xl mb-1 block"></i>
                        <span class="text-xs font-bold text-slate-800 block">Barangay Desk</span>
                        <span class="text-[11px] text-slate-700 font-mono font-bold block">0908-123-4567</span>
                    </a>
                </div>
            </div>


            <!-- ============================================== -->
            <!-- SCREEN 6: CREATE POST SCREEN                   -->
            <!-- ============================================== -->
            <div id="screen-post" class="screen-view space-y-4 hidden">
                <!-- Screen Header -->
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button onclick="switchScreen('home')" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition cursor-pointer" title="Cancel & Return">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-pen-alt text-blue-600"></i> Create Community Post
                            </h2>
                            <p class="text-xs text-slate-500">Post announcements, questions, or neighborhood discussions</p>
                        </div>
                    </div>
                </div>

                <!-- Post Composer Form Card -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                    <form onsubmit="handleCreatePost(event)" class="space-y-4">
                        @csrf
                        <!-- Author Information Header -->
                        <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                            <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center font-extrabold text-blue-700 text-sm">
                                {{ $user->initials }}
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-900">{{ $user->name }}</p>
                                <p class="text-[11px] text-blue-600 font-mono">{{ $user->barangay_id ?? 'BRGY-RESIDENT' }} · Verified Resident</p>
                            </div>
                        </div>

                        <!-- Category & Audience Selectors -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1">Post Category</label>
                                <select id="postTypeSelect" class="w-full border border-slate-200 p-2.5 rounded-xl text-xs sm:text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                                    <option value="general">💬 General Conversation</option>
                                    <option value="question">❓ Resident Question</option>
                                    <option value="announcement">📢 Community Announcement</option>
                                    <option value="feedback">💡 Barangay Feedback</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1">Audience</label>
                                <select id="postVisibilitySelect" class="w-full border border-slate-200 p-2.5 rounded-xl text-xs sm:text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                                    <option value="Public">🌐 Public to Everyone</option>
                                    <option value="Residents Only">🔒 Verified Residents Only</option>
                                </select>
                            </div>
                        </div>

                        <!-- Content Textarea -->
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-1">Post Content *</label>
                            <textarea id="postContent" rows="6" required placeholder="What's on your mind? Share news, inquiries, clean-up drives, lost items, or general thoughts with your neighbors..." 
                                      class="w-full border border-slate-200 p-4 rounded-2xl text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition resize-none"></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-2">
                            <button type="button" onclick="switchScreen('home')" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" id="createPostSubmitBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-xs transition flex items-center gap-2 cursor-pointer">
                                <i class="fas fa-paper-plane"></i> Publish to Community
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- ============================================== -->
<!-- BOTTOM NAVIGATION (MOBILE APPS & RESPONSIVE)   -->
<!-- ============================================== -->
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200/90 z-40 shadow-lg md:hidden">
    <div class="max-w-md mx-auto grid grid-cols-5 h-16">
        <button id="nav-btn-home" onclick="switchScreen('home')" class="nav-item active flex flex-col items-center justify-center gap-1 border-t-2 border-blue-700 transition cursor-pointer">
            <i class="nav-icon fas fa-newspaper text-blue-700 text-base"></i>
            <span class="nav-label text-[10px] text-blue-700 font-bold">Feed</span>
        </button>
        <button id="nav-btn-health" onclick="switchScreen('health')" class="nav-item flex flex-col items-center justify-center gap-1 border-t-2 border-transparent transition cursor-pointer">
            <i class="nav-icon fas fa-heartbeat text-slate-400 text-base"></i>
            <span class="nav-label text-[10px] text-slate-400">Health</span>
        </button>
        <button id="nav-btn-post" onclick="switchScreen('post')" class="nav-item flex flex-col items-center justify-center gap-1 border-t-2 border-transparent transition cursor-pointer">
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm">
                <i class="fas fa-plus text-xs"></i>
            </div>
            <span class="nav-label text-[10px] text-slate-400">Post</span>
        </button>
        <button id="nav-btn-jobs" onclick="switchScreen('jobs')" class="nav-item flex flex-col items-center justify-center gap-1 border-t-2 border-transparent transition cursor-pointer">
            <i class="nav-icon fas fa-briefcase text-slate-400 text-base"></i>
            <span class="nav-label text-[10px] text-slate-400">Jobs</span>
        </button>
        <button id="nav-btn-scholar" onclick="switchScreen('scholar')" class="nav-item flex flex-col items-center justify-center gap-1 border-t-2 border-transparent transition cursor-pointer">
            <i class="nav-icon fas fa-graduation-cap text-slate-400 text-base"></i>
            <span class="nav-label text-[10px] text-slate-400">Scholar</span>
        </button>
    </div>
</nav>

<!-- ============================================== -->
<!-- CENTERED DIALOGS (COMMENTS & EMERGENCY)        -->
<!-- ============================================== -->

<!-- Comments Centered Dialog Modal -->
<div id="commentModal" class="modal-bg" onclick="closeModalOutside(event, 'commentModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-base font-bold text-blue-800 flex items-center gap-2">
                <i class="fas fa-comments"></i> Post Comments & Discussion
            </h3>
            <button type="button" onclick="closeModal('commentModal')" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <div id="commentList" class="max-h-72 overflow-y-auto space-y-3 mb-4 pr-1">
            <!-- Comments rendered here dynamically -->
        </div>
        <div class="flex gap-2 border-t border-slate-100 pt-3">
            <input type="text" id="newCommentInput" onkeypress="if(event.key === 'Enter') handleAddComment()" placeholder="Write a comment..." 
                   class="flex-1 border border-slate-200 rounded-xl p-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
            <button onclick="handleAddComment()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 rounded-xl text-xs font-bold transition cursor-pointer">
                Send
            </button>
        </div>
    </div>
</div>

<!-- Emergency Centered Dialog Modal -->
<div id="emergencyModal" class="modal-bg" onclick="closeModalOutside(event, 'emergencyModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-base font-bold text-red-700 flex items-center gap-2">
                <i class="fas fa-phone-alt"></i> Barangay San Agustin Emergency Hotlines
            </h3>
            <button type="button" onclick="closeModal('emergencyModal')" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <div class="space-y-2.5">
            <a href="tel:911" class="flex justify-between items-center bg-red-50 hover:bg-red-100 rounded-xl p-3 text-red-900 transition">
                <span class="text-xs font-semibold flex items-center gap-2"><i class="fas fa-ambulance text-red-600"></i> Medical Emergency Ambulance</span>
                <span class="font-mono font-bold text-sm text-red-700">911</span>
            </a>
            <a href="tel:117" class="flex justify-between items-center bg-blue-50 hover:bg-blue-100 rounded-xl p-3 text-blue-900 transition">
                <span class="text-xs font-semibold flex items-center gap-2"><i class="fas fa-shield-alt text-blue-700"></i> PNP National Police</span>
                <span class="font-mono font-bold text-sm text-blue-800">117</span>
            </a>
            <a href="tel:160" class="flex justify-between items-center bg-orange-50 hover:bg-orange-100 rounded-xl p-3 text-orange-900 transition">
                <span class="text-xs font-semibold flex items-center gap-2"><i class="fas fa-fire-extinguisher text-orange-600"></i> Bureau of Fire Protection</span>
                <span class="font-mono font-bold text-sm text-orange-700">160</span>
            </a>
            <a href="tel:09081234567" class="flex justify-between items-center bg-slate-50 hover:bg-slate-100 rounded-xl p-3 text-slate-800 transition">
                <span class="text-xs font-semibold flex items-center gap-2"><i class="fas fa-building text-slate-600"></i> Barangay Operations Desk (24/7)</span>
                <span class="font-mono font-bold text-sm text-slate-800">0908-123-4567</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Posts state initialized from server
    let posts = @json($posts);
    let activeFeedTab = 'recent';
    let currentPostId = null;
    let currentActiveScreen = 'home';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // User Dropdown toggle
    function toggleUserDropdown() {
        document.getElementById('userDropdown')?.classList.toggle('hidden');
    }

    // Centered Dialog open/close
    function openModal(id) {
        document.getElementById(id)?.classList.add('open');
    }
    function closeModal(id) {
        document.getElementById(id)?.classList.remove('open');
    }
    function closeModalOutside(e, id) {
        if (e.target.id === id) closeModal(id);
    }

    // ==============================================
    // SCREEN NAVIGATION SWITCHER (DESKTOP SIDEBAR + MOBILE NAV)
    // ==============================================
    const screenTitles = {
        'home': 'Community Feed',
        'health': 'Health & Clinic Records',
        'jobs': 'Job Openings & Livelihood',
        'scholar': 'Resident Scholarship Program',
        'relief': 'Disaster Relief Assistance',
        'post': 'Create Community Post'
    };

    function switchScreen(screenName) {
        currentActiveScreen = screenName;

        // Hide all screens
        document.querySelectorAll('.screen-view').forEach(el => {
            el.classList.add('hidden');
        });

        // Show target screen
        const targetScreen = document.getElementById('screen-' + screenName);
        if (targetScreen) {
            targetScreen.classList.remove('hidden');
        }

        // Update Desktop Sidebar Active Item
        document.querySelectorAll('.sidebar-nav-item').forEach(item => {
            item.classList.remove('active');
        });
        const activeSidebarItem = document.getElementById('sidebar-nav-' + screenName);
        if (activeSidebarItem) {
            activeSidebarItem.classList.add('active');
        }

        // Update Desktop Header Badge
        const badge = document.getElementById('desktop-screen-badge');
        if (badge) {
            badge.innerText = screenTitles[screenName] || 'Resident Hub';
        }

        // Update Mobile Bottom Nav highlights
        document.querySelectorAll('.nav-item').forEach(btn => {
            btn.classList.remove('active', 'border-blue-700');
            btn.classList.add('border-transparent');
            const icon = btn.querySelector('.nav-icon');
            const label = btn.querySelector('.nav-label');
            if (icon) {
                icon.classList.remove('text-blue-700');
                icon.classList.add('text-slate-400');
            }
            if (label) {
                label.classList.remove('text-blue-700', 'font-bold');
                label.classList.add('text-slate-400');
            }
        });

        const activeMobileBtn = document.getElementById('nav-btn-' + screenName);
        if (activeMobileBtn) {
            activeMobileBtn.classList.add('active', 'border-blue-700');
            activeMobileBtn.classList.remove('border-transparent');
            const icon = activeMobileBtn.querySelector('.nav-icon');
            const label = activeMobileBtn.querySelector('.nav-label');
            if (icon) {
                icon.classList.add('text-blue-700');
                icon.classList.remove('text-slate-400');
            }
            if (label) {
                label.classList.add('text-blue-700', 'font-bold');
                label.classList.remove('text-slate-400');
            }
        }

        // Close user dropdown if open
        document.getElementById('userDropdown')?.classList.add('hidden');

        // Smooth scroll to top of page
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ==============================================
    // FEED TABS & POSTS RENDERING
    // ==============================================
    function switchFeedTab(tab, btn) {
        activeFeedTab = tab;
        document.querySelectorAll('.feed-tab-btn').forEach(b => {
            b.classList.remove('text-blue-700', 'border-b-2', 'border-blue-700', 'font-bold');
            b.classList.add('text-slate-500', 'font-semibold');
        });
        btn.classList.add('text-blue-700', 'border-b-2', 'border-blue-700', 'font-bold');
        btn.classList.remove('text-slate-500', 'font-semibold');
        renderFeed();
    }

    function renderFeed() {
        let filtered = [...posts];
        if (activeFeedTab === 'popular') {
            filtered.sort((a, b) => b.likes - a.likes);
        } else if (activeFeedTab === 'questions') {
            filtered = filtered.filter(p => p.type === 'question');
        }

        const container = document.getElementById('communityFeed');
        if (!container) return;

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="bg-white rounded-3xl p-10 text-center border border-slate-200/80 text-slate-400">
                    <i class="fas fa-inbox text-4xl mb-3 text-slate-300"></i>
                    <p class="text-sm font-semibold text-slate-600">No posts in this category yet</p>
                    <p class="text-xs text-slate-400 mt-1">Be the first to share an update with fellow residents!</p>
                    <button onclick="switchScreen('post')" class="mt-4 bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-xl">
                        Create Post
                    </button>
                </div>
            `;
            return;
        }

        container.innerHTML = filtered.map(post => `
            <div class="bg-white rounded-3xl p-4 sm:p-5 shadow-xs border border-slate-200/80 post-card" id="post-${post.id}">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-700 font-extrabold text-xs">
                        ${post.avatar || 'SA'}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-bold text-slate-900 text-sm">${post.author}</p>
                            <span class="text-xs text-slate-400">${post.time}</span>
                            ${post.type === 'question' ? '<span class="text-[10px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-bold">Question</span>' : ''}
                            ${post.type === 'announcement' ? '<span class="text-[10px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-bold">Announcement</span>' : ''}
                            ${post.type === 'feedback' ? '<span class="text-[10px] bg-amber-50 text-amber-800 px-2 py-0.5 rounded-full font-bold">Feedback</span>' : ''}
                            ${post.type === 'general' ? '<span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-medium">Community</span>' : ''}
                        </div>
                        <p class="text-slate-700 text-xs sm:text-sm mt-2 leading-relaxed whitespace-pre-line">${post.content}</p>
                    </div>
                </div>
                <div class="flex items-center gap-6 pt-3 border-t border-slate-100">
                    <button onclick="toggleLike(${post.id})" class="like-btn flex items-center gap-1.5 ${post.liked ? 'liked text-red-500 font-bold' : 'text-slate-500'} hover:text-red-500 transition text-xs cursor-pointer">
                        <i class="fas fa-heart"></i>
                        <span>${post.likes}</span>
                    </button>
                    <button onclick="openCommentModal(${post.id})" class="flex items-center gap-1.5 text-slate-500 hover:text-blue-600 transition text-xs cursor-pointer">
                        <i class="fas fa-comment"></i>
                        <span>${post.comments ? post.comments.length : 0}</span>
                    </button>
                    <button onclick="sharePost(${post.id})" class="flex items-center gap-1.5 text-slate-500 hover:text-green-600 transition text-xs cursor-pointer ml-auto">
                        <i class="fas fa-share-alt"></i>
                        <span>Share</span>
                    </button>
                </div>
            </div>
        `).join('');
    }

    // Toggle Like
    async function toggleLike(postId) {
        try {
            const res = await fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                const target = posts.find(p => p.id === postId);
                if (target) {
                    target.liked = data.liked;
                    target.likes = data.likes_count;
                    renderFeed();
                }
            }
        } catch (e) {
            showToast('Unable to update like', 'error');
        }
    }

    // Create Post Form Submission
    async function handleCreatePost(e) {
        e.preventDefault();
        const content = document.getElementById('postContent').value.trim();
        const type = document.getElementById('postTypeSelect').value;
        const visibility = document.getElementById('postVisibilitySelect').value;
        const btn = document.getElementById('createPostSubmitBtn');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Publishing...';

        try {
            const res = await fetch("{{ route('posts.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content, type, visibility })
            });
            const data = await res.json();
            if (data.success && data.post) {
                posts.unshift(data.post);
                document.getElementById('postContent').value = '';
                showToast('Your post has been published to the community!');

                const countEl = document.getElementById('totalActivityCount');
                if (countEl) countEl.innerText = parseInt(countEl.innerText || 0) + 1;

                // Return to feed and re-render
                switchScreen('home');
                renderFeed();
            } else {
                showToast(data.message || 'Failed to publish post', 'error');
            }
        } catch (e) {
            showToast('Failed to create post. Please check connection.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = origText;
        }
    }

    // Open Comments Dialog
    function openCommentModal(postId) {
        currentPostId = postId;
        const post = posts.find(p => p.id === postId);
        if (!post) return;

        const list = document.getElementById('commentList');
        if (!post.comments || post.comments.length === 0) {
            list.innerHTML = '<p class="text-xs text-slate-400 text-center py-6">No comments yet. Be the first to start the discussion!</p>';
        } else {
            list.innerHTML = post.comments.map(c => `
                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-xs text-slate-900">${c.author}</span>
                        <span class="text-[10px] text-slate-400 font-medium">${c.time}</span>
                    </div>
                    <p class="text-xs text-slate-700 leading-relaxed">${c.text}</p>
                </div>
            `).join('');
        }
        openModal('commentModal');
    }

    // Add Comment
    async function handleAddComment() {
        const input = document.getElementById('newCommentInput');
        const text = input.value.trim();
        if (!text || !currentPostId) return;

        try {
            const res = await fetch(`/posts/${currentPostId}/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content: text })
            });
            const data = await res.json();
            if (data.success && data.comment) {
                const target = posts.find(p => p.id === currentPostId);
                if (target) {
                    if (!target.comments) target.comments = [];
                    target.comments.push(data.comment);
                    openCommentModal(currentPostId);
                    renderFeed();
                }
                input.value = '';
                showToast('Comment added!');
            }
        } catch (e) {
            showToast('Failed to add comment', 'error');
        }
    }

    // Relief Request Submission
    async function handleReliefSubmit(e) {
        e.preventDefault();
        const type = document.getElementById('reliefType').value;
        const details = document.getElementById('reliefDetails').value;

        try {
            const res = await fetch("{{ route('services.relief') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ assistance_type: type, details: details })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Relief assistance request submitted!');
                document.getElementById('reliefDetails').value = '';
            }
        } catch (e) {
            showToast('Failed to submit relief request', 'error');
        }
    }

    // Health Vitals Record Submission
    async function handleHealthSubmit(e) {
        e.preventDefault();
        const bp = document.getElementById('healthBP').value;
        const notes = document.getElementById('healthNotes').value;

        try {
            const res = await fetch("{{ route('services.health') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ blood_pressure: bp, notes: notes })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Health record logged successfully!');
                document.getElementById('healthBP').value = '';
                document.getElementById('healthNotes').value = '';
                if (document.getElementById('healthPulse')) document.getElementById('healthPulse').value = '';
            }
        } catch (e) {
            showToast('Failed to save health record', 'error');
        }
    }

    // Apply for Job
    async function applyJob(jobTitle) {
        try {
            const res = await fetch("{{ route('services.job') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ job_title: jobTitle })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || `Application sent for ${jobTitle}!`);
            }
        } catch (e) {
            showToast('Failed to submit job application', 'error');
        }
    }

    // Apply for Scholarship
    async function applyScholarship() {
        const school = document.getElementById('scholarSchool')?.value || '';
        const course = document.getElementById('scholarCourse')?.value || '';
        const gwa = document.getElementById('scholarGWA')?.value || '';
        const notes = `School: ${school} | Course: ${course} | GWA: ${gwa}`;

        try {
            const res = await fetch("{{ route('services.scholarship') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ notes: notes || 'Scholarship portal submission' })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Scholarship application received!');
                if (document.getElementById('scholarSchool')) document.getElementById('scholarSchool').value = '';
                if (document.getElementById('scholarCourse')) document.getElementById('scholarCourse').value = '';
                if (document.getElementById('scholarGWA')) document.getElementById('scholarGWA').value = '';
            }
        } catch (e) {
            showToast('Failed to submit scholarship application', 'error');
        }
    }

    // Share Post Link
    function sharePost(id) {
        const url = window.location.origin + '/dashboard#post-' + id;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url);
            showToast('Post link copied to clipboard!');
        } else {
            showToast('Post reference: #' + id);
        }
    }

    // Initial render
    renderFeed();
</script>
@endpush
