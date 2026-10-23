@extends('layouts.app')

@section('title', 'Barangay San Agustin - Resident Community Hub')

@section('body-class', 'bg-slate-100 min-h-screen pb-20')

@push('styles')
<style>
    /* Bottom Nav Active */
    .nav-item.active .nav-icon { color: #1d4ed8; }
    .nav-item.active .nav-label { color: #1d4ed8; font-weight: 700; }
    .nav-item.active { border-top: 3px solid #1d4ed8; }

    /* Module cards */
    .module-card {
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }
    .module-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 30px -8px rgba(0,0,0,0.13);
    }
    .module-card:active { transform: scale(0.97); }

    /* Post Card Styles */
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

    /* Welcome gradient */
    .welcome-gradient {
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%);
    }

    /* Feed container */
    .feed-container {
        max-height: calc(100vh - 280px);
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
    <!-- TOP NAV -->
    <nav class="bg-white shadow-sm sticky top-0 z-40 border-b border-slate-100">
        <div class="max-w-2xl mx-auto px-4 h-14 flex justify-between items-center">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-blue-700 flex items-center justify-center text-white text-xs font-bold shadow">SA</div>
                <div>
                    <a href="{{ route('home') }}" class="text-sm font-bold text-blue-900 leading-none hover:underline">Brgy. San Agustin</a>
                    <p class="text-[10px] text-slate-400">Community Hub</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="relative p-2" onclick="openModal('reliefModal')" title="Disaster Relief">
                    <i class="fas fa-hand-holding-heart text-red-500 text-lg"></i>
                </button>
                <button class="relative p-2" onclick="openModal('emergencyModal')" title="Emergency Hotlines">
                    <i class="fas fa-phone-alt text-slate-500 text-lg"></i>
                </button>
                <button id="userMenuBtn" onclick="toggleUserDropdown()" class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center border-2 border-blue-200">
                    <span class="text-blue-700 font-bold text-xs">{{ $user->initials }}</span>
                </button>
            </div>
        </div>
        <!-- User dropdown -->
        <div id="userDropdown" class="hidden absolute right-4 top-14 bg-white rounded-xl shadow-xl border w-48 z-50">
            <div class="px-4 py-2.5 border-b">
                <p class="text-sm font-semibold text-slate-700" id="residentNameDisplay">{{ $user->name }}</p>
                <p class="text-xs text-slate-400">{{ $user->barangay_id ?? 'BRGY-RESIDENT' }}</p>
            </div>
            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                <i class="fas fa-home mr-2 text-slate-400"></i> Barangay Website
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-slate-50 flex items-center gap-2">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div id="homeContent" class="max-w-2xl mx-auto px-4 pt-4">
        <!-- Welcome Card -->
        <div class="welcome-gradient rounded-2xl p-5 text-white shadow-lg mb-5 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-white/10 rounded-full"></div>
            <div class="absolute -right-2 bottom-2 w-16 h-16 bg-white/5 rounded-full"></div>
            <p class="text-blue-200 text-xs font-medium uppercase tracking-wider">Good day 👋</p>
            <h2 class="text-xl font-bold mt-0.5" id="welcomeName">{{ $user->name }}</h2>
            <p class="text-blue-200 text-xs mt-1">{{ $user->address ?? 'Barangay San Agustin Resident' }}</p>
            <div class="flex gap-3 mt-4">
                <div class="bg-white/15 rounded-xl px-3 py-2 text-center flex-1">
                    <p class="text-lg font-bold" id="totalActivityCount">{{ $userStats['posts_count'] }}</p>
                    <p class="text-[10px] text-blue-200">Posts</p>
                </div>
                <div class="bg-white/15 rounded-xl px-3 py-2 text-center flex-1">
                    <p class="text-lg font-bold text-green-300">Active</p>
                    <p class="text-[10px] text-blue-200">Status</p>
                </div>
                <div class="bg-white/15 rounded-xl px-3 py-2 text-center flex-1">
                    <p class="text-lg font-bold">{{ $userStats['online_count'] }}</p>
                    <p class="text-[10px] text-blue-200">Residents Online</p>
                </div>
            </div>
        </div>

        <!-- Quick Access Services Row -->
        <div class="grid grid-cols-4 gap-2 mb-4">
            <button onclick="openModal('reliefModal')" class="bg-white p-2.5 rounded-xl text-center shadow-sm border border-slate-100 hover:bg-slate-50 transition">
                <i class="fas fa-hand-holding-heart text-red-500 text-base mb-1 block"></i>
                <span class="text-[11px] font-semibold text-slate-700 block">Relief</span>
            </button>
            <button onclick="openModal('healthModal')" class="bg-white p-2.5 rounded-xl text-center shadow-sm border border-slate-100 hover:bg-slate-50 transition">
                <i class="fas fa-heartbeat text-green-600 text-base mb-1 block"></i>
                <span class="text-[11px] font-semibold text-slate-700 block">Health</span>
            </button>
            <button onclick="openModal('jobsModal')" class="bg-white p-2.5 rounded-xl text-center shadow-sm border border-slate-100 hover:bg-slate-50 transition">
                <i class="fas fa-briefcase text-purple-600 text-base mb-1 block"></i>
                <span class="text-[11px] font-semibold text-slate-700 block">Jobs</span>
            </button>
            <button onclick="openModal('scholarshipModal')" class="bg-white p-2.5 rounded-xl text-center shadow-sm border border-slate-100 hover:bg-slate-50 transition">
                <i class="fas fa-graduation-cap text-yellow-600 text-base mb-1 block"></i>
                <span class="text-[11px] font-semibold text-slate-700 block">Scholar</span>
            </button>
        </div>

        <!-- Create Post Button -->
        <button onclick="openModal('createPostModal')" class="w-full bg-white rounded-2xl p-3 shadow-sm border border-slate-100 mb-4 flex items-center gap-3 hover:bg-slate-50 transition">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-xs">
                {{ $user->initials }}
            </div>
            <span class="text-slate-500 text-sm">What's on your mind, {{ strtok($user->name, " ") }}?</span>
            <i class="fas fa-pen text-blue-500 ml-auto mr-2"></i>
        </button>

        <!-- Feed Tabs -->
        <div class="flex gap-2 mb-4 border-b border-slate-200">
            <button onclick="switchFeedTab('recent', this)" class="feed-tab-btn px-4 py-2 text-sm font-semibold text-blue-600 border-b-2 border-blue-600">Recent</button>
            <button onclick="switchFeedTab('popular', this)" class="feed-tab-btn px-4 py-2 text-sm font-semibold text-slate-500">Popular</button>
            <button onclick="switchFeedTab('questions', this)" class="feed-tab-btn px-4 py-2 text-sm font-semibold text-slate-500">Questions</button>
        </div>

        <!-- Community Feed -->
        <div id="communityFeed" class="space-y-4 pb-4 feed-container">
            <!-- Posts dynamically rendered from state -->
        </div>
    </div>

    <!-- BOTTOM NAVIGATION -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-40 shadow-lg">
        <div class="max-w-2xl mx-auto grid grid-cols-5 h-16">
            <button class="nav-item active flex flex-col items-center justify-center gap-0.5 border-t-2 border-blue-700" onclick="window.scrollTo({top:0, behavior:'smooth'})">
                <i class="nav-icon fas fa-home text-blue-700 text-lg"></i>
                <span class="nav-label text-[9px] text-blue-700 font-bold">Home</span>
            </button>
            <button class="nav-item flex flex-col items-center justify-center gap-0.5 border-t-2 border-transparent" onclick="openModal('createPostModal')">
                <i class="nav-icon fas fa-pen-square text-slate-400 text-lg"></i>
                <span class="nav-label text-[9px] text-slate-400">Post</span>
            </button>
            <button class="nav-item flex flex-col items-center justify-center gap-0.5 border-t-2 border-transparent" onclick="openModal('healthModal')">
                <i class="nav-icon fas fa-heartbeat text-slate-400 text-lg"></i>
                <span class="nav-label text-[9px] text-slate-400">Health</span>
            </button>
            <button class="nav-item flex flex-col items-center justify-center gap-0.5 border-t-2 border-transparent" onclick="openModal('jobsModal')">
                <i class="nav-icon fas fa-briefcase text-slate-400 text-lg"></i>
                <span class="nav-label text-[9px] text-slate-400">Jobs</span>
            </button>
            <button class="nav-item flex flex-col items-center justify-center gap-0.5 border-t-2 border-transparent" onclick="openModal('scholarshipModal')">
                <i class="nav-icon fas fa-graduation-cap text-slate-400 text-lg"></i>
                <span class="nav-label text-[9px] text-slate-400">Scholar</span>
            </button>
        </div>
    </nav>

    <!-- ============ MODALS ============ -->

    <!-- Create Post Modal -->
    <div id="createPostModal" class="modal-bg" onclick="closeModalOutside(event, 'createPostModal')">
        <div class="modal-sheet">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-blue-700 flex items-center gap-2"><i class="fas fa-pen-alt"></i> Create Post</h3>
                <button onclick="closeModal('createPostModal')" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">&times;</button>
            </div>
            <form onsubmit="handleCreatePost(event)">
                @csrf
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-xs">
                        {{ $user->initials }}
                    </div>
                    <div>
                        <p class="font-semibold text-sm">{{ $user->name }}</p>
                        <div class="flex gap-2">
                            <select id="postTypeSelect" class="text-xs text-slate-600 bg-slate-100 rounded px-1.5 py-0.5 border-none focus:outline-none">
                                <option value="general">General</option>
                                <option value="question">Question</option>
                                <option value="feedback">Feedback</option>
                                <option value="announcement">Announcement</option>
                            </select>
                            <select id="postVisibilitySelect" class="text-xs text-slate-600 bg-slate-100 rounded px-1.5 py-0.5 border-none focus:outline-none">
                                <option value="Public">Public</option>
                                <option value="Residents Only">Residents Only</option>
                            </select>
                        </div>
                    </div>
                </div>
                <textarea id="postContent" rows="4" placeholder="Share your thoughts, questions, or feedback with neighbors..." class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:ring-2 focus:ring-blue-300 focus:outline-none resize-none" required></textarea>
                <button type="submit" id="createPostSubmitBtn" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold mt-4 hover:bg-blue-700 transition">Post to Community</button>
            </form>
        </div>
    </div>

    <!-- Comment Modal -->
    <div id="commentModal" class="modal-bg" onclick="closeModalOutside(event, 'commentModal')">
        <div class="modal-sheet">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-blue-700 flex items-center gap-2"><i class="fas fa-comments"></i> Comments</h3>
                <button onclick="closeModal('commentModal')" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">&times;</button>
            </div>
            <div id="commentList" class="max-h-64 overflow-y-auto space-y-3 mb-4">
                <!-- Comments rendered here -->
            </div>
            <div class="flex gap-2 border-t pt-3">
                <input type="text" id="newCommentInput" placeholder="Write a friendly comment..." class="flex-1 border border-slate-200 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-300 focus:outline-none">
                <button onclick="handleAddComment()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 rounded-xl text-sm font-semibold">Send</button>
            </div>
        </div>
    </div>

    <!-- Disaster Relief Modal -->
    <div id="reliefModal" class="modal-bg" onclick="closeModalOutside(event, 'reliefModal')">
        <div class="modal-sheet">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-red-700 flex items-center gap-2"><i class="fas fa-hand-holding-heart"></i> Disaster Relief Request</h3>
                <button onclick="closeModal('reliefModal')" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">&times;</button>
            </div>
            <div class="bg-red-50 rounded-xl p-3 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-red-500"></i>
                <p class="text-xs text-red-700">Relief requests are prioritized and processed within 24 hours.</p>
            </div>
            <form onsubmit="handleReliefSubmit(event)" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-semibold text-slate-600 block mb-1">Type of Assistance</label>
                    <select id="reliefType" class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:ring-2 focus:ring-red-300 focus:outline-none" required>
                        <option value="Food Pack">Food Pack</option>
                        <option value="Emergency Shelter">Emergency Shelter Kit</option>
                        <option value="Medical Assistance">Medical Assistance</option>
                        <option value="Water Supply">Drinking Water Supply</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600 block mb-1">Details / Situation</label>
                    <textarea id="reliefDetails" rows="3" placeholder="Describe your household situation and location..." class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:ring-2 focus:ring-red-300 focus:outline-none resize-none"></textarea>
                </div>
                <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-xl font-semibold hover:bg-red-700 transition">Submit Relief Request</button>
            </form>
        </div>
    </div>

    <!-- Health Monitoring Modal -->
    <div id="healthModal" class="modal-bg" onclick="closeModalOutside(event, 'healthModal')">
        <div class="modal-sheet">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-green-700 flex items-center gap-2"><i class="fas fa-heartbeat"></i> Health Log</h3>
                <button onclick="closeModal('healthModal')" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">&times;</button>
            </div>
            <form onsubmit="handleHealthSubmit(event)" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-semibold text-slate-600 block mb-1">Blood Pressure</label>
                    <input type="text" id="healthBP" placeholder="e.g. 120/80" required class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:ring-2 focus:ring-green-300">
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600 block mb-1">Notes / Symptoms</label>
                    <textarea id="healthNotes" rows="2" placeholder="Any symptoms, medications, or observations..." class="w-full border border-slate-200 p-3 rounded-xl text-sm bg-slate-50 focus:ring-2 focus:ring-green-300"></textarea>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition">Save Health Record</button>
            </form>
        </div>
    </div>

    <!-- Job Matching Modal -->
    <div id="jobsModal" class="modal-bg" onclick="closeModalOutside(event, 'jobsModal')">
        <div class="modal-sheet">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-purple-700 flex items-center gap-2"><i class="fas fa-briefcase"></i> Job Openings</h3>
                <button onclick="closeModal('jobsModal')" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">&times;</button>
            </div>
            <div class="space-y-3 max-h-72 overflow-y-auto">
                <div class="border border-slate-200 rounded-xl p-3">
                    <p class="font-semibold text-slate-800 text-sm">Barangay Admin Assistant</p>
                    <p class="text-xs text-slate-500">₱13,500/mo · Full-time · San Agustin Barangay Hall</p>
                    <button onclick="applyJob('Barangay Admin Assistant')" class="mt-2 bg-purple-600 hover:bg-purple-700 text-white text-xs px-4 py-1.5 rounded-lg transition">Apply Now</button>
                </div>
                <div class="border border-slate-200 rounded-xl p-3">
                    <p class="font-semibold text-slate-800 text-sm">Community Health Worker</p>
                    <p class="text-xs text-slate-500">₱11,000/mo · Part-time · Barangay Health Center</p>
                    <button onclick="applyJob('Community Health Worker')" class="mt-2 bg-purple-600 hover:bg-purple-700 text-white text-xs px-4 py-1.5 rounded-lg transition">Apply Now</button>
                </div>
                <div class="border border-slate-200 rounded-xl p-3">
                    <p class="font-semibold text-slate-800 text-sm">Disaster Response Volunteer</p>
                    <p class="text-xs text-slate-500">Monthly Stipend · Free Training provided</p>
                    <button onclick="applyJob('Disaster Response Volunteer')" class="mt-2 bg-purple-600 hover:bg-purple-700 text-white text-xs px-4 py-1.5 rounded-lg transition">Apply Now</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scholarship Modal -->
    <div id="scholarshipModal" class="modal-bg" onclick="closeModalOutside(event, 'scholarshipModal')">
        <div class="modal-sheet">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-yellow-700 flex items-center gap-2"><i class="fas fa-graduation-cap"></i> Scholarship Assistance</h3>
                <button onclick="closeModal('scholarshipModal')" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">&times;</button>
            </div>
            <div class="bg-yellow-50 rounded-xl p-4 mb-4">
                <p class="text-sm font-semibold text-yellow-800">28 Slots Available</p>
                <p class="text-xs text-yellow-700 mt-1">Financial educational assistance up to ₱10,000 per semester for enrolled senior high & college residents.</p>
            </div>
            <button onclick="applyScholarship()" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-xl font-semibold transition">
                Apply for Scholarship
            </button>
        </div>
    </div>

    <!-- Emergency Modal -->
    <div id="emergencyModal" class="modal-bg" onclick="closeModalOutside(event, 'emergencyModal')">
        <div class="modal-sheet">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-red-700"><i class="fas fa-phone-alt"></i> Emergency Hotlines</h3>
                <button onclick="closeModal('emergencyModal')" class="w-8 h-8 bg-slate-100 rounded-full">&times;</button>
            </div>
            <a href="tel:911" class="flex justify-between bg-red-50 rounded-xl p-3 mb-2"><span>🚑 Medical Emergency</span><span class="font-bold">911</span></a>
            <a href="tel:117" class="flex justify-between bg-blue-50 rounded-xl p-3 mb-2"><span>👮 Police</span><span class="font-bold">117</span></a>
            <a href="tel:160" class="flex justify-between bg-orange-50 rounded-xl p-3 mb-2"><span>🔥 Fire Department</span><span class="font-bold">160</span></a>
            <a href="tel:09081234567" class="flex justify-between bg-slate-50 rounded-xl p-3"><span>🏢 Barangay Hall Desk</span><span class="font-bold">0908-123-4567</span></a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Posts state initialized from server
    let posts = @json($posts);
    let activeFeedTab = 'recent';
    let currentPostId = null;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function toggleUserDropdown() {
        document.getElementById('userDropdown').classList.toggle('hidden');
    }

    function openModal(id) {
        document.getElementById(id)?.classList.add('open');
    }
    function closeModal(id) {
        document.getElementById(id)?.classList.remove('open');
    }
    function closeModalOutside(e, id) {
        if (e.target.id === id) closeModal(id);
    }

    // Feed Tabs
    function switchFeedTab(tab, btn) {
        activeFeedTab = tab;
        document.querySelectorAll('.feed-tab-btn').forEach(b => {
            b.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
            b.classList.add('text-slate-500');
        });
        btn.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
        btn.classList.remove('text-slate-500');
        renderFeed();
    }

    // Render feed
    function renderFeed() {
        let filtered = [...posts];
        if (activeFeedTab === 'popular') {
            filtered.sort((a, b) => b.likes - a.likes);
        } else if (activeFeedTab === 'questions') {
            filtered = filtered.filter(p => p.type === 'question');
        }

        const container = document.getElementById('communityFeed');
        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="bg-white rounded-2xl p-8 text-center text-slate-400">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p class="text-sm">No posts yet in this tab. Be the first to post!</p>
                </div>
            `;
            return;
        }

        container.innerHTML = filtered.map(post => `
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 post-card" id="post-${post.id}">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 font-bold text-xs">${post.avatar || 'SA'}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-semibold text-slate-800 text-sm">${post.author}</p>
                            <span class="text-xs text-slate-400">${post.time}</span>
                            ${post.type === 'question' ? '<span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">Question</span>' : ''}
                            ${post.type === 'announcement' ? '<span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium">Announcement</span>' : ''}
                            ${post.type === 'feedback' ? '<span class="text-[10px] bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full font-medium">Feedback</span>' : ''}
                        </div>
                        <p class="text-slate-700 text-sm mt-2 whitespace-pre-line">${post.content}</p>
                    </div>
                </div>
                <div class="flex gap-6 pt-3 border-t border-slate-100">
                    <button onclick="toggleLike(${post.id})" class="like-btn flex items-center gap-1.5 ${post.liked ? 'liked text-red-500 font-semibold' : 'text-slate-500'} hover:text-red-500 transition text-xs">
                        <i class="fas fa-heart"></i>
                        <span>${post.likes}</span>
                    </button>
                    <button onclick="openCommentModal(${post.id})" class="flex items-center gap-1.5 text-slate-500 hover:text-blue-500 transition text-xs">
                        <i class="fas fa-comment"></i>
                        <span>${post.comments ? post.comments.length : 0}</span>
                    </button>
                    <button onclick="sharePost(${post.id})" class="flex items-center gap-1.5 text-slate-500 hover:text-green-500 transition text-xs">
                        <i class="fas fa-share"></i>
                        <span>Share</span>
                    </button>
                </div>
            </div>
        `).join('');
    }

    // Toggle like via AJAX
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

    // Create post via AJAX
    async function handleCreatePost(e) {
        e.preventDefault();
        const content = document.getElementById('postContent').value.trim();
        const type = document.getElementById('postTypeSelect').value;
        const visibility = document.getElementById('postVisibilitySelect').value;
        const btn = document.getElementById('createPostSubmitBtn');
        btn.disabled = true;
        btn.innerText = 'Publishing...';

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
                renderFeed();
                closeModal('createPostModal');
                document.getElementById('postContent').value = '';
                showToast('Post published successfully!');

                const countEl = document.getElementById('totalActivityCount');
                if (countEl) countEl.innerText = parseInt(countEl.innerText || 0) + 1;
            }
        } catch (e) {
            showToast('Failed to create post', 'error');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Post to Community';
        }
    }

    // Comments Modal
    function openCommentModal(postId) {
        currentPostId = postId;
        const post = posts.find(p => p.id === postId);
        if (!post) return;

        const list = document.getElementById('commentList');
        if (!post.comments || post.comments.length === 0) {
            list.innerHTML = '<p class="text-xs text-slate-400 text-center py-4">No comments yet. Write the first comment!</p>';
        } else {
            list.innerHTML = post.comments.map(c => `
                <div class="bg-slate-50 p-2.5 rounded-xl">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-xs text-slate-800">${c.author}</span>
                        <span class="text-[10px] text-slate-400">${c.time}</span>
                    </div>
                    <p class="text-xs text-slate-600">${c.text}</p>
                </div>
            `).join('');
        }
        openModal('commentModal');
    }

    // Add Comment via AJAX
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

    // Relief request submit
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
                showToast(data.message);
                closeModal('reliefModal');
                document.getElementById('reliefDetails').value = '';
            }
        } catch (e) {
            showToast('Failed to submit relief request', 'error');
        }
    }

    // Health submit
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
                showToast(data.message);
                closeModal('healthModal');
                document.getElementById('healthBP').value = '';
                document.getElementById('healthNotes').value = '';
            }
        } catch (e) {
            showToast('Failed to save health record', 'error');
        }
    }

    // Job apply
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
                showToast(data.message);
                closeModal('jobsModal');
            }
        } catch (e) {
            showToast('Failed to apply for job', 'error');
        }
    }

    // Scholarship apply
    async function applyScholarship() {
        try {
            const res = await fetch("{{ route('services.scholarship') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ notes: 'Application through resident portal' })
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message);
                closeModal('scholarshipModal');
            }
        } catch (e) {
            showToast('Failed to apply for scholarship', 'error');
        }
    }

    function sharePost(id) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(window.location.origin + '/dashboard#post-' + id);
            showToast('Link copied to clipboard!');
        } else {
            showToast('Post reference: #' + id);
        }
    }

    // Initial render
    renderFeed();
</script>
@endpush
