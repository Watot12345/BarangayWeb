document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    const loginLinks = document.querySelectorAll('a[href="#"]');
    const loginModal = document.getElementById('loginModal');
    const closeLoginModal = document.getElementById('closeLoginModal');

    // Hamburger menu toggle
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('open');
            mobileMenu.classList.toggle('open');
        });

        // Close mobile menu when a link is clicked
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (this.getAttribute('href') !== '#') {
                    hamburger.classList.remove('open');
                    mobileMenu.classList.remove('open');
                }
            });
        });
    }

    // Ensure elements exist before adding listeners
    if (loginModal && closeLoginModal) {
        // Open login modal when any login link is clicked
        loginLinks.forEach(link => {
            if (link.textContent.trim().toLowerCase().includes('login')) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    loginModal.classList.remove('hidden');
                });
            }
        });

        // Close modal when X is clicked
        closeLoginModal.addEventListener('click', function() {
            loginModal.classList.add('hidden');
        });

        // Close modal when clicking outside modal content
        loginModal.addEventListener('click', function(e) {
            if (e.target === loginModal) {
                loginModal.classList.add('hidden');
            }
        });
    }

    // Smooth scrolling for other anchor links (excluding href="#")
    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // adjust offset
                    behavior: 'smooth'
                });
            }
        });
    });
});
