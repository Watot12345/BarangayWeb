@php
    $isStaff = in_array($user->role, ['admin', 'staff']);
@endphp

@extends('layouts.app')

@section('title', 'Barangay San Agustin - ' . ($isStaff ? 'Staff Admin Portal' : 'Resident Hub'))

@section('body-class', 'bg-slate-50 min-h-screen text-slate-800 antialiased')

@push('styles')
<style>
    /* ============================================== */
    /* UNIFIED CIVIC DESIGN SYSTEM & MOBILE FIRST     */
    /* ============================================== */
    :root {
        --civic-primary: #1e3a8a;
        --civic-primary-hover: #172554;
        --civic-accent: #2563eb;
        --civic-bg: #f8fafc;
        --civic-surface: #ffffff;
        --civic-border: #e2e8f0;
        --civic-text-main: #0f172a;
        --civic-text-muted: #64748b;
    }

    /* Desktop Sidebar Nav Item */
    .sidebar-nav-item {
        color: #475569;
        transition: all 0.15s ease;
        border-left: 3px solid transparent;
    }
    .sidebar-nav-item:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }
    .sidebar-nav-item.active {
        background-color: #eff6ff !important;
        color: #1e40af !important;
        font-weight: 700 !important;
        border-left: 3px solid #1e40af !important;
    }
    .sidebar-nav-item.active i {
        color: #1e40af !important;
    }

    /* Mobile Bottom Navigation */
    .nav-item {
        transition: all 0.15s ease;
    }
    .nav-item.active {
        border-top: 3px solid #1e40af !important;
    }
    .nav-item.active .nav-icon {
        color: #1e40af !important;
    }
    .nav-item.active .nav-label {
        color: #1e40af !important;
        font-weight: 700 !important;
    }

    /* Mobile Quick Chips */
    .mobile-chip {
        transition: all 0.15s ease;
        flex-shrink: 0;
        font-size: 11px;
    }
    .mobile-chip.active {
        background-color: #1e3a8a !important;
        color: #ffffff !important;
        font-weight: 700;
        border-color: #1e3a8a !important;
    }

    /* Hide scrollbar for category chips */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Sub-tabs */
    .sub-tab-btn {
        transition: all 0.15s ease;
        border-bottom: 2px solid transparent;
    }
    .sub-tab-btn.active {
        border-bottom: 2px solid #1e40af;
        color: #1e40af;
        font-weight: 700;
    }

    /* Card styling */
    .civic-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03);
    }

    /* Clean subtle badges */
    .badge-clean-blue { background-color: #eff6ff; color: #1e40af; border: 1px solid #dbeafe; }
    .badge-clean-green { background-color: #f0fdf4; color: #166534; border: 1px solid #dcfce7; }
    .badge-clean-amber { background-color: #fffbeb; color: #92400e; border: 1px solid #fef3c7; }
    .badge-clean-red { background-color: #fef2f2; color: #991b1b; border: 1px solid #fee2e2; }
    .badge-clean-slate { background-color: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

    /* Post Card */
    .post-card {
        transition: border-color 0.15s ease;
    }
    .post-card:hover {
        border-color: #cbd5e1;
    }

    .like-btn.liked { color: #dc2626; }

    /* Touch-friendly modal bottom-sheet on mobile */
    @media (max-width: 640px) {
        .modal-bg {
            padding: 0 !important;
            align-items: flex-end !important;
        }
        .modal-sheet {
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            border-top-left-radius: 1.25rem !important;
            border-top-right-radius: 1.25rem !important;
            max-height: 92vh !important;
            margin: 0 !important;
            padding: 1.25rem 1rem 2rem 1rem !important;
            width: 100% !important;
            max-width: 100% !important;
            animation: slideUpSheet 0.22s ease-out !important;
        }
    }
    @keyframes slideUpSheet {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex flex-col md:flex-row bg-slate-50 text-slate-800">

    <!-- ============================================== -->
    <!-- DESKTOP SIDEBAR (WEBSITE MODE: MD & ABOVE)     -->
    <!-- ============================================== -->
    <aside class="hidden md:flex md:w-64 lg:w-72 flex-col bg-white border-r border-slate-200 min-h-screen sticky top-0 h-screen overflow-y-auto p-5 z-30 flex-shrink-0">
        
        <!-- Official Logo & Header -->
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
            <img src="{{ asset('1.jpg') }}" alt="Barangay Seal" class="w-10 h-10 rounded-full object-cover border border-slate-200 flex-shrink-0">
            <div class="min-w-0">
                <span class="text-sm font-bold text-slate-900 leading-tight block whitespace-nowrap">Brgy. San Agustin</span>
                <span class="text-[11px] text-slate-500 font-medium block whitespace-nowrap">
                    {{ $isStaff ? 'Staff Admin Portal' : 'Resident Hub' }}
                </span>
            </div>
        </div>

        <!-- Identity / User Profile Card -->
        <div class="my-4 p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-900 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                {{ $user->initials }}
            </div>
            <div class="overflow-hidden flex-1">
                <p class="text-xs font-bold text-slate-900 truncate">{{ $user->name }}</p>
                <p class="text-[10px] text-blue-700 font-mono font-medium">{{ $user->barangay_id ?? 'BRGY-0001' }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ?? true ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                    <span class="text-[10px] text-slate-600 font-medium truncate">
                        {{ $user->designation ?? ($isStaff ? 'Staff Officer' : 'Resident') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Primary Action -->
        @if($isStaff)
            <button onclick="switchScreen('admin-broadcast')" class="w-full bg-blue-900 hover:bg-blue-950 text-white py-2 px-3 rounded-lg text-xs font-semibold shadow-xs transition flex items-center justify-center gap-2 mb-3 cursor-pointer">
                <i class="fas fa-bullhorn text-xs"></i> Post Official Advisory
            </button>
        @else
            <button onclick="switchScreen('post')" class="w-full bg-blue-900 hover:bg-blue-950 text-white py-2 px-3 rounded-lg text-xs font-semibold shadow-xs transition flex items-center justify-center gap-2 mb-3 cursor-pointer">
                <i class="fas fa-pen text-xs"></i> Create Post
            </button>
        @endif

        <!-- Navigation Links -->
        <nav class="space-y-1 flex-1">
            @if($isStaff)
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1 mb-1">
                    Administration
                </p>

                <button onclick="switchScreen('admin-overview')" id="sidebar-nav-admin-overview" class="sidebar-nav-item active w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                    <span class="flex items-center gap-2.5"><i class="fas fa-chart-line w-4 text-center text-slate-500"></i> Executive Overview</span>
                </button>

                <button onclick="switchScreen('admin-documents')" id="sidebar-nav-admin-documents" class="sidebar-nav-item w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                    <span class="flex items-center gap-2.5"><i class="fas fa-file-text w-4 text-center text-slate-500"></i> Clearance Requests</span>
                    @if(isset($adminData['stats']['pending_documents']) && $adminData['stats']['pending_documents'] > 0)
                        <span class="badge-clean-amber text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminData['stats']['pending_documents'] }}</span>
                    @endif
                </button>

                <button onclick="switchScreen('admin-relief')" id="sidebar-nav-admin-relief" class="sidebar-nav-item w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                    <span class="flex items-center gap-2.5"><i class="fas fa-box-open w-4 text-center text-slate-500"></i> Relief Operations</span>
                    @if(isset($adminData['stats']['pending_relief']) && $adminData['stats']['pending_relief'] > 0)
                        <span class="badge-clean-red text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminData['stats']['pending_relief'] }}</span>
                    @endif
                </button>

                <button onclick="switchScreen('admin-jobs')" id="sidebar-nav-admin-jobs" class="sidebar-nav-item w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                    <span class="flex items-center gap-2.5"><i class="fas fa-briefcase w-4 text-center text-slate-500"></i> Jobs & Recruiters</span>
                    <span class="badge-clean-slate text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminData['stats']['total_openings'] ?? 0 }}</span>
                </button>

                <button onclick="switchScreen('admin-scholar')" id="sidebar-nav-admin-scholar" class="sidebar-nav-item w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                    <span class="flex items-center gap-2.5"><i class="fas fa-graduation-cap w-4 text-center text-slate-500"></i> Scholarships</span>
                    <span class="badge-clean-slate text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminData['stats']['total_scholarships'] ?? 0 }}</span>
                </button>

                <button onclick="switchScreen('admin-staff')" id="sidebar-nav-admin-staff" class="sidebar-nav-item w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                    <span class="flex items-center gap-2.5"><i class="fas fa-id-badge w-4 text-center text-slate-500"></i> Staff Registry</span>
                    <span class="badge-clean-blue text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminData['stats']['total_staff'] ?? 0 }}</span>
                </button>

                <button onclick="switchScreen('admin-residents')" id="sidebar-nav-admin-residents" class="sidebar-nav-item w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                    <span class="flex items-center gap-2.5"><i class="fas fa-users w-4 text-center text-slate-500"></i> Resident Registry</span>
                </button>

                <div class="pt-3 my-2 border-t border-slate-200"></div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-0.5">Community Views</p>
            @endif

            <!-- RESIDENT & COMMUNITY VIEWS -->
            <button onclick="switchScreen('home')" id="sidebar-nav-home" class="sidebar-nav-item {{ !$isStaff ? 'active' : '' }} w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                <i class="fas fa-newspaper w-4 text-center text-slate-500"></i>
                <span>Community Feed</span>
            </button>

            <button onclick="switchScreen('health')" id="sidebar-nav-health" class="sidebar-nav-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                <i class="fas fa-heartbeat w-4 text-center text-slate-500"></i>
                <span>Health Center Log</span>
            </button>

            <button onclick="switchScreen('jobs')" id="sidebar-nav-jobs" class="sidebar-nav-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                <i class="fas fa-briefcase w-4 text-center text-slate-500"></i>
                <span>Job Openings</span>
            </button>

            <button onclick="switchScreen('scholar')" id="sidebar-nav-scholar" class="sidebar-nav-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                <i class="fas fa-graduation-cap w-4 text-center text-slate-500"></i>
                <span>Scholarship Program</span>
            </button>

            <button onclick="switchScreen('relief')" id="sidebar-nav-relief" class="sidebar-nav-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium cursor-pointer">
                <i class="fas fa-hand-holding-heart w-4 text-center text-slate-500"></i>
                <span>Relief Assistance</span>
            </button>
        </nav>

        <!-- Utility Links -->
        <div class="pt-4 border-t border-slate-200 space-y-1">
            <button onclick="openModal('emergencyModal')" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                <i class="fas fa-phone-alt text-slate-500 w-4 text-center"></i> Emergency Hotlines
            </button>
            <a href="{{ route('home') }}" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                <i class="fas fa-external-link-alt text-slate-500 w-4 text-center"></i> Public Landing Page
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full pt-1">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-red-700 hover:bg-slate-100 transition cursor-pointer">
                    <i class="fas fa-sign-out-alt text-slate-500 w-4 text-center"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- ============================================== -->
    <!-- MOBILE OFF-CANVAS DRAWER MENU                  -->
    <!-- ============================================== -->
    <div id="mobileDrawer" class="fixed inset-0 z-50 pointer-events-none md:hidden">
        <!-- Backdrop -->
        <div id="mobileDrawerBackdrop" onclick="closeMobileDrawer()" 
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs opacity-0 transition-opacity duration-200 pointer-events-none"></div>

        <!-- Slide-over Drawer Panel -->
        <div id="mobileDrawerPanel" 
             class="fixed inset-y-0 left-0 w-4/5 max-w-xs bg-white shadow-2xl flex flex-col transform -translate-x-full transition-transform duration-250 ease-out z-10 pointer-events-auto">
            
            <!-- Drawer Header -->
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2.5 min-w-0">
                    <img src="{{ asset('1.jpg') }}" alt="Seal" class="w-9 h-9 rounded-full object-cover border border-slate-200 flex-shrink-0">
                    <div class="min-w-0 flex flex-col justify-center">
                        <span class="text-sm font-bold text-slate-900 block whitespace-nowrap leading-tight">Brgy. San Agustin</span>
                        <span class="text-[11px] text-blue-800 font-semibold block whitespace-nowrap leading-tight">{{ $isStaff ? 'Staff Admin Portal' : 'Resident Hub' }}</span>
                    </div>
                </div>
                <button onclick="closeMobileDrawer()" class="w-8 h-8 rounded-full bg-slate-200/80 hover:bg-slate-300 text-slate-700 flex items-center justify-center text-base font-bold flex-shrink-0 cursor-pointer">
                    &times;
                </button>
            </div>

            <!-- Profile Summary in Drawer -->
            <div class="p-3.5 border-b border-slate-100 bg-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-900 text-white font-bold text-xs flex items-center justify-center">
                        {{ $user->initials }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ $user->name }}</p>
                        <p class="text-[10px] text-slate-500 truncate">{{ $user->designation ?? ($isStaff ? 'Barangay Staff' : 'Resident') }}</p>
                    </div>
                </div>
            </div>

            <!-- Drawer Links (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-3 space-y-1 text-xs">
                @if($isStaff)
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1">Staff Operations</p>
                    
                    <button onclick="switchScreenMobile('admin-overview')" class="drawer-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                        <i class="fas fa-chart-line w-4 text-center text-blue-900"></i> Executive Overview
                    </button>
                    <button onclick="switchScreenMobile('admin-documents')" class="drawer-link w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                        <span class="flex items-center gap-3"><i class="fas fa-file-text w-4 text-center text-slate-600"></i> Clearance Requests</span>
                        @if(isset($adminData['stats']['pending_documents']) && $adminData['stats']['pending_documents'] > 0)
                            <span class="badge-clean-amber text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminData['stats']['pending_documents'] }}</span>
                        @endif
                    </button>
                    <button onclick="switchScreenMobile('admin-jobs')" class="drawer-link w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                        <span class="flex items-center gap-3"><i class="fas fa-briefcase w-4 text-center text-slate-600"></i> Jobs & Recruiters</span>
                        <span class="badge-clean-slate text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminData['stats']['total_openings'] ?? 0 }}</span>
                    </button>
                    <button onclick="switchScreenMobile('admin-relief')" class="drawer-link w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                        <span class="flex items-center gap-3"><i class="fas fa-box-open w-4 text-center text-slate-600"></i> Relief Operations</span>
                        @if(isset($adminData['stats']['pending_relief']) && $adminData['stats']['pending_relief'] > 0)
                            <span class="badge-clean-red text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminData['stats']['pending_relief'] }}</span>
                        @endif
                    </button>
                    <button onclick="switchScreenMobile('admin-scholar')" class="drawer-link w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                        <span class="flex items-center gap-3"><i class="fas fa-graduation-cap w-4 text-center text-slate-600"></i> Scholarships</span>
                        <span class="badge-clean-slate text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminData['stats']['total_scholarships'] ?? 0 }}</span>
                    </button>
                    <button onclick="switchScreenMobile('admin-staff')" class="drawer-link w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                        <span class="flex items-center gap-3"><i class="fas fa-id-badge w-4 text-center text-blue-900"></i> Staff Registry</span>
                        <span class="badge-clean-blue text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminData['stats']['total_staff'] ?? 0 }}</span>
                    </button>
                    <button onclick="switchScreenMobile('admin-residents')" class="drawer-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                        <i class="fas fa-users w-4 text-center text-slate-600"></i> Resident Registry
                    </button>
                    <button onclick="switchScreenMobile('admin-broadcast')" class="drawer-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                        <i class="fas fa-bullhorn w-4 text-center text-slate-600"></i> Post Advisory
                    </button>

                    <div class="pt-2 my-1 border-t border-slate-100"></div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1">Community Hub</p>
                @endif

                <button onclick="switchScreenMobile('home')" class="drawer-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                    <i class="fas fa-newspaper w-4 text-center text-slate-600"></i> Community Feed
                </button>
                <button onclick="openModal('requestDocModal'); closeMobileDrawer();" class="drawer-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-900 bg-blue-50/70 hover:bg-blue-100 font-semibold text-left">
                    <i class="fas fa-file-invoice w-4 text-center text-blue-900"></i> Request Barangay Clearance
                </button>
                <button onclick="switchScreenMobile('health')" class="drawer-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                    <i class="fas fa-heartbeat w-4 text-center text-slate-600"></i> Health Records Log
                </button>
                <button onclick="switchScreenMobile('jobs')" class="drawer-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                    <i class="fas fa-briefcase w-4 text-center text-slate-600"></i> Job Openings
                </button>
                <button onclick="switchScreenMobile('scholar')" class="drawer-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                    <i class="fas fa-graduation-cap w-4 text-center text-slate-600"></i> Scholarship Program
                </button>
                <button onclick="switchScreenMobile('relief')" class="drawer-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-100 font-medium text-left">
                    <i class="fas fa-hand-holding-heart w-4 text-center text-slate-600"></i> Relief Assistance
                </button>
            </div>

            <!-- Drawer Footer -->
            <div class="p-3 border-t border-slate-200 bg-slate-50 space-y-1 text-xs">
                <button onclick="openModal('emergencyModal'); closeMobileDrawer();" class="w-full flex items-center gap-2 p-2 rounded-lg text-slate-700 hover:bg-slate-200">
                    <i class="fas fa-phone-alt text-red-600 w-4 text-center"></i> Hotlines
                </button>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 p-2 rounded-lg text-rose-700 hover:bg-rose-50 font-semibold">
                        <i class="fas fa-sign-out-alt w-4 text-center"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MAIN CONTENT AREA                              -->
    <!-- ============================================== -->
    <main class="flex-1 flex flex-col min-w-0 pb-20 md:pb-8">

        <!-- Mobile App Top Bar -->
        <header class="md:hidden bg-white border-b border-slate-200 px-3.5 h-14 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-2.5 min-w-0 flex-1">
                <button onclick="openMobileDrawer()" class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 flex items-center justify-center transition cursor-pointer flex-shrink-0" aria-label="Open Navigation Menu">
                    <i class="fas fa-bars text-sm"></i>
                </button>
                <img src="{{ asset('1.jpg') }}" alt="Seal" class="w-8 h-8 rounded-full object-cover border border-slate-200 flex-shrink-0">
                <div class="min-w-0 flex flex-col justify-center">
                    <span class="text-xs sm:text-sm font-bold text-slate-900 block whitespace-nowrap leading-tight">Brgy. San Agustin</span>
                    <span id="mobile-current-screen-label" class="text-[10px] text-blue-800 font-semibold block whitespace-nowrap leading-tight truncate">
                        {{ $isStaff ? 'Staff Admin Portal' : 'Resident Hub' }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button onclick="openModal('emergencyModal')" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center cursor-pointer" title="Hotlines">
                    <i class="fas fa-phone-alt text-xs"></i>
                </button>
                <button onclick="toggleUserDropdown()" class="w-8 h-8 rounded-lg bg-blue-900 text-white font-bold text-xs flex items-center justify-center cursor-pointer shadow-xs">
                    {{ $user->initials }}
                </button>
            </div>
        </header>

        <!-- Mobile Horizontal Scrolling Quick Navigation Chips -->
        <div class="md:hidden bg-white border-b border-slate-200 px-3 py-2 overflow-x-auto no-scrollbar flex items-center gap-1.5 sticky top-14 z-20 shadow-2xs">
            @if($isStaff)
                <button onclick="switchScreen('admin-overview')" id="mobile-chip-admin-overview" class="mobile-chip active px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-chart-line mr-1 text-[10px]"></i> Overview
                </button>
                <button onclick="switchScreen('admin-documents')" id="mobile-chip-admin-documents" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-file-text mr-1 text-[10px]"></i> Clearances
                </button>
                <button onclick="switchScreen('admin-jobs')" id="mobile-chip-admin-jobs" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-briefcase mr-1 text-[10px]"></i> Jobs & PESO
                </button>
                <button onclick="switchScreen('admin-relief')" id="mobile-chip-admin-relief" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-box-open mr-1 text-[10px]"></i> Relief
                </button>
                <button onclick="switchScreen('admin-scholar')" id="mobile-chip-admin-scholar" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-graduation-cap mr-1 text-[10px]"></i> Scholarships
                </button>
                <button onclick="switchScreen('admin-staff')" id="mobile-chip-admin-staff" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-id-badge mr-1 text-[10px]"></i> Staff
                </button>
                <button onclick="switchScreen('admin-residents')" id="mobile-chip-admin-residents" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-users mr-1 text-[10px]"></i> Residents
                </button>
                <button onclick="switchScreen('home')" id="mobile-chip-home" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-newspaper mr-1 text-[10px]"></i> Feed
                </button>
            @else
                <button onclick="switchScreen('home')" id="mobile-chip-home" class="mobile-chip active px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-newspaper mr-1 text-[10px]"></i> Feed
                </button>
                <button onclick="openModal('requestDocModal')" class="mobile-chip px-3 py-1.5 rounded-full border border-blue-200 bg-blue-50 text-blue-900 font-semibold cursor-pointer">
                    <i class="fas fa-file-invoice mr-1 text-[10px]"></i> Clearances
                </button>
                <button onclick="switchScreen('jobs')" id="mobile-chip-jobs" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-briefcase mr-1 text-[10px]"></i> Job Openings
                </button>
                <button onclick="switchScreen('health')" id="mobile-chip-health" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-heartbeat mr-1 text-[10px]"></i> Health Log
                </button>
                <button onclick="switchScreen('scholar')" id="mobile-chip-scholar" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-graduation-cap mr-1 text-[10px]"></i> Scholarships
                </button>
                <button onclick="switchScreen('relief')" id="mobile-chip-relief" class="mobile-chip px-3 py-1.5 rounded-full border border-slate-200 bg-slate-100 text-slate-700 font-medium">
                    <i class="fas fa-hand-holding-heart mr-1 text-[10px]"></i> Relief
                </button>
            @endif
        </div>

        <!-- Desktop Top Navigation Bar -->
        <header class="hidden md:flex bg-white border-b border-slate-200 px-8 py-3.5 items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <span id="desktop-screen-badge" class="px-3 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">
                    {{ $isStaff ? 'Executive Overview' : 'Community Feed' }}
                </span>
                <span class="text-xs text-slate-500">
                    User: <strong class="text-slate-800">{{ $user->name }}</strong> · <span class="font-mono text-slate-600">{{ $user->barangay_id ?? 'BRGY-0001' }}</span>
                </span>
            </div>
            <div class="flex items-center gap-2.5">
                @if($isStaff)
                    <button onclick="openModal('postJobModal')" class="text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 transition cursor-pointer flex items-center gap-1.5">
                        <i class="fas fa-plus text-slate-500"></i> Post Job
                    </button>
                    <button onclick="openModal('registerRecruiterModal')" class="text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 transition cursor-pointer flex items-center gap-1.5">
                        <i class="fas fa-building text-slate-500"></i> Add Recruiter
                    </button>
                @endif
                <button onclick="openModal('emergencyModal')" class="text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 transition cursor-pointer flex items-center gap-1.5">
                    <i class="fas fa-phone-alt text-slate-500"></i> Hotlines
                </button>
            </div>
        </header>

        <!-- Dynamic Screen Views Container -->
        <div class="max-w-5xl w-full mx-auto p-3.5 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">

            <!-- Toast Notification Container -->
            <div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-2 pointer-events-none"></div>

            @if($isStaff && isset($adminData))
                <!-- ============================================== -->
                <!-- STAFF SCREEN 1: EXECUTIVE CONSOLE OVERVIEW     -->
                <!-- ============================================== -->
                <div id="screen-admin-overview" class="screen-view space-y-4 sm:space-y-5">
                    
                    <!-- Calm Header Card -->
                    <div class="civic-card p-4 sm:p-6 bg-white border border-slate-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="badge-clean-blue text-[11px] font-bold px-2.5 py-0.5 rounded-md inline-block mb-1.5">
                                    Administration Console
                                </span>
                                <h2 class="text-lg sm:text-xl font-bold text-slate-900">Barangay Operations & Services Desk</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Manage document clearances, relief dispatches, job openings, and accredited recruiters.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="openModal('postJobModal')" class="flex-1 sm:flex-none justify-center bg-blue-900 hover:bg-blue-950 text-white text-xs font-semibold px-3 py-2.5 sm:py-2 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                                    <i class="fas fa-plus"></i> Post Job
                                </button>
                                <button onclick="switchScreen('admin-broadcast')" class="flex-1 sm:flex-none justify-center bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold px-3 py-2.5 sm:py-2 rounded-lg transition cursor-pointer flex items-center gap-1.5 border border-slate-200">
                                    <i class="fas fa-bullhorn text-slate-600"></i> Broadcast
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 4 Unified Metric Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3">
                        <div class="civic-card p-3 sm:p-4">
                            <span class="text-[10px] sm:text-[11px] font-medium text-slate-500 uppercase tracking-wider block">Registered Residents</span>
                            <p class="text-xl sm:text-2xl font-bold text-slate-900 mt-0.5 sm:mt-1">{{ $adminData['stats']['total_residents'] }}</p>
                            <span class="text-[10px] text-slate-500 font-medium mt-1 flex items-center gap-1">
                                <i class="fas fa-check-circle text-emerald-600"></i> Active Masterlist
                            </span>
                        </div>

                        <div class="civic-card p-3 sm:p-4 cursor-pointer hover:border-slate-300 transition" onclick="switchScreen('admin-documents')">
                            <span class="text-[10px] sm:text-[11px] font-medium text-slate-500 uppercase tracking-wider block">Clearance Requests</span>
                            <p class="text-xl sm:text-2xl font-bold text-slate-900 mt-0.5 sm:mt-1">{{ $adminData['stats']['pending_documents'] }}</p>
                            <span class="text-[10px] text-amber-700 font-medium mt-1 flex items-center gap-1">
                                <i class="fas fa-clock text-amber-600"></i> Pending Validation
                            </span>
                        </div>

                        <div class="civic-card p-3 sm:p-4 cursor-pointer hover:border-slate-300 transition" onclick="switchScreen('admin-relief')">
                            <span class="text-[10px] sm:text-[11px] font-medium text-slate-500 uppercase tracking-wider block">Relief Dispatches</span>
                            <p class="text-xl sm:text-2xl font-bold text-slate-900 mt-0.5 sm:mt-1">{{ $adminData['stats']['pending_relief'] }}</p>
                            <span class="text-[10px] text-rose-700 font-medium mt-1 flex items-center gap-1">
                                <i class="fas fa-truck text-rose-600"></i> Dispatch Queue
                            </span>
                        </div>

                        <div class="civic-card p-3 sm:p-4 cursor-pointer hover:border-slate-300 transition" onclick="switchScreen('admin-jobs')">
                            <span class="text-[10px] sm:text-[11px] font-medium text-slate-500 uppercase tracking-wider block">Jobs & Partners</span>
                            <div class="flex items-baseline gap-1.5 mt-0.5 sm:mt-1">
                                <p class="text-xl sm:text-2xl font-bold text-slate-900">{{ $adminData['stats']['total_openings'] ?? 0 }}</p>
                                <span class="text-[10px] text-slate-500">jobs / {{ $adminData['stats']['total_recruiters'] ?? 0 }}</span>
                            </div>
                            <span class="text-[10px] text-blue-700 font-medium mt-1 flex items-center gap-1">
                                <i class="fas fa-briefcase text-blue-600"></i> PESO Desk
                            </span>
                        </div>
                    </div>

                    <!-- Quick Navigation Cards -->
                    <div class="civic-card p-3.5 sm:p-4">
                        <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2.5">Staff Quick Modules</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <button onclick="switchScreen('admin-documents')" class="p-2.5 sm:p-3 bg-slate-50 hover:bg-slate-100 text-slate-800 rounded-lg text-xs font-semibold transition text-left flex items-center gap-2 border border-slate-200 cursor-pointer">
                                <i class="fas fa-file-invoice text-slate-600"></i> Clearances
                            </button>
                            <button onclick="switchScreen('admin-relief')" class="p-2.5 sm:p-3 bg-slate-50 hover:bg-slate-100 text-slate-800 rounded-lg text-xs font-semibold transition text-left flex items-center gap-2 border border-slate-200 cursor-pointer">
                                <i class="fas fa-box-open text-slate-600"></i> Relief Aid
                            </button>
                            <button onclick="switchScreen('admin-jobs')" class="p-2.5 sm:p-3 bg-slate-50 hover:bg-slate-100 text-slate-800 rounded-lg text-xs font-semibold transition text-left flex items-center gap-2 border border-slate-200 cursor-pointer">
                                <i class="fas fa-briefcase text-slate-600"></i> Jobs & PESO
                            </button>
                            <button onclick="switchScreen('admin-staff')" class="p-2.5 sm:p-3 bg-slate-50 hover:bg-slate-100 text-slate-800 rounded-lg text-xs font-semibold transition text-left flex items-center gap-2 border border-slate-200 cursor-pointer">
                                <i class="fas fa-id-badge text-slate-600"></i> Staff Registry
                            </button>
                        </div>
                    </div>

                    <!-- Recent Document Submissions (Responsive: Card List on Mobile, Table on Desktop) -->
                    <div class="civic-card p-4 sm:p-5">
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-list text-slate-500"></i> Recent Document Clearance Requests
                            </h3>
                            <button onclick="switchScreen('admin-documents')" class="text-xs font-semibold text-blue-700 hover:underline">
                                View all ({{ $adminData['service_requests']->count() }})
                            </button>
                        </div>

                        <!-- Mobile Card List View (< sm) -->
                        <div class="sm:hidden space-y-2.5">
                            @forelse($adminData['service_requests']->take(5) as $req)
                                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 text-xs space-y-2" id="req-mobile-card-{{ $req->id }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-slate-900 text-sm">{{ $req->full_name ?? ($req->user->name ?? 'Resident') }}</span>
                                        <span class="status-badge px-2 py-0.5 rounded text-[10px] font-semibold {{ $req->status === 'Pending' ? 'badge-clean-amber' : ($req->status === 'Ready for Pick-up' ? 'badge-clean-blue' : 'badge-clean-green') }}">
                                            {{ $req->status }}
                                        </span>
                                    </div>
                                    <div class="text-slate-600 text-[11px] space-y-0.5">
                                        <p><strong>Clearance:</strong> {{ $req->service_type }}</p>
                                        <p class="text-slate-500 truncate"><strong>Purpose:</strong> {{ $req->purpose ?? 'General requirement' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 pt-1 border-t border-slate-200/80">
                                        <button onclick="updateRequestStatus({{ $req->id }}, 'Ready for Pick-up')" class="flex-1 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded text-center text-xs font-semibold cursor-pointer">
                                            Ready
                                        </button>
                                        <button onclick="updateRequestStatus({{ $req->id }}, 'Issued')" class="flex-1 py-1.5 bg-blue-900 hover:bg-blue-950 text-white rounded text-center text-xs font-semibold cursor-pointer">
                                            Issue
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-slate-400 py-3 text-xs">No clearance requests logged.</p>
                            @endforelse
                        </div>

                        <!-- Desktop Table View (>= sm) -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px]">
                                    <tr>
                                        <th class="p-2.5 rounded-l-lg">Requester</th>
                                        <th class="p-2.5">Clearance Type</th>
                                        <th class="p-2.5">Purpose</th>
                                        <th class="p-2.5">Status</th>
                                        <th class="p-2.5 rounded-r-lg text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($adminData['service_requests']->take(5) as $req)
                                        <tr id="req-row-{{ $req->id }}">
                                            <td class="p-2.5 font-bold text-slate-900">{{ $req->full_name ?? ($req->user->name ?? 'Resident') }}</td>
                                            <td class="p-2.5 font-medium text-slate-700">{{ $req->service_type }}</td>
                                            <td class="p-2.5 text-slate-500 truncate max-w-xs">{{ $req->purpose ?? 'General requirement' }}</td>
                                            <td class="p-2.5">
                                                <span class="status-badge px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $req->status === 'Pending' ? 'badge-clean-amber' : ($req->status === 'Ready for Pick-up' ? 'badge-clean-blue' : 'badge-clean-green') }}">
                                                    {{ $req->status }}
                                                </span>
                                            </td>
                                            <td class="p-2.5 text-right space-x-1">
                                                <button onclick="updateRequestStatus({{ $req->id }}, 'Ready for Pick-up')" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-semibold transition cursor-pointer">
                                                    Ready
                                                </button>
                                                <button onclick="updateRequestStatus({{ $req->id }}, 'Issued')" class="px-2 py-1 bg-blue-900 hover:bg-blue-950 text-white rounded text-[11px] font-semibold transition cursor-pointer">
                                                    Issue
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-4 text-center text-slate-400">No clearance requests logged.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- STAFF SCREEN 2: DOCUMENT CLEARANCE REQUESTS    -->
                <!-- ============================================== -->
                <div id="screen-admin-documents" class="screen-view space-y-3 sm:space-y-4 hidden">
                    <div class="civic-card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <button onclick="switchScreen('admin-overview')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                                <i class="fas fa-arrow-left text-xs"></i>
                            </button>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">Document Clearance Requests</h2>
                                <p class="text-xs text-slate-500">Official review of barangay clearances, residency, and indigency certificates</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2.5 sm:space-y-3">
                        @forelse($adminData['service_requests'] as $req)
                            <div class="civic-card p-4 sm:p-5" id="full-req-card-{{ $req->id }}">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                    <div>
                                        <span class="text-xs font-bold text-blue-900">{{ $req->service_type }}</span>
                                        <h3 class="font-bold text-slate-900 text-sm mt-0.5">{{ $req->full_name ?? ($req->user->name ?? 'Resident') }}</h3>
                                    </div>
                                    <span id="badge-req-{{ $req->id }}" class="status-badge px-2.5 py-0.5 rounded-md text-xs font-semibold self-start {{ $req->status === 'Pending' ? 'badge-clean-amber' : ($req->status === 'Ready for Pick-up' ? 'badge-clean-blue' : 'badge-clean-green') }}">
                                        {{ $req->status }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-600 space-y-1 mb-3">
                                    <p><strong>Purpose:</strong> {{ $req->purpose ?? 'General clearance requirement' }}</p>
                                    <p><strong>Contact:</strong> {{ $req->contact_number ?? ($req->user->phone ?? 'N/A') }}</p>
                                    <p class="text-slate-400 font-mono text-[11px]">Submitted {{ $req->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100">
                                    <button onclick="updateRequestStatus({{ $req->id }}, 'Ready for Pick-up')" class="bg-slate-100 hover:bg-slate-200 text-slate-800 py-2 sm:py-1.5 rounded-lg text-xs font-semibold transition text-center cursor-pointer">
                                        Ready
                                    </button>
                                    <button onclick="updateRequestStatus({{ $req->id }}, 'Issued')" class="bg-blue-900 hover:bg-blue-950 text-white py-2 sm:py-1.5 rounded-lg text-xs font-semibold transition text-center cursor-pointer">
                                        Issue
                                    </button>
                                    <button onclick="updateRequestStatus({{ $req->id }}, 'Rejected')" class="bg-white hover:bg-slate-50 text-rose-700 border border-slate-200 py-2 sm:py-1.5 rounded-lg text-xs font-semibold transition text-center cursor-pointer">
                                        Reject
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="civic-card p-8 text-center text-slate-400 text-xs">
                                No document requests on record.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- STAFF SCREEN 3: RELIEF OPERATIONS DESK         -->
                <!-- ============================================== -->
                <div id="screen-admin-relief" class="screen-view space-y-3 sm:space-y-4 hidden">
                    <div class="civic-card p-4 sm:p-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <button onclick="switchScreen('admin-overview')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                                <i class="fas fa-arrow-left text-xs"></i>
                            </button>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">Disaster Relief Operations</h2>
                                <p class="text-xs text-slate-500">Track and dispatch emergency household relief assistance</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2.5 sm:space-y-3">
                        @forelse($adminData['relief_requests'] as $relief)
                            <div class="civic-card p-4 sm:p-5" id="relief-card-{{ $relief->id }}">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                    <div>
                                        <span class="text-xs font-bold text-slate-700">{{ $relief->assistance_type }}</span>
                                        <h3 class="font-bold text-slate-900 text-sm mt-0.5">{{ $relief->user->name ?? 'Resident Household' }}</h3>
                                    </div>
                                    <span id="relief-badge-{{ $relief->id }}" class="status-badge px-2.5 py-0.5 rounded-md text-xs font-semibold self-start {{ $relief->status === 'Pending' ? 'badge-clean-amber' : ($relief->status === 'Dispatched' ? 'badge-clean-blue' : 'badge-clean-green') }}">
                                        {{ $relief->status }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-600 space-y-1 mb-3">
                                    <p><strong>Resident Address:</strong> {{ $relief->user->address ?? 'San Agustin' }}</p>
                                    <p><strong>Details / Special Needs:</strong> {{ $relief->details ?? 'Standard family food & medical pack' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100">
                                    <button onclick="updateReliefStatus({{ $relief->id }}, 'Dispatched')" class="bg-slate-100 hover:bg-slate-200 text-slate-800 py-2 sm:py-1.5 rounded-lg text-xs font-semibold transition text-center cursor-pointer">
                                        <i class="fas fa-truck mr-1"></i> Dispatch Pack
                                    </button>
                                    <button onclick="updateReliefStatus({{ $relief->id }}, 'Delivered')" class="bg-blue-900 hover:bg-blue-950 text-white py-2 sm:py-1.5 rounded-lg text-xs font-semibold transition text-center cursor-pointer">
                                        <i class="fas fa-check mr-1"></i> Mark Delivered
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="civic-card p-8 text-center text-slate-400 text-xs">
                                No relief requests recorded.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- STAFF SCREEN 4: JOBS & RECRUITER DESK (PESO)   -->
                <!-- ============================================== -->
                <div id="screen-admin-jobs" class="screen-view space-y-3 sm:space-y-4 hidden">
                    
                    <!-- Header with Actions -->
                    <div class="civic-card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <button onclick="switchScreen('admin-overview')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                                <i class="fas fa-arrow-left text-xs"></i>
                            </button>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">PESO Employment Desk</h2>
                                <p class="text-xs text-slate-500">Publish job openings, accredit recruiters, and review resident applicants</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="openModal('postJobModal')" class="flex-1 sm:flex-none justify-center bg-blue-900 hover:bg-blue-950 text-white text-xs font-semibold px-3 py-2 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                                <i class="fas fa-plus"></i> Post Job
                            </button>
                            <button onclick="openModal('registerRecruiterModal')" class="flex-1 sm:flex-none justify-center bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold px-3 py-2 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                                <i class="fas fa-building text-slate-500"></i> Add Recruiter
                            </button>
                        </div>
                    </div>

                    <!-- Sub-tabs Switcher -->
                    <div class="flex gap-4 border-b border-slate-200 text-xs overflow-x-auto no-scrollbar">
                        <button onclick="switchAdminJobTab('openings')" id="admin-job-tab-btn-openings" class="sub-tab-btn active pb-2 px-1 whitespace-nowrap cursor-pointer">
                            Job Openings ({{ $adminData['job_openings']->count() }})
                        </button>
                        <button onclick="switchAdminJobTab('applicants')" id="admin-job-tab-btn-applicants" class="sub-tab-btn pb-2 px-1 whitespace-nowrap cursor-pointer">
                            Applicants ({{ $adminData['job_applications']->count() }})
                        </button>
                        <button onclick="switchAdminJobTab('recruiters')" id="admin-job-tab-btn-recruiters" class="sub-tab-btn pb-2 px-1 whitespace-nowrap cursor-pointer">
                            Accredited Recruiters ({{ $adminData['job_recruiters']->count() }})
                        </button>
                    </div>

                    <!-- TAB 1: JOB OPENINGS -->
                    <div id="admin-job-tab-openings" class="space-y-2.5 sm:space-y-3">
                        @forelse($adminData['job_openings'] as $opening)
                            <div class="civic-card p-4 sm:p-5" id="job-opening-card-{{ $opening->id }}">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-slate-900 text-sm">{{ $opening->title }}</h3>
                                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded {{ $opening->status === 'Open' ? 'badge-clean-green' : 'badge-clean-slate' }}">
                                                {{ $opening->status }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-600 mt-0.5 flex flex-wrap items-center gap-1.5">
                                            <span>{{ $opening->company_name }}</span>
                                            @if($opening->recruiter)
                                                <span class="badge-clean-blue text-[10px] px-1.5 py-0.2 rounded font-medium">Accredited Recruiter</span>
                                            @endif
                                            <span>·</span>
                                            <span class="font-mono">{{ $opening->salary_range ?? 'Standard Wage' }}</span>
                                        </p>
                                    </div>
                                    <span class="text-xs text-slate-500 font-medium">
                                        {{ $opening->vacancies }} {{ Str::plural('Vacancy', $opening->vacancies) }} · {{ $opening->employment_type }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-2 mb-3">{{ $opening->description }}</p>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                                    <span class="text-slate-400 text-[11px]">
                                        Applicants: <strong class="text-slate-700">{{ $opening->applications->count() }}</strong>
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button onclick="updateJobOpeningStatus({{ $opening->id }}, '{{ $opening->status === 'Open' ? 'Closed' : 'Open' }}')" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xs font-medium transition cursor-pointer">
                                            {{ $opening->status === 'Open' ? 'Close' : 'Re-open' }}
                                        </button>
                                        <button onclick="deleteJobOpening({{ $opening->id }})" class="px-2.5 py-1.5 text-rose-700 hover:bg-rose-50 rounded text-xs font-medium transition cursor-pointer">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="civic-card p-8 text-center text-slate-400 text-xs">
                                No job openings posted yet. Click <strong>Post Job</strong> to publish a new position.
                            </div>
                        @endforelse
                    </div>

                    <!-- TAB 2: JOB APPLICANTS -->
                    <div id="admin-job-tab-applicants" class="space-y-2.5 sm:space-y-3 hidden">
                        @forelse($adminData['job_applications'] as $jobApp)
                            <div class="civic-card p-4 sm:p-5" id="job-app-card-{{ $jobApp->id }}">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                    <div>
                                        <span class="text-xs font-bold text-blue-900">{{ $jobApp->job_title }}</span>
                                        <h3 class="font-bold text-slate-900 text-sm mt-0.5">{{ $jobApp->user->name ?? 'Applicant' }}</h3>
                                    </div>
                                    <span id="job-badge-{{ $jobApp->id }}" class="status-badge px-2.5 py-0.5 rounded-md text-xs font-semibold self-start badge-clean-slate">
                                        {{ $jobApp->status }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-600 space-y-1 mb-3">
                                    <p><strong>Phone:</strong> {{ $jobApp->user->phone ?? 'N/A' }} · <strong>Address:</strong> {{ $jobApp->user->address ?? 'San Agustin' }}</p>
                                    @if($jobApp->notes)
                                        <p class="bg-slate-50 p-2 rounded border border-slate-100 text-[11px] text-slate-700">{{ $jobApp->notes }}</p>
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100">
                                    <button onclick="updateJobStatus({{ $jobApp->id }}, 'Interview Scheduled')" class="bg-slate-100 hover:bg-slate-200 text-slate-800 py-1.5 rounded text-xs font-semibold transition text-center cursor-pointer">
                                        <i class="fas fa-calendar mr-1"></i> Interview
                                    </button>
                                    <button onclick="updateJobStatus({{ $jobApp->id }}, 'Approved')" class="bg-blue-900 hover:bg-blue-950 text-white py-1.5 rounded text-xs font-semibold transition text-center cursor-pointer">
                                        <i class="fas fa-check mr-1"></i> Hire / Approve
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="civic-card p-8 text-center text-slate-400 text-xs">
                                No job applications submitted yet.
                            </div>
                        @endforelse
                    </div>

                    <!-- TAB 3: REGISTERED RECRUITERS -->
                    <div id="admin-job-tab-recruiters" class="space-y-2.5 sm:space-y-3 hidden">
                        @forelse($adminData['job_recruiters'] as $recruiter)
                            <div class="civic-card p-4 sm:p-5" id="recruiter-card-{{ $recruiter->id }}">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-slate-900 text-sm">{{ $recruiter->company_name }}</h3>
                                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded {{ $recruiter->status === 'Approved' ? 'badge-clean-green' : ($recruiter->status === 'Pending' ? 'badge-clean-amber' : 'badge-clean-red') }}">
                                                {{ $recruiter->status === 'Approved' ? 'Accredited Partner' : $recruiter->status }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $recruiter->industry }} · Contact: {{ $recruiter->contact_person }}</p>
                                    </div>
                                    <div class="text-xs text-slate-500 text-left sm:text-right">
                                        <p>{{ $recruiter->email }}</p>
                                        <p class="font-mono text-[11px]">{{ $recruiter->phone }}</p>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-100 mb-3 space-y-1">
                                    <p><strong>Permit / Registration:</strong> {{ $recruiter->permit_number ?? 'In review' }}</p>
                                    <p><strong>Office Address:</strong> {{ $recruiter->address ?? 'San Jose del Monte, Bulacan' }}</p>
                                    @if($recruiter->description)
                                        <p class="text-[11px] text-slate-500">{{ $recruiter->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                                    <span class="text-[11px] text-slate-400">
                                        Jobs: <strong class="text-slate-700">{{ $recruiter->job_openings_count ?? 0 }}</strong>
                                    </span>
                                    <div class="flex items-center gap-2">
                                        @if($recruiter->status !== 'Approved')
                                            <button onclick="updateRecruiterStatus({{ $recruiter->id }}, 'Approved')" class="bg-blue-900 hover:bg-blue-950 text-white px-3 py-1 rounded text-xs font-semibold transition cursor-pointer">
                                                Approve
                                            </button>
                                        @endif
                                        @if($recruiter->status !== 'Rejected')
                                            <button onclick="updateRecruiterStatus({{ $recruiter->id }}, 'Rejected')" class="bg-white hover:bg-slate-50 text-rose-700 border border-slate-200 px-3 py-1 rounded text-xs font-semibold transition cursor-pointer">
                                                Reject
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="civic-card p-8 text-center text-slate-400 text-xs">
                                No registered job recruiters yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- STAFF SCREEN 5: SCHOLARSHIP APPLICATIONS       -->
                <!-- ============================================== -->
                <div id="screen-admin-scholar" class="screen-view space-y-3 sm:space-y-4 hidden">
                    <div class="civic-card p-4 sm:p-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <button onclick="switchScreen('admin-overview')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                                <i class="fas fa-arrow-left text-xs"></i>
                            </button>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">Scholarship Grants Review</h2>
                                <p class="text-xs text-slate-500">Review student applications and approve educational financial assistance</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2.5 sm:space-y-3">
                        @forelse($adminData['scholarship_applications'] as $sch)
                            <div class="civic-card p-4 sm:p-5" id="sch-card-{{ $sch->id }}">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                    <div>
                                        <h3 class="font-bold text-slate-900 text-sm">{{ $sch->user->name ?? 'Student Applicant' }}</h3>
                                        <p class="text-xs text-slate-500 mt-0.5">Barangay ID: {{ $sch->user->barangay_id ?? 'N/A' }}</p>
                                    </div>
                                    <span id="sch-badge-{{ $sch->id }}" class="status-badge px-2.5 py-0.5 rounded-md text-xs font-semibold self-start {{ $sch->status === 'Granted' ? 'badge-clean-green' : ($sch->status === 'Declined' ? 'badge-clean-red' : 'badge-clean-amber') }}">
                                        {{ $sch->status }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-lg border border-slate-100 mb-3">
                                    <p class="font-mono text-[11px]">{{ $sch->notes ?? 'Standard scholarship portal application' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100">
                                    <button onclick="updateScholarshipStatus({{ $sch->id }}, 'Granted')" class="bg-blue-900 hover:bg-blue-950 text-white py-2 sm:py-1.5 rounded-lg text-xs font-semibold transition text-center cursor-pointer">
                                        <i class="fas fa-award mr-1"></i> Grant
                                    </button>
                                    <button onclick="updateScholarshipStatus({{ $sch->id }}, 'Declined')" class="bg-white hover:bg-slate-50 text-rose-700 border border-slate-200 py-2 sm:py-1.5 rounded-lg text-xs font-semibold transition text-center cursor-pointer">
                                        Decline
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="civic-card p-8 text-center text-slate-400 text-xs">
                                No scholarship applications submitted.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- STAFF SCREEN 6: STAFF REGISTRY (USER MGMT)     -->
                <!-- ============================================== -->
                <div id="screen-admin-staff" class="screen-view space-y-3 sm:space-y-4 hidden">
                    <div class="civic-card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <button onclick="switchScreen('admin-overview')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                                <i class="fas fa-arrow-left text-xs"></i>
                            </button>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">Barangay Staff Registry</h2>
                                <p class="text-xs text-slate-500">Official management of barangay officials, staff accounts, and designations</p>
                            </div>
                        </div>
                        @if($user->role === 'admin')
                            <button onclick="openModal('addStaffModal')" class="bg-blue-900 hover:bg-blue-950 text-white text-xs font-semibold px-3.5 py-2.5 sm:py-2 rounded-lg transition cursor-pointer flex items-center justify-center gap-1.5 self-stretch sm:self-auto">
                                <i class="fas fa-user-plus"></i> Add Staff Member
                            </button>
                        @endif
                    </div>

                    <!-- Mobile Card List View (< sm) -->
                    <div class="sm:hidden space-y-2.5">
                        @forelse($adminData['staff_members'] as $staffMember)
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 text-xs space-y-2 shadow-2xs" id="staff-mobile-card-{{ $staffMember->id }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-bold text-slate-900 text-sm">{{ $staffMember->name }}</span>
                                        <span class="text-[10px] font-mono text-slate-500 block">{{ $staffMember->barangay_id ?? 'STAFF-000' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $staffMember->role === 'admin' ? 'badge-clean-amber' : 'badge-clean-blue' }}">
                                            {{ $staffMember->role }}
                                        </span>
                                        <span id="staff-mobile-status-{{ $staffMember->id }}" class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $staffMember->is_active ?? true ? 'badge-clean-green' : 'badge-clean-slate' }}">
                                            {{ $staffMember->is_active ?? true ? 'Active' : 'Off' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-slate-600 text-[11px] space-y-0.5">
                                    <p><strong>Designation:</strong> {{ $staffMember->designation ?? 'Barangay Staff' }}</p>
                                    <p class="text-slate-500">{{ $staffMember->email }}</p>
                                </div>
                                @if($user->role === 'admin')
                                    <div class="grid grid-cols-3 gap-1.5 pt-2 border-t border-slate-100">
                                        <button onclick="openEditRoleModal({{ $staffMember->id }}, '{{ addslashes($staffMember->name) }}', '{{ $staffMember->role }}', '{{ addslashes($staffMember->designation ?? '') }}')" class="py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded text-center text-xs font-semibold cursor-pointer">
                                            Role
                                        </button>
                                        @if($staffMember->id !== $user->id)
                                            <button onclick="toggleUserStatus({{ $staffMember->id }})" class="py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded text-center text-xs font-semibold cursor-pointer">
                                                {{ $staffMember->is_active ?? true ? 'Suspend' : 'Activate' }}
                                            </button>
                                        @else
                                            <span class="py-1.5 text-center text-[10px] text-slate-400">Self</span>
                                        @endif
                                        <button onclick="openResetPasswordModal({{ $staffMember->id }}, '{{ addslashes($staffMember->name) }}')" class="py-1.5 text-blue-800 hover:bg-blue-50 rounded text-center text-xs font-semibold cursor-pointer">
                                            Reset
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-center text-slate-400 py-3 text-xs">No staff members listed.</p>
                        @endforelse
                    </div>

                    <!-- Desktop Master Table (>= sm) -->
                    <div class="hidden sm:block civic-card p-5 overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[10px]">
                                <tr>
                                    <th class="p-3 rounded-l-lg">Employee / ID</th>
                                    <th class="p-3">Staff Name</th>
                                    <th class="p-3">Designation & Department</th>
                                    <th class="p-3">Role</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3 rounded-r-lg text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($adminData['staff_members'] as $staffMember)
                                    <tr id="staff-row-{{ $staffMember->id }}">
                                        <td class="p-3 font-mono font-bold text-slate-700">{{ $staffMember->barangay_id ?? 'STAFF-000' }}</td>
                                        <td class="p-3">
                                            <p class="font-bold text-slate-900">{{ $staffMember->name }}</p>
                                            <p class="text-[11px] text-slate-500">{{ $staffMember->email }}</p>
                                        </td>
                                        <td class="p-3 font-medium text-slate-700">
                                            {{ $staffMember->designation ?? 'Barangay Operations' }}
                                        </td>
                                        <td class="p-3">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $staffMember->role === 'admin' ? 'badge-clean-amber' : 'badge-clean-blue' }}">
                                                {{ $staffMember->role }}
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <span id="staff-status-{{ $staffMember->id }}" class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $staffMember->is_active ?? true ? 'badge-clean-green' : 'badge-clean-slate' }}">
                                                {{ $staffMember->is_active ?? true ? 'Active' : 'Deactivated' }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-right space-x-1">
                                            @if($user->role === 'admin')
                                                <button onclick="openEditRoleModal({{ $staffMember->id }}, '{{ addslashes($staffMember->name) }}', '{{ $staffMember->role }}', '{{ addslashes($staffMember->designation ?? '') }}')" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-semibold transition cursor-pointer">
                                                    Edit Role
                                                </button>
                                                @if($staffMember->id !== $user->id)
                                                    <button onclick="toggleUserStatus({{ $staffMember->id }})" class="px-2 py-1 bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 rounded text-[11px] font-semibold transition cursor-pointer">
                                                        {{ $staffMember->is_active ?? true ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                @endif
                                                <button onclick="openResetPasswordModal({{ $staffMember->id }}, '{{ addslashes($staffMember->name) }}')" class="px-2 py-1 text-blue-700 hover:bg-blue-50 rounded text-[11px] font-semibold transition cursor-pointer">
                                                    Reset
                                                </button>
                                            @else
                                                <span class="text-slate-400 text-[11px]">View only</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-slate-400">No staff members listed.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- STAFF SCREEN 7: RESIDENT REGISTRY DIRECTORY    -->
                <!-- ============================================== -->
                <div id="screen-admin-residents" class="screen-view space-y-3 sm:space-y-4 hidden">
                    <div class="civic-card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <button onclick="switchScreen('admin-overview')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                                <i class="fas fa-arrow-left text-xs"></i>
                            </button>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">Barangay Resident Registry</h2>
                                <p class="text-xs text-slate-500">Official masterlist of registered citizens in San Agustin</p>
                            </div>
                        </div>
                        <input type="text" id="residentSearchInput" onkeyup="filterResidentRegistry()" placeholder="Search resident or ID..." 
                               class="border border-slate-200 rounded-lg px-3 py-2 text-xs bg-slate-50 focus:bg-white focus:outline-none w-full sm:w-64">
                    </div>

                    <!-- Mobile Card List View (< sm) -->
                    <div class="sm:hidden space-y-2.5">
                        @foreach($adminData['residents'] as $res)
                            <div class="p-3.5 bg-white rounded-xl border border-slate-200 text-xs space-y-2 resident-row shadow-2xs" id="resident-mobile-{{ $res->id }}">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900 text-sm">{{ $res->name }}</span>
                                    <span class="font-mono text-[10px] font-bold text-blue-800 bg-blue-50 px-2 py-0.5 rounded">{{ $res->barangay_id ?? 'BRGY-0000' }}</span>
                                </div>
                                <div class="text-slate-600 text-[11px] space-y-0.5">
                                    <p>{{ $res->email }} · {{ $res->phone ?? 'No phone' }}</p>
                                    <p class="text-slate-500">{{ $res->address ?? 'San Agustin' }}</p>
                                </div>
                                @if($user->role === 'admin')
                                    <div class="pt-2 border-t border-slate-100">
                                        <button onclick="openEditRoleModal({{ $res->id }}, '{{ addslashes($res->name) }}', 'resident', '')" class="w-full py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded text-center text-xs font-semibold cursor-pointer">
                                            Promote to Staff
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View (>= sm) -->
                    <div class="hidden sm:block civic-card p-5 overflow-x-auto">
                        <table class="w-full text-xs text-left" id="residentsTable">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[10px]">
                                <tr>
                                    <th class="p-3 rounded-l-lg">Barangay ID</th>
                                    <th class="p-3">Full Name</th>
                                    <th class="p-3">Email & Phone</th>
                                    <th class="p-3">Address</th>
                                    <th class="p-3 rounded-r-lg text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($adminData['residents'] as $res)
                                    <tr class="resident-row" id="resident-row-{{ $res->id }}">
                                        <td class="p-3 font-mono font-bold text-slate-700">{{ $res->barangay_id ?? 'BRGY-0000' }}</td>
                                        <td class="p-3 font-bold text-slate-900">{{ $res->name }}</td>
                                        <td class="p-3 text-slate-600">
                                            <p>{{ $res->email }}</p>
                                            <p class="text-slate-400 text-[11px]">{{ $res->phone ?? 'No phone' }}</p>
                                        </td>
                                        <td class="p-3 text-slate-600">{{ $res->address ?? 'San Agustin' }}</td>
                                        <td class="p-3 text-right">
                                            @if($user->role === 'admin')
                                                <button onclick="openEditRoleModal({{ $res->id }}, '{{ addslashes($res->name) }}', 'resident', '')" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-semibold transition cursor-pointer">
                                                    Promote to Staff
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- STAFF SCREEN 8: OFFICIAL BROADCAST POSTER      -->
                <!-- ============================================== -->
                <div id="screen-admin-broadcast" class="screen-view space-y-3 sm:space-y-4 hidden">
                    <div class="civic-card p-4 sm:p-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <button onclick="switchScreen('admin-overview')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                                <i class="fas fa-arrow-left text-xs"></i>
                            </button>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">Official Barangay Broadcast</h2>
                                <p class="text-xs text-slate-500">Publish verified announcements to every resident's community feed</p>
                            </div>
                        </div>
                    </div>

                    <div class="civic-card p-4 sm:p-6">
                        <form onsubmit="handleBroadcastSubmit(event)" class="space-y-3 sm:space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Advisory Message Content</label>
                                <textarea id="broadcastContent" rows="5" required placeholder="Type the official public advisory (e.g. Weather updates, health mission, relief distribution dates)..." 
                                          class="w-full border border-slate-200 rounded-xl p-3 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50 focus:bg-white"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Official Photo or Video Attachment (Optional)</label>
                                <div class="flex flex-wrap items-center gap-2">
                                    <label for="broadcastMediaInput" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold cursor-pointer shadow-2xs transition">
                                        <i class="fas fa-photo-film text-blue-900"></i>
                                        <span>Choose Media</span>
                                    </label>
                                    <input type="file" id="broadcastMediaInput" accept="image/*,video/mp4,video/webm,video/quicktime" class="hidden" onchange="handleMediaSelect(event, 'broadcast')">
                                    <span id="broadcastMediaStatus" class="text-[11px] text-slate-500 truncate">Max 15MB video · Photos auto-compressed</span>
                                </div>
                                <div id="broadcastMediaPreviewBox" class="hidden mt-2.5 rounded-xl border border-slate-200 p-2.5 bg-slate-50">
                                    <div id="broadcastMediaPreviewContent" class="overflow-hidden rounded-lg max-h-56 flex items-center justify-center bg-black/5"></div>
                                    <div class="mt-2 flex items-center justify-between text-[11px] px-1">
                                        <span id="broadcastMediaInfo" class="text-slate-600 font-medium"></span>
                                        <button type="button" onclick="clearMedia('broadcast')" class="text-rose-600 hover:text-rose-800 font-semibold cursor-pointer">
                                            <i class="fas fa-trash-alt mr-1"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-2 pt-2">
                                <button type="button" onclick="switchScreen('admin-overview')" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg text-xs font-semibold transition cursor-pointer flex items-center gap-2">
                                    <i class="fas fa-bullhorn text-xs"></i> Publish to Feed
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- ============================================== -->
            <!-- RESIDENT VIEW: COMMUNITY FEED SCREEN           -->
            <!-- ============================================== -->
            <div id="screen-home" class="screen-view space-y-3 sm:space-y-4 {{ $isStaff ? 'hidden' : '' }}">
                
                <!-- Welcome Banner (Calm & Dignified) -->
                <div class="civic-card p-4 sm:p-6 bg-slate-900 text-white">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-white/10 text-white">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> 
                            {{ $isStaff ? 'Staff Officer' : 'Verified Resident' }}
                        </span>
                        <span class="text-xs font-mono text-slate-400">{{ date('M j, Y') }}</span>
                    </div>
                    <h2 class="text-base sm:text-xl font-bold text-white mt-2">Welcome to Barangay San Agustin</h2>
                    <p class="text-xs text-slate-300 mt-0.5">Stay connected with official advisories, public services, and neighborhood announcements.</p>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <button type="button" onclick="openModal('requestDocModal')" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow-xs">
                            <i class="fas fa-file-invoice text-xs"></i> Request Clearance
                        </button>
                        <button type="button" onclick="switchScreen('jobs')" class="bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fas fa-briefcase text-xs"></i> View Jobs
                        </button>
                    </div>
                </div>

                <!-- Create Post Input Trigger (Mobile touch-friendly) -->
                <div onclick="switchScreen('post')" class="civic-card p-3 sm:p-3.5 flex items-center gap-3 hover:border-slate-300 transition text-left cursor-pointer active:bg-slate-50">
                    <div class="w-9 h-9 rounded-lg bg-blue-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                        {{ $user->initials }}
                    </div>
                    <div class="flex-1 bg-slate-50 hover:bg-slate-100 rounded-lg px-3 py-2 text-xs text-slate-400 border border-slate-200 truncate">
                        Share a community update or inquiry...
                    </div>
                    <button class="bg-blue-900 hover:bg-blue-950 text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                        Post
                    </button>
                </div>

                <!-- Feed Tab Filter Pills -->
                <div class="flex items-center gap-2 border-b border-slate-200 pb-2 text-xs font-medium overflow-x-auto no-scrollbar">
                    <button onclick="setFeedTab('recent')" id="feed-tab-recent" class="px-3 py-1 rounded-md bg-blue-900 text-white cursor-pointer whitespace-nowrap">
                        All Updates
                    </button>
                    <button onclick="setFeedTab('popular')" id="feed-tab-popular" class="px-3 py-1 rounded-md bg-slate-100 text-slate-600 hover:bg-slate-200 cursor-pointer whitespace-nowrap">
                        Popular
                    </button>
                    <button onclick="setFeedTab('questions')" id="feed-tab-questions" class="px-3 py-1 rounded-md bg-slate-100 text-slate-600 hover:bg-slate-200 cursor-pointer whitespace-nowrap">
                        Inquiries
                    </button>
                </div>

                <!-- Community Feed Posts List -->
                <div id="postsFeedContainer" class="space-y-3">
                    <!-- Posts rendered by client script -->
                </div>
            </div>

            <!-- ============================================== -->
            <!-- RESIDENT VIEW: HEALTH CENTER RECORDS           -->
            <!-- ============================================== -->
            <div id="screen-health" class="screen-view space-y-3 sm:space-y-4 hidden">
                <div class="civic-card p-4 sm:p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button onclick="switchScreen('home')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                            <i class="fas fa-arrow-left text-xs"></i>
                        </button>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Health Center Vitals Log</h2>
                            <p class="text-xs text-slate-500">Record your blood pressure and follow-up notes for the clinic</p>
                        </div>
                    </div>
                </div>

                <div class="civic-card p-4 sm:p-6">
                    <form onsubmit="handleHealthSubmit(event)" class="space-y-3.5 sm:space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Blood Pressure (mmHg)</label>
                                <input type="text" id="healthBP" required placeholder="e.g. 120/80" 
                                       class="w-full border border-slate-200 rounded-lg p-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pulse Rate (optional)</label>
                                <input type="text" id="healthPulse" placeholder="e.g. 72" 
                                       class="w-full border border-slate-200 rounded-lg p-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Notes / Maintenance Medicines</label>
                            <textarea id="healthNotes" rows="3" placeholder="Specify if feeling dizzy or notes for the barangay clinic doctor..." 
                                      class="w-full border border-slate-200 rounded-lg p-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50"></textarea>
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                            Save Vitals Record
                        </button>
                    </form>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- RESIDENT VIEW: JOB OPENINGS & LIVELIHOOD       -->
            <!-- ============================================== -->
            <div id="screen-jobs" class="screen-view space-y-3 sm:space-y-4 hidden">
                
                <!-- Screen Header with Recruiter Registration Action -->
                <div class="civic-card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <button onclick="switchScreen('home')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                            <i class="fas fa-arrow-left text-xs"></i>
                        </button>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Job Openings & PESO Livelihood</h2>
                            <p class="text-xs text-slate-500">Employment opportunities from accredited local recruiters and barangay partners</p>
                        </div>
                    </div>
                    <button onclick="openModal('registerRecruiterModal')" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold px-3 py-2 rounded-lg transition cursor-pointer flex items-center justify-center gap-1.5 self-stretch sm:self-auto">
                        <i class="fas fa-building text-slate-500"></i> Recruiter Partnership
                    </button>
                </div>

                <!-- Recruiter Partnership Information Banner -->
                <div class="civic-card p-3.5 sm:p-4 bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-900 text-white flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-handshake text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900">Are you a business owner or recruiter?</p>
                            <p class="text-[11px] text-slate-500">Accredit with Barangay San Agustin to publish job openings for verified residents.</p>
                        </div>
                    </div>
                    <button onclick="openModal('registerRecruiterModal')" class="bg-blue-900 hover:bg-blue-950 text-white text-xs font-semibold px-3 py-2 rounded-lg transition cursor-pointer whitespace-nowrap self-stretch sm:self-auto text-center">
                        Submit Accreditation Form
                    </button>
                </div>

                <!-- Dynamic Jobs List (Mobile-optimized cards) -->
                <div class="space-y-3">
                    @forelse($openJobs as $job)
                        <div class="civic-card p-4 sm:p-5 hover:border-slate-300 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 mb-2">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">{{ $job->title }}</h3>
                                    <p class="text-xs text-slate-600 mt-0.5 flex flex-wrap items-center gap-1.5">
                                        <span class="font-medium text-slate-800">{{ $job->company_name }}</span>
                                        @if($job->recruiter)
                                            <span class="badge-clean-blue text-[10px] font-semibold px-2 py-0.5 rounded-md inline-flex items-center gap-1">
                                                <i class="fas fa-check-circle"></i> Accredited Recruiter
                                            </span>
                                        @else
                                            <span class="badge-clean-slate text-[10px] font-semibold px-2 py-0.5 rounded-md">
                                                Barangay Desk
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <span class="badge-clean-green text-xs font-semibold px-2.5 py-1 rounded-md self-start">
                                    {{ $job->salary_range ?? 'Competitive Pay' }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed mb-3">
                                {{ $job->description }}
                            </p>

                            @if($job->requirements)
                                <div class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-100 mb-3">
                                    <strong>Requirements:</strong> {{ $job->requirements }}
                                </div>
                            @endif

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs">
                                <div class="flex flex-wrap items-center gap-2 text-slate-500 text-[11px]">
                                    <span><i class="fas fa-map-marker-alt text-slate-400 mr-1"></i> {{ $job->location }}</span>
                                    <span>•</span>
                                    <span><i class="fas fa-clock text-slate-400 mr-1"></i> {{ $job->employment_type }}</span>
                                    <span>•</span>
                                    <span><i class="fas fa-users text-slate-400 mr-1"></i> {{ $job->vacancies }} Slots</span>
                                </div>
                                <button onclick="openApplyJobModal({{ $job->id }}, '{{ addslashes($job->title) }}')" class="w-full sm:w-auto bg-blue-900 hover:bg-blue-950 text-white text-xs font-semibold px-4 py-2 sm:py-1.5 rounded-lg transition cursor-pointer text-center">
                                    Apply Now
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="civic-card p-8 text-center text-slate-400 text-xs">
                            <p>No active job openings at the moment. Please check back shortly or visit the Barangay PESO desk.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ============================================== -->
            <!-- RESIDENT VIEW: SCHOLARSHIP PROGRAM             -->
            <!-- ============================================== -->
            <div id="screen-scholar" class="screen-view space-y-3 sm:space-y-4 hidden">
                <div class="civic-card p-4 sm:p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button onclick="switchScreen('home')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                            <i class="fas fa-arrow-left text-xs"></i>
                        </button>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Resident Scholarship Program</h2>
                            <p class="text-xs text-slate-500">Educational financial grants for youth and college students</p>
                        </div>
                    </div>
                </div>

                <div class="civic-card p-4 sm:p-6">
                    <form onsubmit="handleScholarSubmit(event)" class="space-y-3.5 sm:space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School / University</label>
                                <input type="text" id="scholarSchool" required placeholder="e.g. City College of San Jose del Monte" 
                                       class="w-full border border-slate-200 rounded-lg p-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Course & Year Level</label>
                                <input type="text" id="scholarCourse" required placeholder="e.g. BS Information Technology - 2nd Year" 
                                       class="w-full border border-slate-200 rounded-lg p-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Latest GWA Average</label>
                            <input type="text" id="scholarGWA" required placeholder="e.g. 1.75" 
                                   class="w-full border border-slate-200 rounded-lg p-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50">
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                            Submit Scholarship Application
                        </button>
                    </form>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- RESIDENT VIEW: RELIEF ASSISTANCE REQUEST       -->
            <!-- ============================================== -->
            <div id="screen-relief" class="screen-view space-y-3 sm:space-y-4 hidden">
                <div class="civic-card p-4 sm:p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button onclick="switchScreen('home')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                            <i class="fas fa-arrow-left text-xs"></i>
                        </button>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Disaster Relief Assistance</h2>
                            <p class="text-xs text-slate-500">Request emergency food packs and medical aid for your household</p>
                        </div>
                    </div>
                </div>

                <div class="civic-card p-4 sm:p-6">
                    <form onsubmit="handleReliefSubmit(event)" class="space-y-3.5 sm:space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assistance Type</label>
                            <select id="reliefType" required class="w-full border border-slate-200 rounded-lg p-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50">
                                <option value="Emergency Food Pack">Emergency Food Relief Pack</option>
                                <option value="Medical & Hygiene Pack">Medical & Hygiene Assistance Pack</option>
                                <option value="Senior Citizen & Infant Support">Senior Citizen & Infant Support Aid</option>
                                <option value="Flood / Disaster Evacuation Aid">Flood / Disaster Evacuation Assistance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Household Details & Special Needs</label>
                            <textarea id="reliefDetails" rows="3" placeholder="Indicate family member count, infants, or seniors needing maintenance medicine..." 
                                      class="w-full border border-slate-200 rounded-lg p-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50"></textarea>
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                            Submit Relief Request
                        </button>
                    </form>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- RESIDENT VIEW: CREATE POST SCREEN              -->
            <!-- ============================================== -->
            <div id="screen-post" class="screen-view space-y-3 sm:space-y-4 hidden">
                <div class="civic-card p-4 sm:p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button onclick="switchScreen('home')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition cursor-pointer">
                            <i class="fas fa-arrow-left text-xs"></i>
                        </button>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Create Community Post</h2>
                            <p class="text-xs text-slate-500">Post an announcement, inquiry, or update to your neighborhood</p>
                        </div>
                    </div>
                </div>

                <div class="civic-card p-4 sm:p-6">
                    <form onsubmit="handlePostSubmit(event)" class="space-y-3.5 sm:space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category</label>
                            <select id="postType" class="w-full border border-slate-200 rounded-lg p-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50">
                                <option value="general">General Community Discussion</option>
                                <option value="question">Question & Inquiry</option>
                                <option value="feedback">Neighborhood Feedback</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Content</label>
                            <textarea id="postContent" rows="4" required placeholder="What would you like to share with Barangay San Agustin neighbors?..." 
                                      class="w-full border border-slate-200 rounded-lg p-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-blue-900 bg-slate-50"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Attach Photo or Video (Optional)</label>
                            <div class="flex flex-wrap items-center gap-2">
                                <label for="postMediaInput" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold cursor-pointer shadow-2xs transition">
                                    <i class="fas fa-photo-film text-blue-900"></i>
                                    <span>Choose Media</span>
                                </label>
                                <input type="file" id="postMediaInput" accept="image/*,video/mp4,video/webm,video/quicktime" class="hidden" onchange="handleMediaSelect(event, 'post')">
                                <span id="postMediaStatus" class="text-[11px] text-slate-500 truncate">Max 15MB video · Photos auto-compressed</span>
                            </div>
                            <div id="postMediaPreviewBox" class="hidden mt-2.5 rounded-xl border border-slate-200 p-2.5 bg-slate-50">
                                <div id="postMediaPreviewContent" class="overflow-hidden rounded-lg max-h-56 flex items-center justify-center bg-black/5"></div>
                                <div class="mt-2 flex items-center justify-between text-[11px] px-1">
                                    <span id="postMediaInfo" class="text-slate-600 font-medium"></span>
                                    <button type="button" onclick="clearMedia('post')" class="text-rose-600 hover:text-rose-800 font-semibold cursor-pointer">
                                        <i class="fas fa-trash-alt mr-1"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" onclick="switchScreen('home')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                                Publish Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- ============================================== -->
<!-- BOTTOM NAVIGATION (MOBILE ONLY: 56PX COMPACT)  -->
<!-- ============================================== -->
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-40 md:hidden shadow-lg">
    <div class="max-w-md mx-auto grid grid-cols-5 h-14">
        @if($isStaff)
            <button id="nav-btn-admin-overview" onclick="switchScreen('admin-overview')" class="nav-item active flex flex-col items-center justify-center gap-0.5 border-t-2 border-blue-900 text-blue-900 cursor-pointer">
                <i class="nav-icon fas fa-chart-line text-sm"></i>
                <span class="nav-label text-[10px] font-bold">Console</span>
            </button>
            <button id="nav-btn-admin-documents" onclick="switchScreen('admin-documents')" class="nav-item flex flex-col items-center justify-center gap-0.5 border-t-2 border-transparent text-slate-500 cursor-pointer">
                <i class="nav-icon fas fa-file-text text-sm"></i>
                <span class="nav-label text-[10px]">Docs</span>
            </button>
            <button id="nav-btn-admin-jobs" onclick="switchScreen('admin-jobs')" class="nav-item flex flex-col items-center justify-center gap-0.5 border-t-2 border-transparent text-slate-500 cursor-pointer">
                <i class="nav-icon fas fa-briefcase text-sm"></i>
                <span class="nav-label text-[10px]">Jobs</span>
            </button>
            <button id="nav-btn-admin-staff" onclick="switchScreen('admin-staff')" class="nav-item flex flex-col items-center justify-center gap-0.5 border-t-2 border-transparent text-slate-500 cursor-pointer">
                <i class="nav-icon fas fa-id-badge text-sm"></i>
                <span class="nav-label text-[10px]">Staff</span>
            </button>
            <button onclick="openMobileDrawer()" class="flex flex-col items-center justify-center gap-0.5 text-slate-500 cursor-pointer">
                <i class="fas fa-bars text-sm"></i>
                <span class="text-[10px]">Menu</span>
            </button>
        @else
            <button id="nav-btn-home" onclick="switchScreen('home')" class="nav-item active flex flex-col items-center justify-center gap-0.5 border-t-2 border-blue-900 text-blue-900 cursor-pointer">
                <i class="nav-icon fas fa-newspaper text-sm"></i>
                <span class="nav-label text-[10px] font-bold">Feed</span>
            </button>
            <button id="nav-btn-jobs" onclick="switchScreen('jobs')" class="nav-item flex flex-col items-center justify-center gap-0.5 border-t-2 border-transparent text-slate-500 cursor-pointer">
                <i class="nav-icon fas fa-briefcase text-sm"></i>
                <span class="nav-label text-[10px]">Jobs</span>
            </button>
            <button id="nav-btn-post" onclick="switchScreen('post')" class="flex flex-col items-center justify-center -mt-2 cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-blue-900 text-white flex items-center justify-center shadow-md">
                    <i class="fas fa-plus text-sm"></i>
                </div>
                <span class="text-[9px] text-slate-500 font-medium mt-0.5">Post</span>
            </button>
            <button id="nav-btn-health" onclick="switchScreen('health')" class="nav-item flex flex-col items-center justify-center gap-0.5 border-t-2 border-transparent text-slate-500 cursor-pointer">
                <i class="nav-icon fas fa-heartbeat text-sm"></i>
                <span class="nav-label text-[10px]">Health</span>
            </button>
            <button onclick="openMobileDrawer()" class="flex flex-col items-center justify-center gap-0.5 text-slate-500 cursor-pointer">
                <i class="fas fa-th-large text-sm"></i>
                <span class="text-[10px]">Services</span>
            </button>
        @endif
    </div>
</nav>

<!-- ============================================== -->
<!-- CENTERED & MOBILE BOTTOM SHEET MODALS          -->
<!-- ============================================== -->

<!-- 0. Request Document / Barangay Clearance Modal (Resident) -->
<div id="requestDocModal" class="modal-bg" onclick="closeModalOutside(event, 'requestDocModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-file-invoice text-blue-900"></i> Request Barangay Document
            </h3>
            <button type="button" onclick="closeModal('requestDocModal')" class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <form onsubmit="handleRequestDocSubmit(event)" class="space-y-3 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Document Type *</label>
                <select id="requestDocType" required class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                    <option value="Barangay Clearance">Barangay Clearance</option>
                    <option value="Certificate of Indigency">Certificate of Indigency</option>
                    <option value="Certificate of Residency">Certificate of Residency</option>
                    <option value="Business Clearance">Barangay Business Clearance</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Full Legal Name *</label>
                <input type="text" id="requestDocName" required value="{{ $user->name }}" 
                       class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Contact Number</label>
                <input type="text" id="requestDocPhone" value="{{ $user->phone ?? '' }}" placeholder="0917-XXX-XXXX" 
                       class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Purpose / Notes *</label>
                <textarea id="requestDocPurpose" rows="3" required placeholder="e.g. Local employment, Postal ID, Bank account opening, Scholarship..." 
                          class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs"></textarea>
            </div>
            <div class="p-2.5 bg-blue-50 border border-blue-100 rounded-lg text-[11px] text-blue-800 flex items-start gap-2">
                <i class="fas fa-info-circle mt-0.5 text-blue-900"></i>
                <span>Requests are usually processed within 1 to 2 business days. Present your Barangay ID or valid government ID upon claiming.</span>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('requestDocModal')" class="px-3.5 py-2 text-slate-600 hover:bg-slate-100 rounded-lg font-semibold">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-2 rounded-lg font-semibold shadow-xs">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 1. Post Job Opening Modal (Staff) -->
<div id="postJobModal" class="modal-bg" onclick="closeModalOutside(event, 'postJobModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-briefcase text-blue-900"></i> Publish Job Opening
            </h3>
            <button type="button" onclick="closeModal('postJobModal')" class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <form onsubmit="handlePostJobOpening(event)" class="space-y-3 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Job Title *</label>
                <input type="text" id="postJobTitle" required placeholder="e.g. Cashier, Security Guard, Warehouse Associate" 
                       class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Recruiter / Employer Partner</label>
                    <select id="postJobRecruiterId" class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs" onchange="handleRecruiterSelectChange(this)">
                        <option value="">Barangay San Agustin (PESO / LGU)</option>
                        @if(isset($approvedRecruiters))
                            @foreach($approvedRecruiters as $rec)
                                <option value="{{ $rec->id }}">{{ $rec->company_name }} (Accredited)</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Company / Display Name</label>
                    <input type="text" id="postJobCompanyName" placeholder="e.g. Robinsons Supermarket" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Employment Type</label>
                    <select id="postJobType" class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contractual">Contractual</option>
                        <option value="Emergency">Emergency / Temporary</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Salary Range</label>
                    <input type="text" id="postJobSalary" placeholder="e.g. ₱610/day or ₱18,000/mo" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Vacancies</label>
                    <input type="number" id="postJobVacancies" value="1" min="1" max="500" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Job Description *</label>
                <textarea id="postJobDesc" rows="3" required placeholder="Describe the duties, role expectations, and worksite details..." 
                          class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs"></textarea>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Qualifications / Requirements</label>
                <input type="text" id="postJobReqs" placeholder="e.g. High School Graduate, Resident priority, 21-45 y/o" 
                       class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('postJobModal')" class="px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 text-xs">Cancel</button>
                <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg font-semibold text-xs">Publish Opening</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Recruiter Accreditation / Registration Modal -->
<div id="registerRecruiterModal" class="modal-bg" onclick="closeModalOutside(event, 'registerRecruiterModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-building text-blue-900"></i> Recruiter & Employer Accreditation Form
            </h3>
            <button type="button" onclick="closeModal('registerRecruiterModal')" class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <form onsubmit="handleRecruiterRegisterSubmit(event)" class="space-y-3 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Company / Business Name *</label>
                <input type="text" id="recruiterCompanyName" required placeholder="e.g. SM Supermarket SJDM / Apex Logistics" 
                       class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Contact Person (HR Officer) *</label>
                    <input type="text" id="recruiterContactPerson" required placeholder="e.g. Claire Mendoza (HR Head)" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Industry / Business Type *</label>
                    <input type="text" id="recruiterIndustry" required placeholder="e.g. Retail, Security, Logistics, BPO" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Official Email Address *</label>
                    <input type="email" id="recruiterEmail" required placeholder="e.g. careers@company.ph" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Contact Phone Number *</label>
                    <input type="text" id="recruiterPhone" required placeholder="e.g. 0917-123-4567" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Business Permit / DTI / SEC #</label>
                    <input type="text" id="recruiterPermit" placeholder="e.g. BP-2026-9901" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Office Address</label>
                    <input type="text" id="recruiterAddress" placeholder="e.g. Quirino Highway, San Jose del Monte" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Company Description & Hiring Needs</label>
                <textarea id="recruiterDescription" rows="2" placeholder="Briefly describe what roles your company is hiring for in San Agustin..." 
                          class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('registerRecruiterModal')" class="px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 text-xs">Cancel</button>
                <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg font-semibold text-xs">Submit Accreditation</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Add Staff Member Modal (Super Admin) -->
<div id="addStaffModal" class="modal-bg" onclick="closeModalOutside(event, 'addStaffModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-user-plus text-blue-900"></i> Register Staff Officer
            </h3>
            <button type="button" onclick="closeModal('addStaffModal')" class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <form onsubmit="handleStoreStaffSubmit(event)" class="space-y-3 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Full Legal Name *</label>
                <input type="text" id="staffName" required placeholder="e.g. Juan De La Cruz" 
                       class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Official Email *</label>
                    <input type="email" id="staffEmail" required placeholder="e.g. juan.staff@sjdm.gov.ph" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Employee / Staff ID *</label>
                    <input type="text" id="staffBarangayId" required placeholder="e.g. STAFF-0010" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">System Role *</label>
                    <select id="staffRole" class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                        <option value="staff">Staff Officer (Operations Desk)</option>
                        <option value="admin">Administrator (Executive / Captain)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Designation / Department *</label>
                    <input type="text" id="staffDesignation" required placeholder="e.g. PESO Officer, Health Desk, Secretary" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Phone Number</label>
                    <input type="text" id="staffPhone" placeholder="e.g. 0917-888-9999" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Temporary Password *</label>
                    <input type="password" id="staffPassword" required minlength="6" placeholder="Min. 6 characters" 
                           class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('addStaffModal')" class="px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 text-xs">Cancel</button>
                <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg font-semibold text-xs">Save Staff Account</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Edit Role / Promote User Modal -->
<div id="editUserRoleModal" class="modal-bg" onclick="closeModalOutside(event, 'editUserRoleModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-user-tag text-blue-900"></i> Update Role & Designation
            </h3>
            <button type="button" onclick="closeModal('editUserRoleModal')" class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <form onsubmit="handleUpdateRoleSubmit(event)" class="space-y-3 text-xs">
            <input type="hidden" id="editUserId">
            <div>
                <p class="text-xs text-slate-500">Updating permissions for: <strong id="editUserName" class="text-slate-900"></strong></p>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Assign Role *</label>
                <select id="editUserRoleSelect" class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
                    <option value="resident">Resident (Community Hub)</option>
                    <option value="staff">Staff Officer (Operations Desk)</option>
                    <option value="admin">Administrator (Executive / Captain)</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Official Designation</label>
                <input type="text" id="editUserDesignation" placeholder="e.g. PESO Officer, Desk Officer, Secretary" 
                       class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('editUserRoleModal')" class="px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 text-xs">Cancel</button>
                <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg font-semibold text-xs">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- 5. Reset Password Modal -->
<div id="resetPasswordModal" class="modal-bg" onclick="closeModalOutside(event, 'resetPasswordModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-key text-blue-900"></i> Reset Password
            </h3>
            <button type="button" onclick="closeModal('resetPasswordModal')" class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <form onsubmit="handleResetPasswordSubmit(event)" class="space-y-3 text-xs">
            <input type="hidden" id="resetPasswordUserId">
            <p class="text-xs text-slate-500">Resetting login password for: <strong id="resetPasswordUserName" class="text-slate-900"></strong></p>
            <div>
                <label class="block font-bold text-slate-700 mb-1">New Password *</label>
                <input type="password" id="newResetPassword" required minlength="6" placeholder="Enter new password (min. 6 characters)" 
                       class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs">
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('resetPasswordModal')" class="px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 text-xs">Cancel</button>
                <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg font-semibold text-xs">Update Password</button>
            </div>
        </form>
    </div>
</div>

<!-- 6. Resident Apply for Job Modal -->
<div id="applyJobModal" class="modal-bg" onclick="closeModalOutside(event, 'applyJobModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-briefcase text-blue-900"></i> Submit Job Application
            </h3>
            <button type="button" onclick="closeModal('applyJobModal')" class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <form onsubmit="handleConfirmJobApply(event)" class="space-y-3 text-xs">
            <input type="hidden" id="applyJobOpeningId">
            <input type="hidden" id="applyJobTitleHidden">
            <div>
                <p class="text-xs text-slate-600">Position: <strong id="applyJobTitleDisplay" class="text-slate-900 text-sm"></strong></p>
                <p class="text-[11px] text-slate-500">Applicant: <strong>{{ $user->name }}</strong> · Contact: {{ $user->phone ?? 'On Profile' }}</p>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Qualifications / Brief Message to Employer</label>
                <textarea id="applyJobNotes" rows="3" placeholder="Briefly mention your previous work experience, availability, and skills..." 
                          class="w-full border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-sm sm:text-xs"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('applyJobModal')" class="px-4 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 text-xs">Cancel</button>
                <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-5 py-2.5 rounded-lg font-semibold text-xs">Confirm Application</button>
            </div>
        </form>
    </div>
</div>

<!-- 7. Comments Centered Dialog Modal -->
<div id="commentModal" class="modal-bg" onclick="closeModalOutside(event, 'commentModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-comments text-blue-900"></i> Comments & Discussion
            </h3>
            <button type="button" onclick="closeModal('commentModal')" class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <div id="commentList" class="max-h-72 overflow-y-auto space-y-2 mb-3 pr-1 text-xs">
            <!-- Dynamic comments -->
        </div>
        <div class="flex gap-2 border-t border-slate-100 pt-3">
            <input type="text" id="newCommentInput" onkeypress="if(event.key === 'Enter') handleAddComment()" placeholder="Write a comment..." 
                   class="flex-1 border border-slate-200 rounded-lg p-2.5 text-sm sm:text-xs bg-slate-50">
            <button onclick="handleAddComment()" class="bg-blue-900 hover:bg-blue-950 text-white px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer">
                Send
            </button>
        </div>
    </div>
</div>

<!-- 8. Emergency Hotlines Centered Dialog Modal -->
<div id="emergencyModal" class="modal-bg" onclick="closeModalOutside(event, 'emergencyModal')">
    <div class="modal-sheet">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-phone-alt text-slate-700"></i> Emergency Hotlines
            </h3>
            <button type="button" onclick="closeModal('emergencyModal')" class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-600 transition cursor-pointer">
                &times;
            </button>
        </div>
        <div class="space-y-2 text-xs">
            <a href="tel:911" class="flex justify-between items-center bg-slate-50 hover:bg-slate-100 rounded-lg p-3 text-slate-800 transition border border-slate-200">
                <span class="font-medium flex items-center gap-2"><i class="fas fa-ambulance text-slate-600"></i> Medical Emergency Ambulance</span>
                <span class="font-mono font-bold text-sm">911</span>
            </a>
            <a href="tel:117" class="flex justify-between items-center bg-slate-50 hover:bg-slate-100 rounded-lg p-3 text-slate-800 transition border border-slate-200">
                <span class="font-medium flex items-center gap-2"><i class="fas fa-shield-alt text-slate-600"></i> PNP National Police</span>
                <span class="font-mono font-bold text-sm">117</span>
            </a>
            <a href="tel:160" class="flex justify-between items-center bg-slate-50 hover:bg-slate-100 rounded-lg p-3 text-slate-800 transition border border-slate-200">
                <span class="font-medium flex items-center gap-2"><i class="fas fa-fire-extinguisher text-slate-600"></i> Bureau of Fire Protection</span>
                <span class="font-mono font-bold text-sm">160</span>
            </a>
            <a href="tel:09081234567" class="flex justify-between items-center bg-slate-50 hover:bg-slate-100 rounded-lg p-3 text-slate-800 transition border border-slate-200">
                <span class="font-medium flex items-center gap-2"><i class="fas fa-building text-slate-600"></i> Barangay Operations Desk (24/7)</span>
                <span class="font-mono font-bold text-sm">0908-123-4567</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // State initialization
    let posts = @json($posts);
    let activeFeedTab = 'recent';
    let currentPostId = null;
    let isStaff = {{ $isStaff ? 'true' : 'false' }};
    let currentActiveScreen = isStaff ? 'admin-overview' : 'home';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Screen Titles
    const screenTitles = {
        'admin-overview': 'Executive Overview',
        'admin-documents': 'Clearance Requests',
        'admin-relief': 'Relief Operations Desk',
        'admin-scholar': 'Scholarship Grants Review',
        'admin-jobs': 'Jobs & Recruiter Desk',
        'admin-staff': 'Staff Registry & Personnel',
        'admin-residents': 'Barangay Resident Registry',
        'admin-broadcast': 'Broadcast Official Advisory',
        'home': 'Community Feed',
        'health': 'Health Center Log',
        'jobs': 'Job Openings & PESO Livelihood',
        'scholar': 'Resident Scholarship Program',
        'relief': 'Disaster Relief Assistance',
        'post': 'Create Community Post'
    };

    // Mobile Drawer Open & Close
    function openMobileDrawer() {
        const drawer = document.getElementById('mobileDrawer');
        if (!drawer) return;
        drawer.classList.remove('pointer-events-none');
        drawer.classList.add('open');
        document.getElementById('mobileDrawerBackdrop').classList.remove('opacity-0', 'pointer-events-none');
        document.getElementById('mobileDrawerBackdrop').classList.add('opacity-100');
        document.getElementById('mobileDrawerPanel').classList.remove('-translate-x-full');
        document.body.classList.add('overflow-hidden');
    }

    function closeMobileDrawer() {
        const drawer = document.getElementById('mobileDrawer');
        if (!drawer) return;
        document.getElementById('mobileDrawerBackdrop').classList.remove('opacity-100');
        document.getElementById('mobileDrawerBackdrop').classList.add('opacity-0', 'pointer-events-none');
        document.getElementById('mobileDrawerPanel').classList.add('-translate-x-full');
        setTimeout(() => {
            drawer.classList.remove('open');
            drawer.classList.add('pointer-events-none');
            document.body.classList.remove('overflow-hidden');
        }, 220);
    }

    function switchScreenMobile(screenName) {
        closeMobileDrawer();
        switchScreen(screenName);
    }

    // User Dropdown toggle
    function toggleUserDropdown() {
        document.getElementById('userDropdown')?.classList.toggle('hidden');
    }

    // Modal helpers
    function openModal(id) {
        document.getElementById(id)?.classList.add('open');
    }
    function closeModal(id) {
        document.getElementById(id)?.classList.remove('open');
    }
    function closeModalOutside(e, id) {
        if (e.target.id === id) closeModal(id);
    }

    // Screen Switcher
    function switchScreen(screenName) {
        currentActiveScreen = screenName;

        // Hide all screens
        document.querySelectorAll('.screen-view').forEach(el => el.classList.add('hidden'));

        // Show target screen
        const targetScreen = document.getElementById('screen-' + screenName);
        if (targetScreen) targetScreen.classList.remove('hidden');

        // Update Desktop Sidebar
        document.querySelectorAll('.sidebar-nav-item').forEach(item => item.classList.remove('active'));
        const activeSidebarItem = document.getElementById('sidebar-nav-' + screenName);
        if (activeSidebarItem) activeSidebarItem.classList.add('active');

        // Update Desktop Header Badge
        const badge = document.getElementById('desktop-screen-badge');
        if (badge) badge.innerText = screenTitles[screenName] || 'Resident Hub';

        // Update Mobile Label
        const mobileLabel = document.getElementById('mobile-current-screen-label');
        if (mobileLabel) mobileLabel.innerText = screenTitles[screenName] || 'Resident Hub';

        // Update Mobile Category Chips
        document.querySelectorAll('.mobile-chip').forEach(chip => {
            chip.classList.remove('active');
        });
        const activeChip = document.getElementById('mobile-chip-' + screenName);
        if (activeChip) {
            activeChip.classList.add('active');
            activeChip.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }

        // Update Mobile Bottom Nav highlights
        document.querySelectorAll('.nav-item').forEach(btn => {
            btn.classList.remove('active', 'border-blue-900', 'text-blue-900');
            btn.classList.add('border-transparent', 'text-slate-500');
        });
        const activeNavBtn = document.getElementById('nav-btn-' + screenName);
        if (activeNavBtn) {
            activeNavBtn.classList.add('active', 'border-blue-900', 'text-blue-900');
            activeNavBtn.classList.remove('border-transparent', 'text-slate-500');
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Admin Jobs Sub-tab Switcher
    function switchAdminJobTab(tabName) {
        ['openings', 'applicants', 'recruiters'].forEach(t => {
            document.getElementById('admin-job-tab-' + t)?.classList.add('hidden');
            document.getElementById('admin-job-tab-btn-' + t)?.classList.remove('active');
        });
        document.getElementById('admin-job-tab-' + tabName)?.classList.remove('hidden');
        document.getElementById('admin-job-tab-btn-' + tabName)?.classList.add('active');
    }

    // Feed Tabs Filter
    function setFeedTab(tab) {
        activeFeedTab = tab;
        ['recent', 'popular', 'questions'].forEach(t => {
            const btn = document.getElementById('feed-tab-' + t);
            if (btn) {
                if (t === tab) {
                    btn.className = 'px-3 py-1 rounded-md bg-blue-900 text-white cursor-pointer whitespace-nowrap';
                } else {
                    btn.className = 'px-3 py-1 rounded-md bg-slate-100 text-slate-600 hover:bg-slate-200 cursor-pointer whitespace-nowrap';
                }
            }
        });
        renderFeed();
    }

    // Render Community Feed
    function renderFeed() {
        const container = document.getElementById('postsFeedContainer');
        if (!container) return;

        let displayPosts = [...posts];
        if (activeFeedTab === 'popular') {
            displayPosts.sort((a, b) => (b.likes || 0) - (a.likes || 0));
        } else if (activeFeedTab === 'questions') {
            displayPosts = displayPosts.filter(p => p.type === 'question');
        }

        if (displayPosts.length === 0) {
            container.innerHTML = `
                <div class="civic-card p-8 text-center text-slate-400 text-xs">
                    <i class="fas fa-inbox text-2xl mb-2"></i>
                    <p>No community posts in this category yet.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = displayPosts.map(post => {
            const isAnnouncement = post.type === 'announcement' || post.content.includes('[OFFICIAL BARANGAY ADVISORY]');
            
            let mediaHtml = '';
            if (post.media_url) {
                if (post.media_type === 'video') {
                    mediaHtml = `
                        <div class="mt-2.5 mb-2 overflow-hidden rounded-xl border border-slate-200 bg-black">
                            <video controls playsinline preload="metadata" class="w-full max-h-80 sm:max-h-96 rounded-xl" src="${post.media_url}"></video>
                        </div>
                    `;
                } else {
                    mediaHtml = `
                        <div class="mt-2.5 mb-2 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center">
                            <img src="${post.media_url}" alt="Post attachment" class="w-full h-auto max-h-80 sm:max-h-96 object-contain cursor-pointer hover:opacity-95 transition rounded-xl" onclick="window.open('${post.media_url}', '_blank')">
                        </div>
                    `;
                }
            }

            return `
                <div class="civic-card p-4 sm:p-5 post-card ${isAnnouncement ? 'border-l-4 border-l-blue-900 bg-blue-50/20' : ''}" id="post-${post.id}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg ${isAnnouncement ? 'bg-blue-900 text-white' : 'bg-slate-200 text-slate-700'} font-bold text-xs flex items-center justify-center flex-shrink-0">
                                ${isAnnouncement ? '<i class="fas fa-bullhorn text-xs"></i>' : (post.avatar || 'U')}
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1.5 truncate">
                                    ${post.author}
                                    ${isAnnouncement ? '<span class="badge-clean-blue text-[10px] px-1.5 py-0.2 rounded font-semibold">Advisory</span>' : ''}
                                </h4>
                                <span class="text-[10px] text-slate-400 font-mono">${post.time || 'Recent'}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line mb-3">
                        ${post.content}
                    </p>

                    ${mediaHtml}

                    <div class="flex items-center gap-4 pt-2 border-t border-slate-100 text-xs text-slate-500">
                        <button onclick="toggleLike(${post.id})" class="like-btn flex items-center gap-1.5 hover:text-slate-900 transition cursor-pointer ${post.liked ? 'liked font-bold' : ''}">
                            <i class="${post.liked ? 'fas' : 'far'} fa-heart"></i>
                            <span id="likes-count-${post.id}">${post.likes || 0}</span>
                        </button>
                        <button onclick="openCommentModal(${post.id})" class="flex items-center gap-1.5 hover:text-slate-900 transition cursor-pointer">
                            <i class="far fa-comment"></i>
                            <span>${(post.comments || []).length}</span>
                        </button>
                        <button onclick="sharePost(${post.id})" class="flex items-center gap-1.5 hover:text-slate-900 transition cursor-pointer ml-auto">
                            <i class="far fa-share-square"></i>
                            <span>Share</span>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Toggle Post Like
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
            if (data.liked !== undefined) {
                const target = posts.find(p => p.id === postId);
                if (target) {
                    target.liked = data.liked;
                    target.likes = data.likes_count;
                }
                renderFeed();
            }
        } catch (e) {
            showToast('Unable to update like', 'error');
        }
    }

    // Media Upload & Client-Side Compression Storage
    const mediaBlobs = {
        post: null,
        broadcast: null
    };
    const mediaTypes = {
        post: null,
        broadcast: null
    };

    function handleMediaSelect(e, formPrefix) {
        const file = e.target.files && e.target.files[0];
        if (!file) return;

        const previewBox = document.getElementById(formPrefix + 'MediaPreviewBox');
        const previewContent = document.getElementById(formPrefix + 'MediaPreviewContent');
        const infoLabel = document.getElementById(formPrefix + 'MediaInfo');
        const statusLabel = document.getElementById(formPrefix + 'MediaStatus');

        if (file.type.startsWith('image/')) {
            if (statusLabel) statusLabel.textContent = 'Optimizing image...';
            mediaTypes[formPrefix] = 'image';

            const reader = new FileReader();
            reader.onload = function(evt) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    const maxDim = 1280;

                    if (width > maxDim || height > maxDim) {
                        if (width > height) {
                            height = Math.round((height * maxDim) / width);
                            width = maxDim;
                        } else {
                            width = Math.round((width * maxDim) / height);
                            height = maxDim;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(function(blob) {
                        mediaBlobs[formPrefix] = blob;
                        const origSizeKB = Math.round(file.size / 1024);
                        const compSizeKB = Math.round(blob.size / 1024);

                        if (previewContent) {
                            previewContent.innerHTML = `<img src="${URL.createObjectURL(blob)}" class="max-h-52 rounded object-contain mx-auto" />`;
                        }
                        if (infoLabel) {
                            infoLabel.innerHTML = `<span class="text-emerald-700 font-semibold">Compressed: ${compSizeKB} KB</span> <span class="text-slate-400 line-through">(${origSizeKB} KB)</span>`;
                        }
                        if (previewBox) previewBox.classList.remove('hidden');
                        if (statusLabel) statusLabel.textContent = 'Image compressed & ready';
                    }, 'image/jpeg', 0.82);
                };
                img.src = evt.target.result;
            };
            reader.readAsDataURL(file);
        } else if (file.type.startsWith('video/')) {
            const sizeMB = file.size / (1024 * 1024);
            if (sizeMB > 15) {
                alert(`Selected video is too large (${sizeMB.toFixed(1)}MB). Please select a video under 15MB (e.g. under 60 seconds) to ensure quick mobile delivery.`);
                e.target.value = '';
                clearMedia(formPrefix);
                return;
            }

            mediaTypes[formPrefix] = 'video';
            mediaBlobs[formPrefix] = file;

            const videoUrl = URL.createObjectURL(file);
            if (previewContent) {
                previewContent.innerHTML = `<video src="${videoUrl}" controls playsinline class="max-h-52 rounded mx-auto"></video>`;
            }
            if (infoLabel) {
                infoLabel.innerHTML = `<span class="text-blue-900 font-semibold"><i class="fas fa-video mr-1"></i> Video (${sizeMB.toFixed(1)} MB)</span>`;
            }
            if (previewBox) previewBox.classList.remove('hidden');
            if (statusLabel) statusLabel.textContent = 'Video ready';
        } else {
            alert('Please select an image or video file (MP4, WebM, MOV, JPG, PNG).');
            e.target.value = '';
        }
    }

    function clearMedia(formPrefix) {
        mediaBlobs[formPrefix] = null;
        mediaTypes[formPrefix] = null;
        const fileInput = document.getElementById(formPrefix + 'MediaInput');
        if (fileInput) fileInput.value = '';
        const previewBox = document.getElementById(formPrefix + 'MediaPreviewBox');
        if (previewBox) previewBox.classList.add('hidden');
        const previewContent = document.getElementById(formPrefix + 'MediaPreviewContent');
        if (previewContent) previewContent.innerHTML = '';
        const statusLabel = document.getElementById(formPrefix + 'MediaStatus');
        if (statusLabel) statusLabel.textContent = 'Max 15MB video · Photos auto-compressed';
    }

    // Create Community Post
    async function handlePostSubmit(e) {
        e.preventDefault();
        const content = document.getElementById('postContent').value.trim();
        const type = document.getElementById('postType').value;
        if (!content) return;

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const origText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Publishing...';

        const formData = new FormData();
        formData.append('content', content);
        formData.append('type', type);
        formData.append('visibility', 'Public');

        if (mediaBlobs.post) {
            const ext = mediaTypes.post === 'image' ? 'jpg' : (mediaBlobs.post.name ? mediaBlobs.post.name.split('.').pop() : 'mp4');
            formData.append('media', mediaBlobs.post, mediaTypes.post === 'image' ? 'upload.jpg' : (mediaBlobs.post.name || 'video.mp4'));
        }

        try {
            const res = await fetch("{{ route('posts.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await res.json();
            if (data.success && data.post) {
                posts.unshift(data.post);
                document.getElementById('postContent').value = '';
                clearMedia('post');
                renderFeed();
                showToast('Post published to community feed!');
                switchScreen('home');
            } else {
                showToast(data.message || 'Failed to create post', 'error');
            }
        } catch (err) {
            showToast('Failed to create post', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;
        }
    }

    // Post Official Advisory (Staff)
    async function handleBroadcastSubmit(e) {
        e.preventDefault();
        const content = document.getElementById('broadcastContent').value.trim();
        if (!content) return;

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const origText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Broadcasting...';

        const formData = new FormData();
        formData.append('content', content);

        if (mediaBlobs.broadcast) {
            const ext = mediaTypes.broadcast === 'image' ? 'jpg' : (mediaBlobs.broadcast.name ? mediaBlobs.broadcast.name.split('.').pop() : 'mp4');
            formData.append('media', mediaBlobs.broadcast, mediaTypes.broadcast === 'image' ? 'upload.jpg' : (mediaBlobs.broadcast.name || 'video.mp4'));
        }

        try {
            const res = await fetch("{{ route('admin.broadcast') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await res.json();
            if (data.success && data.post) {
                posts.unshift(data.post);
                document.getElementById('broadcastContent').value = '';
                clearMedia('broadcast');
                renderFeed();
                showToast('Official advisory published!');
                switchScreen('home');
            } else {
                showToast(data.message || 'Failed to publish advisory', 'error');
            }
        } catch (err) {
            showToast('Failed to publish advisory', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;
        }
    }

    // Request Document / Clearance (Resident)
    async function handleRequestDocSubmit(e) {
        e.preventDefault();
        const serviceType = document.getElementById('requestDocType').value;
        const fullName = document.getElementById('requestDocName').value.trim();
        const phone = document.getElementById('requestDocPhone').value.trim();
        const purpose = document.getElementById('requestDocPurpose').value.trim();

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const origText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Submitting...';

        try {
            const res = await fetch("{{ route('services.request') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    service_type: serviceType,
                    full_name: fullName,
                    contact_number: phone,
                    purpose: purpose
                })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Clearance request submitted successfully!');
                closeModal('requestDocModal');
                document.getElementById('requestDocPurpose').value = '';
            } else {
                showToast(data.message || 'Error submitting request', 'error');
            }
        } catch (err) {
            showToast('Failed to submit clearance request', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;
        }
    }

    // Update Clearance Status
    async function updateRequestStatus(id, newStatus) {
        try {
            const res = await fetch(`/admin/requests/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || `Status set to ${newStatus}`);
                const badge = document.getElementById('badge-req-' + id);
                if (badge) {
                    badge.innerText = newStatus;
                    badge.className = 'status-badge px-2.5 py-0.5 rounded-md text-xs font-semibold self-start ' + 
                        (newStatus === 'Ready for Pick-up' ? 'badge-clean-blue' : (newStatus === 'Issued' ? 'badge-clean-green' : 'badge-clean-red'));
                }
            }
        } catch (e) {
            showToast('Failed to update request', 'error');
        }
    }

    // Update Relief Status
    async function updateReliefStatus(id, newStatus) {
        try {
            const res = await fetch(`/admin/relief/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || `Relief status: ${newStatus}`);
                const badge = document.getElementById('relief-badge-' + id);
                if (badge) {
                    badge.innerText = newStatus;
                    badge.className = 'status-badge px-2.5 py-0.5 rounded-md text-xs font-semibold self-start ' +
                        (newStatus === 'Dispatched' ? 'badge-clean-blue' : 'badge-clean-green');
                }
            }
        } catch (e) {
            showToast('Failed to update relief status', 'error');
        }
    }

    // Post Job Opening (Staff)
    function handleRecruiterSelectChange(sel) {
        const text = sel.options[sel.selectedIndex].text;
        const compInput = document.getElementById('postJobCompanyName');
        if (sel.value && text && !compInput.value) {
            compInput.value = text.replace(' (Accredited)', '');
        }
    }

    async function handlePostJobOpening(e) {
        e.preventDefault();
        const payload = {
            title: document.getElementById('postJobTitle').value.trim(),
            job_recruiter_id: document.getElementById('postJobRecruiterId').value || null,
            company_name: document.getElementById('postJobCompanyName').value.trim(),
            employment_type: document.getElementById('postJobType').value,
            salary_range: document.getElementById('postJobSalary').value.trim(),
            vacancies: parseInt(document.getElementById('postJobVacancies').value) || 1,
            description: document.getElementById('postJobDesc').value.trim(),
            requirements: document.getElementById('postJobReqs').value.trim(),
        };

        try {
            const res = await fetch("{{ route('admin.jobs.openings.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Job opening published!');
                closeModal('postJobModal');
                setTimeout(() => window.location.reload(), 800);
            }
        } catch (e) {
            showToast('Failed to publish job opening', 'error');
        }
    }

    // Update Job Opening Status (Staff)
    async function updateJobOpeningStatus(id, newStatus) {
        try {
            const res = await fetch(`/admin/jobs/openings/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Job opening status updated!');
                setTimeout(() => window.location.reload(), 600);
            }
        } catch (e) {
            showToast('Failed to update opening status', 'error');
        }
    }

    // Delete Job Opening (Staff)
    async function deleteJobOpening(id) {
        if (!confirm('Are you sure you want to remove this job opening?')) return;
        try {
            const res = await fetch(`/admin/jobs/openings/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Job opening deleted');
                document.getElementById('job-opening-card-' + id)?.remove();
            }
        } catch (e) {
            showToast('Failed to delete opening', 'error');
        }
    }

    // Register Recruiter (Public / Staff Intake)
    async function handleRecruiterRegisterSubmit(e) {
        e.preventDefault();
        const payload = {
            company_name: document.getElementById('recruiterCompanyName').value.trim(),
            contact_person: document.getElementById('recruiterContactPerson').value.trim(),
            industry: document.getElementById('recruiterIndustry').value.trim(),
            email: document.getElementById('recruiterEmail').value.trim(),
            phone: document.getElementById('recruiterPhone').value.trim(),
            permit_number: document.getElementById('recruiterPermit').value.trim(),
            address: document.getElementById('recruiterAddress').value.trim(),
            description: document.getElementById('recruiterDescription').value.trim(),
        };

        const targetUrl = isStaff ? "{{ route('admin.recruiters.store') }}" : "{{ route('recruiters.register') }}";
        if (isStaff) payload.status = 'Approved';

        try {
            const res = await fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Recruiter application submitted!');
                closeModal('registerRecruiterModal');
                if (isStaff) setTimeout(() => window.location.reload(), 800);
            }
        } catch (e) {
            showToast('Failed to submit recruiter registration', 'error');
        }
    }

    // Update Recruiter Status (Staff)
    async function updateRecruiterStatus(id, newStatus) {
        try {
            const res = await fetch(`/admin/recruiters/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Recruiter status updated');
                setTimeout(() => window.location.reload(), 600);
            }
        } catch (e) {
            showToast('Failed to update recruiter status', 'error');
        }
    }

    // Register Staff Member (Super Admin)
    async function handleStoreStaffSubmit(e) {
        e.preventDefault();
        const payload = {
            name: document.getElementById('staffName').value.trim(),
            email: document.getElementById('staffEmail').value.trim(),
            barangay_id: document.getElementById('staffBarangayId').value.trim(),
            role: document.getElementById('staffRole').value,
            designation: document.getElementById('staffDesignation').value.trim(),
            phone: document.getElementById('staffPhone').value.trim(),
            password: document.getElementById('staffPassword').value,
        };

        try {
            const res = await fetch("{{ route('admin.users.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Staff member created!');
                closeModal('addStaffModal');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showToast(data.message || 'Error creating staff', 'error');
            }
        } catch (e) {
            showToast('Failed to register staff account', 'error');
        }
    }

    // Open Edit Role Modal
    function openEditRoleModal(id, name, role, designation) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUserName').innerText = name;
        document.getElementById('editUserRoleSelect').value = role;
        document.getElementById('editUserDesignation').value = designation || '';
        openModal('editUserRoleModal');
    }

    async function handleUpdateRoleSubmit(e) {
        e.preventDefault();
        const id = document.getElementById('editUserId').value;
        const role = document.getElementById('editUserRoleSelect').value;
        const designation = document.getElementById('editUserDesignation').value.trim();

        try {
            const res = await fetch(`/admin/users/${id}/role`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ role: role, designation: designation })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'User role updated!');
                closeModal('editUserRoleModal');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showToast(data.message || 'Error updating role', 'error');
            }
        } catch (e) {
            showToast('Failed to update role', 'error');
        }
    }

    // Toggle User Status
    async function toggleUserStatus(id) {
        try {
            const res = await fetch(`/admin/users/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Status updated');
                const badge = document.getElementById('staff-status-' + id);
                if (badge) {
                    badge.innerText = data.is_active ? 'Active' : 'Deactivated';
                    badge.className = 'px-2 py-0.5 rounded text-[10px] font-semibold ' + (data.is_active ? 'badge-clean-green' : 'badge-clean-slate');
                }
            }
        } catch (e) {
            showToast('Failed to toggle status', 'error');
        }
    }

    // Reset User Password
    function openResetPasswordModal(id, name) {
        document.getElementById('resetPasswordUserId').value = id;
        document.getElementById('resetPasswordUserName').innerText = name;
        document.getElementById('newResetPassword').value = '';
        openModal('resetPasswordModal');
    }

    async function handleResetPasswordSubmit(e) {
        e.preventDefault();
        const id = document.getElementById('resetPasswordUserId').value;
        const pass = document.getElementById('newResetPassword').value;

        try {
            const res = await fetch(`/admin/users/${id}/reset-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ password: pass })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Password reset successfully');
                closeModal('resetPasswordModal');
            }
        } catch (e) {
            showToast('Failed to reset password', 'error');
        }
    }

    // Resident Job Apply Modal Trigger
    function openApplyJobModal(jobId, jobTitle) {
        document.getElementById('applyJobOpeningId').value = jobId;
        document.getElementById('applyJobTitleHidden').value = jobTitle;
        document.getElementById('applyJobTitleDisplay').innerText = jobTitle;
        document.getElementById('applyJobNotes').value = '';
        openModal('applyJobModal');
    }

    async function handleConfirmJobApply(e) {
        e.preventDefault();
        const jobId = document.getElementById('applyJobOpeningId').value;
        const jobTitle = document.getElementById('applyJobTitleHidden').value;
        const notes = document.getElementById('applyJobNotes').value.trim();

        try {
            const res = await fetch("{{ route('services.job') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    job_title: jobTitle,
                    job_opening_id: jobId ? parseInt(jobId) : null,
                    notes: notes
                })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || `Application sent for ${jobTitle}!`);
                closeModal('applyJobModal');
            }
        } catch (e) {
            showToast('Failed to submit application', 'error');
        }
    }

    // Update Scholarship Status (Staff)
    async function updateScholarshipStatus(id, newStatus) {
        try {
            const res = await fetch(`/admin/scholarships/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || `Scholarship status: ${newStatus}`);
                const badge = document.getElementById('sch-badge-' + id);
                if (badge) {
                    badge.innerText = newStatus;
                    badge.className = 'status-badge px-2.5 py-0.5 rounded-md text-xs font-semibold self-start ' + 
                        (newStatus === 'Granted' ? 'badge-clean-green' : 'badge-clean-red');
                }
            }
        } catch (e) {
            showToast('Failed to update scholarship', 'error');
        }
    }

    // Update Job Applicant Status (Staff)
    async function updateJobStatus(id, newStatus) {
        try {
            const res = await fetch(`/admin/jobs/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || `Applicant status: ${newStatus}`);
                const badge = document.getElementById('job-badge-' + id);
                if (badge) {
                    badge.innerText = newStatus;
                    badge.className = 'status-badge px-2.5 py-0.5 rounded-md text-xs font-semibold self-start badge-clean-green';
                }
            }
        } catch (e) {
            showToast('Failed to update applicant status', 'error');
        }
    }

    // Filter Resident Registry Table & Mobile Cards
    function filterResidentRegistry() {
        const query = document.getElementById('residentSearchInput')?.value.toLowerCase() || '';
        document.querySelectorAll('.resident-row').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }

    // Comments Modal
    function openCommentModal(postId) {
        currentPostId = postId;
        const target = posts.find(p => p.id === postId);
        const list = document.getElementById('commentList');
        if (!target || !list) return;

        const comments = target.comments || [];
        if (comments.length === 0) {
            list.innerHTML = '<p class="text-xs text-slate-400 py-3 text-center">No comments yet. Start the conversation!</p>';
        } else {
            list.innerHTML = comments.map(c => `
                <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-slate-900">${c.author}</span>
                        <span class="text-[10px] text-slate-400">${c.time || 'Recent'}</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">${c.text}</p>
                </div>
            `).join('');
        }
        openModal('commentModal');
    }

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

    // Submit Health Record
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
            }
        } catch (e) {
            showToast('Failed to save health record', 'error');
        }
    }

    // Submit Relief Request
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

    // Submit Scholarship
    async function handleScholarSubmit(e) {
        e.preventDefault();
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
                body: JSON.stringify({ notes: notes })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Scholarship application received!');
                document.getElementById('scholarSchool').value = '';
                document.getElementById('scholarCourse').value = '';
                document.getElementById('scholarGWA').value = '';
            }
        } catch (e) {
            showToast('Failed to submit scholarship', 'error');
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

    // Toast Notification Utility
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const toast = document.createElement('div');
        const bgClass = type === 'success' ? 'bg-slate-900 text-white' : (type === 'error' ? 'bg-rose-800 text-white' : 'bg-blue-900 text-white');
        toast.className = `toast px-3.5 py-2.5 rounded-lg shadow-lg flex items-center gap-2.5 text-xs font-medium ${bgClass} transition-opacity duration-300 pointer-events-auto`;
        toast.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle text-emerald-400' : 'fa-info-circle text-blue-300'}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 hover:opacity-75">&times;</button>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, 3500);
    }

    // Initial feed render
    renderFeed();
</script>
@endpush
