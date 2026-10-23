document.addEventListener('DOMContentLoaded', function () {

    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    const loginLinks = document.querySelectorAll('a[href="#"]');
    const loginModal = document.getElementById('loginModal');
    const closeLoginModalBtn = document.getElementById('closeLoginModal');

    // ✅ Hamburger menu toggle
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function () {
            hamburger.classList.toggle('open');
            mobileMenu.classList.toggle('open');
        });

        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function () {
                if (this.getAttribute('href') !== '#') {
                    hamburger.classList.remove('open');
                    mobileMenu.classList.remove('open');
                }
            });
        });
    }

    // ✅ Login modal logic
    if (loginModal && closeLoginModalBtn) {

        loginLinks.forEach(link => {
            if (link.textContent.trim().toLowerCase().includes('login')) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    loginModal.classList.add('active');
                });
            }
        });

        closeLoginModalBtn.addEventListener('click', function () {
            loginModal.classList.remove('active');
        });

        loginModal.addEventListener('click', function (e) {
            if (e.target === loginModal) {
                loginModal.classList.remove('active');
            }
        });
    }

    // ✅ Smooth scroll
    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetElement = document.querySelector(this.getAttribute('href'));

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ✅ AI Assistant - Setup enter key listener
    const userInput = document.getElementById('userInput');
    if (userInput) {
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }

}); // ✅ END of DOMContentLoaded


// ================= MODAL FUNCTIONS =================

function openEmergencyModal() {
    const el = document.getElementById('emergencyModal');
    if (el) el.classList.add('active');
}

function closeEmergencyModal() {
    const el = document.getElementById('emergencyModal');
    if (el) el.classList.remove('active');
}

function openLoginModal() {
    const el = document.getElementById('loginModal');
    if (el) el.classList.add('active');
}

function closeLoginModal() {
    const el = document.getElementById('loginModal');
    if (el) el.classList.remove('active');
}

function openRegisterModal() {
    const el = document.getElementById('registerModal');
    if (el) el.classList.add('active');
}

function closeRegisterModal() {
    const el = document.getElementById('registerModal');
    if (el) el.classList.remove('active');
}

function switchToRegister() {
    closeLoginModal();
    openRegisterModal();
}

function switchToLogin() {
    closeRegisterModal();
    openLoginModal();
}


// ================= PASSWORD STRENGTH =================

function checkPasswordStrength() {
    const password = document.getElementById('regPassword')?.value || '';
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    if (!strengthBar || !strengthText) return;

    let strength = 0;

    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    // Reset classes
    strengthBar.classList.remove('weak', 'medium', 'strong');
    
    if (password.length === 0) {
        strengthBar.style.width = '0%';
        strengthText.textContent = 'Use at least 8 characters with letters & numbers';
    } else if (strength < 2) {
        strengthBar.classList.add('weak');
        strengthBar.style.width = '33%';
        strengthText.textContent = 'Weak password - add more complexity';
    } else if (strength < 4) {
        strengthBar.classList.add('medium');
        strengthBar.style.width = '66%';
        strengthText.textContent = 'Medium password - getting better';
    } else {
        strengthBar.classList.add('strong');
        strengthBar.style.width = '100%';
        strengthText.textContent = 'Strong password - excellent!';
    }
}


// ================= FORM HANDLERS =================

function handleLogin(event) {
    event.preventDefault();

    const btn = event.target.querySelector('button[type="submit"]');
    if (!btn) return;

    const originalText = btn.innerHTML;

    btn.innerHTML = 'Logging in...';
    btn.disabled = true;

    setTimeout(() => {
        alert('✅ Login successful!');
        closeLoginModal();

        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 1500);
}

function handleRegister(event) {
    event.preventDefault();

    const password = document.getElementById('regPassword')?.value;
    const confirm = document.getElementById('confirmPassword')?.value;

    if (password !== confirm) {
        alert('❌ Passwords do not match!');
        return;
    }

    const btn = event.target.querySelector('button[type="submit"]');
    if (!btn) return;

    const originalText = btn.innerHTML;

    btn.innerHTML = 'Creating account...';
    btn.disabled = true;

    setTimeout(() => {
        alert('✅ Account created!');
        closeRegisterModal();
        openLoginModal();

        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 1500);
}

// ================= AI ASSISTANT FUNCTIONS =================
function openAIAssistant() {
    const el = document.getElementById('aiAssistant');
    if (el) {
        el.style.display = 'flex';
    }
}

function closeAIAssistant() {
    const el = document.getElementById('aiAssistant');
    if (el) {
        el.style.display = 'none';
        console.log("AI Assistant closed");
    }
}

function toggleAIAssistant() {
    const el = document.getElementById('aiAssistant');
    if (el) {
        if (el.style.display === 'none' || el.style.display === '') {
            openAIAssistant();
        } else {
            closeAIAssistant();
        }
    }
}

function sendMessage() {
    const input = document.getElementById('userInput');
    const chatBox = document.getElementById('chatBox');

    if (!input || !chatBox) return;

    const message = input.value.trim();
    if (message === '') return;

    addMessage(message, 'user');
    input.value = '';

    setTimeout(() => {
        const reply = getBotReply(message);
        addMessage(reply, 'bot');
    }, 500);
}

function addMessage(text, sender) {
    const chatBox = document.getElementById('chatBox');
    if (!chatBox) return;

    const msg = document.createElement('div');
    
    if (sender === 'user') {
        msg.className = 'bg-blue-600 text-white p-2 rounded-lg ml-auto w-fit max-w-[75%] mb-2';
    } else {
        msg.className = 'bg-gray-200 text-gray-800 p-2 rounded-lg w-fit max-w-[75%] mb-2';
    }

    msg.textContent = text;
    chatBox.appendChild(msg);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function getBotReply(message) {
    message = message.toLowerCase();

    if (message.includes('clearance')) {
        return 'To get a barangay clearance, visit the office and bring a valid ID.';
    }
    if (message.includes('hello') || message.includes('hi')) {
        return 'Hello! How can I assist you today?';
    }
    if (message.includes('help')) {
        return 'I can help you with: Clearance requests, Emergency hotlines, Barangay services, and General information.';
    }
    if (message.includes('emergency')) {
        return 'For emergencies, call: Police: 117, Fire: 160, Ambulance: 911';
    }
    
    return "Sorry, I'm still learning. Please ask about barangay clearance, services, or emergency hotlines.";
}

// ================= GLOBAL CLICK =================
window.addEventListener('click', function (event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
    }
});