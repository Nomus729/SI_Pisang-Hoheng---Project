document.addEventListener("DOMContentLoaded", function () {
    
    // --- DEFINISI VARIABEL DOM (Global dalam fungsi ini) ---
    // Kita definisikan di atas agar bisa dipakai oleh SweetAlert maupun Logic Modal
    const modal = document.getElementById("authModal");
    const openBtn = document.getElementById("openLoginBtn");
    const closeBtn = document.querySelector(".close-modal");
    
    const loginFormBox = document.getElementById("loginForm");     // Div Pembungkus Login
    const registerFormBox = document.getElementById("registerForm"); // Div Pembungkus Register
    const formLoginElement = document.getElementById("formLogin"); // Tag <form> asli Login
    const formRegisterElement = document.getElementById("formRegister"); // Tag <form> asli Register
    
    const showRegister = document.getElementById("showRegister");
    const showLogin = document.getElementById("showLogin");


    // =========================================
    // 1. SWEETALERT2 & AUTO OPEN MODAL (LOGIKA BARU)
    // =========================================
    const flashData = document.getElementById('flash-data');
    
    if (flashData) {
        const icon = flashData.getAttribute('data-icon');
        const title = flashData.getAttribute('data-title');
        const text = flashData.getAttribute('data-text');
        const keepModal = flashData.getAttribute('data-modal'); // Ambil sinyal modal

        // Tampilkan SweetAlert
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonColor: '#89CFF0',
            confirmButtonText: 'Oke'
        }).then(() => {
            // Opsional: Fokus ke email jika gagal login
            if(keepModal === 'login') {
                const emailInput = document.querySelector('input[name="email"]');
                if(emailInput) emailInput.focus();
            }
        });

        // Cek apakah modal harus tetap terbuka (Login Gagal)
        if (keepModal === 'login') {
            // Buka Modal Paksa
            modal.style.display = "flex";
            
            // Pastikan yang tampil Form Login
            registerFormBox.classList.add("hidden");
            loginFormBox.classList.remove("hidden");

            // Kosongkan inputan agar user mengetik ulang
            if(formLoginElement) formLoginElement.reset();
        }
    }


    // =========================================
    // 2. LOADING STATE PADA TOMBOL FORM
    // =========================================
    function handleFormSubmit(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function() {
                const btn = form.querySelector('button[type="submit"]');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                btn.style.opacity = '0.7';
                btn.style.cursor = 'not-allowed';
            });
        }
    }
    handleFormSubmit('formLogin');
    handleFormSubmit('formRegister');


    // =========================================
    // 3. STICKY HEADER & MAGIC LINE NAVIGASI
    // =========================================
    const header = document.getElementById("mainHeader");
    const sections = document.querySelectorAll("section");
    const navLinks = document.querySelectorAll(".nav-links a");
    const indicator = document.querySelector(".nav-indicator"); 
    
    function moveIndicator(element) {
        if (!element) return;
        const width = element.offsetWidth;
        const left = element.offsetLeft;
        if (indicator) {
            indicator.style.width = `${width}px`;
            indicator.style.left = `${left}px`;
        }
    }

    const initialActive = document.querySelector(".nav-links a.active");
    if(initialActive) moveIndicator(initialActive);

    window.addEventListener("scroll", function() {
        let currentScroll = window.pageYOffset;

        if (currentScroll > 20) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }

        let currentSectionId = "";
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (currentScroll >= (sectionTop - 250)) {
                currentSectionId = section.getAttribute("id");
            }
        });

        navLinks.forEach(link => {
            link.classList.remove("active");
            if (link.getAttribute("href").includes(currentSectionId)) {
                link.classList.add("active");
                moveIndicator(link); 
            }
        });
    });

    navLinks.forEach(link => {
        link.addEventListener("click", function() {
            moveIndicator(this);
        });
    });


    // =========================================
    // 4. ANIMASI FADE IN ELEMENT (SCROLL)
    // =========================================
    const observerOptions = { root: null, threshold: 0.15 };
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            } else {
                entry.target.classList.remove('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal-on-scroll').forEach(el => {
        scrollObserver.observe(el);
    });


    // =========================================
    // 5. EVENT LISTENER MODAL (BUKA/TUTUP)
    // =========================================
    // (Variabel sudah didefinisikan di paling atas agar tidak bentrok)

    if (openBtn) {
        openBtn.addEventListener("click", () => {
            modal.style.display = "flex";
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });
    }

    window.addEventListener("click", (e) => {
        if (e.target == modal) {
            modal.style.display = "none";
        }
    });

    // Switch Login <-> Register
    if (showRegister) {
        showRegister.addEventListener("click", (e) => {
            e.preventDefault();
            loginFormBox.classList.add("hidden");
            registerFormBox.classList.remove("hidden");
        });
    }

    if (showLogin) {
        showLogin.addEventListener("click", (e) => {
            e.preventDefault();
            registerFormBox.classList.add("hidden");
            loginFormBox.classList.remove("hidden");
        });
    }


    // =========================================
    // 6. FITUR MATA PASSWORD (SHOW/HIDE)
    // =========================================
    const togglePasswords = document.querySelectorAll('.toggle-password');

    togglePasswords.forEach(icon => {
        icon.addEventListener('click', function () {
            const input = this.previousElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash'); 
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye'); 
            }
        });
    });


    // =========================================
    // 7. VALIDASI EMAIL REALTIME
    // =========================================
    const emailInputs = document.querySelectorAll('.email-input');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    emailInputs.forEach(input => {
        input.addEventListener('input', function () {
            const val = this.value;
            if (val === "") {
                this.classList.remove('input-error');
                return;
            }
            if (!emailRegex.test(val)) {
                this.classList.add('input-error'); 
            } else {
                this.classList.remove('input-error'); 
            }
        });

        input.addEventListener('blur', function () {
            if (this.value !== "" && !emailRegex.test(this.value)) {
                this.classList.add('input-error');
            }
        });
    });

});