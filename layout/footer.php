</div> <!-- END CONTENT -->

<!-- FOOTER -->
<footer class="bg-white border-t text-center p-4 text-sm text-gray-600">
    © <?= date('Y'); ?> Sistem Pelanggaran Siswa.
    All rights reserved.
</footer>

<script>
    function toggleDropdown(e) {
        e.stopPropagation();
        const menu = document.getElementById("dropdownMenu");

        if (menu.classList.contains("pointer-events-none")) {
            menu.classList.remove("opacity-0", "scale-95", "pointer-events-none");
            menu.classList.add("opacity-100", "scale-100");
        } else {
            closeDropdown();
        }
    }

    function closeDropdown() {
        const menu = document.getElementById("dropdownMenu");
        if (menu) {
            menu.classList.add("opacity-0", "scale-95", "pointer-events-none");
            menu.classList.remove("opacity-100", "scale-100");
        }
    }

    document.addEventListener("click", function() {
        closeDropdown();
    });
</script>

</body>

</html>