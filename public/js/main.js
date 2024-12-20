    // Kích hoạt dropdown submenu
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
        dropdownSubmenus.forEach(function (submenu) {
            submenu.addEventListener('mouseenter', function () {
                const dropdown = submenu.querySelector('.dropdown-menu');
                if (dropdown) dropdown.style.display = 'block';
            });

            submenu.addEventListener('mouseleave', function () {
                const dropdown = submenu.querySelector('.dropdown-menu');
                if (dropdown) dropdown.style.display = 'none';
            });
        });
    });
    