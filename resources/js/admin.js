// Toggle sidebar on mobile
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

// Toggle submenu
function toggleSubmenu(id) {
    const submenu = document.getElementById(id);
    const isActive = submenu.classList.contains('active');
    
    // Close all submenus
    document.querySelectorAll('.submenu').forEach(s => {
        s.classList.remove('active');
    });
    
    // Toggle current submenu
    if (!isActive) {
        submenu.classList.add('active');
    }
}

// Toggle user dropdown menu
function toggleUserMenu() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

// Toggle notifications
function toggleNotifications() {
    // Implementasi notifikasi
    alert('Fitur notifikasi akan ditambahkan');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.querySelector('.menu-toggle');
    const userMenu = document.querySelector('.user-info');
    const userDropdown = document.getElementById('userDropdown');
    
    // Close sidebar on mobile
    if (window.innerWidth <= 768 && 
        sidebar && sidebar.classList.contains('active') && 
        !sidebar.contains(event.target) && 
        menuToggle && !menuToggle.contains(event.target)) {
        sidebar.classList.remove('active');
    }
    
    // Close user dropdown
    if (userDropdown && userMenu && 
        !userMenu.contains(event.target) && 
        !userDropdown.contains(event.target)) {
        userDropdown.style.display = 'none';
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Confirm delete actions
document.querySelectorAll('form[action*="destroy"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            e.preventDefault();
        }
    });
});
