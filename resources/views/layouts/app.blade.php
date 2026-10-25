<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', config('app.name', 'Barangay San Agustin - Community Hub'))</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('output.css') }}">

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Navbar Guaranteed High-Contrast Styles */
        nav.navbar {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
            color: #ffffff !important;
        }
        nav.navbar h1,
        nav.navbar p {
            color: #ffffff !important;
        }
        nav.navbar a.nav-link,
        nav.navbar a.nav-link i,
        nav.navbar #hamburger i {
            color: #ffffff !important;
        }

        /* Register Button with Blue Icon and Text */
        .btn-register,
        nav.navbar .btn-register {
            background-color: #ffffff !important;
            color: #1d4ed8 !important;
        }
        .btn-register i,
        .btn-register span,
        nav.navbar .btn-register i,
        nav.navbar .btn-register span {
            color: #1d4ed8 !important;
        }
        .btn-register:hover,
        nav.navbar .btn-register:hover {
            background-color: #eff6ff !important;
            color: #1e40af !important;
        }
        .btn-register:hover i,
        .btn-register:hover span,
        nav.navbar .btn-register:hover i,
        nav.navbar .btn-register:hover span {
            color: #1e40af !important;
        }
        nav.navbar a.nav-link {
            color: #ffffff !important;
            opacity: 0.95;
            transition: all 0.2s ease;
        }
        nav.navbar a.nav-link:hover {
            color: #ffffff !important;
            opacity: 1;
            background-color: rgba(255, 255, 255, 0.15) !important;
        }

        /* Hamburger & Mobile Menu Rules */
        #hamburger {
            display: flex !important;
            align-items: center;
            justify-content: center;
            cursor: pointer !important;
        }
        @media (min-width: 768px) {
            #hamburger {
                display: none !important;
            }
        }
        #mobileMenu.hidden {
            display: none !important;
        }
        #mobileMenu:not(.hidden) {
            display: block !important;
            max-height: none !important;
            overflow: visible !important;
        }

        /* Modal Overlay Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(0,0,0,0.7);
            backdrop-filter: blur(5px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .modal-overlay.active {
            display: flex;
        }

        .modal-bg {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(4px);
            z-index: 100;
            align-items: flex-end;
            justify-content: center;
        }

        .modal-bg.open {
            display: flex;
        }

        .modal-sheet {
            background: white;
            border-radius: 1.5rem 1.5rem 0 0;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 1.5rem;
            animation: slideUp 0.25s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(60px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Modal Headers - Guaranteed Gradient & White Text */
        .modal-header-blue {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
            color: #ffffff !important;
        }
        .modal-header-blue h3,
        .modal-header-blue p,
        .modal-header-blue span {
            color: #ffffff !important;
        }
        .modal-header-red {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            color: #ffffff !important;
        }
        .modal-header-red h3,
        .modal-header-red p,
        .modal-header-red span {
            color: #ffffff !important;
        }

        /* Modal Close Button */
        .modal-close-btn {
            color: #ffffff !important;
            opacity: 0.85;
            font-size: 2rem !important;
            line-height: 1 !important;
            background: transparent !important;
            border: none !important;
            cursor: pointer !important;
            padding: 0 0.5rem;
            transition: opacity 0.2s ease, transform 0.2s ease !important;
        }
        .modal-close-btn:hover {
            opacity: 1 !important;
            transform: scale(1.15);
        }

        /* Buttons: Guaranteed Visible Backgrounds & Text */
        .btn-submit-blue {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            background-color: #2563eb !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.38) !important;
            transition: all 0.2s ease !important;
        }
        .btn-submit-blue:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%) !important;
            background-color: #1d4ed8 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 20px 0 rgba(37, 99, 235, 0.48) !important;
            transform: translateY(-1px) !important;
        }
        .btn-submit-green {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important;
            background-color: #16a34a !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 14px 0 rgba(22, 163, 74, 0.38) !important;
            transition: all 0.2s ease !important;
        }
        .btn-submit-green:hover {
            background: linear-gradient(135deg, #15803d 0%, #166534 100%) !important;
            background-color: #15803d !important;
            color: #ffffff !important;
            box-shadow: 0 6px 20px 0 rgba(22, 163, 74, 0.48) !important;
            transform: translateY(-1px) !important;
        }
        .btn-submit-red {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            background-color: #dc2626 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 14px 0 rgba(220, 38, 38, 0.38) !important;
            transition: all 0.2s ease !important;
        }

        /* Fallbacks for Tailwind gradient classes to prevent collision transparent backgrounds */
        .bg-gradient-to-r.from-blue-600.to-blue-800 {
            background: linear-gradient(to right, #2563eb, #1e40af) !important;
            color: #ffffff !important;
        }
        .bg-gradient-to-r.from-green-600.to-green-700 {
            background: linear-gradient(to right, #16a34a, #15803d) !important;
            color: #ffffff !important;
        }
        .bg-gradient-to-r.from-red-600.to-red-700 {
            background: linear-gradient(to right, #dc2626, #b91c1c) !important;
            color: #ffffff !important;
        }
        .welcome-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%) !important;
        }

        /* Toast notifications */
        #toastContainer {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            pointer-events: none;
        }

        .toast {
            pointer-events: auto;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('body-class', 'bg-gray-50 text-gray-800')">
    <div id="toastContainer"></div>

    @yield('content')

    @stack('scripts')
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            const toast = document.createElement('div');
            const bgClass = type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-blue-600');
            toast.className = `toast text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3 text-sm font-medium ${bgClass}`;
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle')}"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-2 hover:opacity-75">&times;</button>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s ease';
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }
    </script>
</body>
</html>
