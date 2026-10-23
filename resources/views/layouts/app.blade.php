<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Barangay San Agustin - Community Hub'))</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('output.css') }}">

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

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
