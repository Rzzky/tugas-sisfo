// public/js/app.js (continued)
document.addEventListener('DOMContentLoaded', function() {
    // Handle mobile menu toggle if needed
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.querySelector('.navbar-menu');

    if (navbarToggle && navbarMenu) {
        navbarToggle.addEventListener('click', function() {
            navbarMenu.classList.toggle('is-active');
        });
    }

    // Notifications dropdown functionality
    const notificationBell = document.querySelector('.notifications');
    if (notificationBell) {
        notificationBell.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notification-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
                e.stopPropagation();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            const dropdown = document.getElementById('notification-dropdown');
            if (dropdown && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
    }

    // Search box functionality
    const searchBox = document.querySelector('.search-box input');
    if (searchBox) {
        searchBox.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        searchBox.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    }
});
