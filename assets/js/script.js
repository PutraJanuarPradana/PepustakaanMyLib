/* =====================================================
   SIDEBAR
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    const body = document.body;

    const toggle =
        document.getElementById("sidebarToggle");

    const overlay =
        document.getElementById("sidebarOverlay");


    /* =========================
       BUKA / TUTUP SIDEBAR
    ========================= */

    if (toggle) {

        toggle.addEventListener(
            "click",
            function () {

                body.classList.toggle(
                    "sidebar-open"
                );

            }
        );

    }


    /* =========================
       KLIK OVERLAY
    ========================= */

    if (overlay) {

        overlay.addEventListener(
            "click",
            function () {

                body.classList.remove(
                    "sidebar-open"
                );

            }
        );

    }


    /* =========================
       ESC UNTUK TUTUP
    ========================= */

    document.addEventListener(
        "keydown",
        function (event) {

            if (event.key === "Escape") {

                body.classList.remove(
                    "sidebar-open"
                );

            }

        }
    );


    /* =========================
       DARK MODE
    ========================= */

    const themeButton =
        document.getElementById(
            "themeToggle"
        );


    const savedTheme =
        localStorage.getItem(
            "perpustakaan-theme"
        );


    /*
       DEFAULT = LIGHT
    */

    if (savedTheme === "dark") {

        body.classList.add(
            "dark-mode"
        );

    } else {

        body.classList.remove(
            "dark-mode"
        );

    }


    /* =========================
       TOMBOL THEME
    ========================= */

    function updateThemeIcon() {

        if (!themeButton) {
            return;
        }


        if (
            body.classList.contains(
                "dark-mode"
            )
        ) {

            themeButton.innerHTML = "☀️";

            themeButton.setAttribute(
                "title",
                "Gunakan Light Mode"
            );

        } else {

            themeButton.innerHTML = "🌙";

            themeButton.setAttribute(
                "title",
                "Gunakan Dark Mode"
            );

        }

    }


    updateThemeIcon();


    if (themeButton) {

        themeButton.addEventListener(
            "click",
            function () {

                body.classList.toggle(
                    "dark-mode"
                );


                if (
                    body.classList.contains(
                        "dark-mode"
                    )
                ) {

                    localStorage.setItem(
                        "perpustakaan-theme",
                        "dark"
                    );

                } else {

                    localStorage.setItem(
                        "perpustakaan-theme",
                        "light"
                    );

                }


                updateThemeIcon();

            }
        );

    }


    /* =========================
       KONFIRMASI HAPUS
    ========================= */

    window.confirmDelete =
        function () {

            return confirm(
                "Apakah Anda yakin ingin menghapus data ini?"
            );

        };


});