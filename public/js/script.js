document.addEventListener("DOMContentLoaded", function () {

    // =========================================
    // 1. VARIABEL GLOBAL & SETUP
    // =========================================
    const header = document.getElementById("mainHeader");
    const modal = document.getElementById("authModal");
    const openBtn = document.getElementById("openLoginBtn");
    const closeBtn = document.querySelector(".close-modal");

    const loginFormBox = document.getElementById("loginForm");
    const registerFormBox = document.getElementById("registerForm");
    const showRegister = document.getElementById("showRegister");
    const showLogin = document.getElementById("showLogin");

    const navLinks = document.querySelectorAll(".nav-links a");
    const indicator = document.querySelector(".nav-indicator");
    const sections = document.querySelectorAll("section, footer");


    // =========================================
    // 2. SWEETALERT NOTIFIKASI (PHP FLASH DATA)
    // =========================================
    const flashData = document.getElementById('flash-data');
    if (flashData) {
        const icon = flashData.getAttribute('data-icon');
        const title = flashData.getAttribute('data-title');
        const text = flashData.getAttribute('data-text');
        const keepModal = flashData.getAttribute('data-modal');

        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonColor: '#89CFF0',
            confirmButtonText: 'Oke'
        }).then(() => {
            if (keepModal === 'login') {
                const emailInput = document.querySelector('input[name="email"]');
                if (emailInput) emailInput.focus();
            }
        });

        // Buka Modal Otomatis jika Login Gagal
        if (keepModal === 'login' && modal) {
            modal.style.display = "flex";
            registerFormBox.classList.add("hidden");
            loginFormBox.classList.remove("hidden");
        }
    }


    // =========================================
    // 3. LOADING STATE PADA TOMBOL
    // =========================================
    function handleFormSubmit(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function () {
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                    btn.style.opacity = '0.7';
                    btn.style.cursor = 'not-allowed';
                }
            });
        }
    }
    handleFormSubmit('formLogin');
    handleFormSubmit('formRegister');


    // =========================================
    // 4. STICKY HEADER & SCROLL SPY (GSAP VERSION)
    // =========================================
    gsap.registerPlugin(ScrollTrigger);

    // Animasi Navbar Smooth dengan GSAP Scrub
    // Force Z-Index via GSAP to ensure it stays on top
    gsap.set(".main-header", { zIndex: 99999 });

    // GSAP ScrollTrigger dengan MatchMedia (Hanya jalan di Desktop)
    let mm = gsap.matchMedia();

    mm.add("(min-width: 901px)", () => {

        // 1. Toggle Class .sticky menggunakan Event Listener Standar (Desktop Only) - OPTIMIZED
        let ticking = false;
        window.addEventListener("scroll", function () {
            if (!ticking) {
                window.requestAnimationFrame(function () {
                    handleDesktopScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });

        function handleDesktopScroll() {
            const header = document.querySelector(".main-header");
            if (window.scrollY > 10) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }
        }

        // 2. Animasi Properti (Timeline)
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: "body",
                start: "top top",
                end: "+=150",
                scrub: 0.5,
            }
        });

        tl.to(".main-header", {
            height: "80px",
            backgroundColor: "rgba(255, 255, 255, 0.98)",
            boxShadow: "0 4px 15px rgba(0, 0, 0, 0.05)",
            padding: "10px 5%",
            duration: 1
        }, 0)
            .to(".logo img", {
                width: "35px",
                duration: 1
            }, 0)
            .to(".brand-name", {
                fontSize: "1.1rem",
                duration: 1
            }, 0)
            .to(".brand-sub", {
                fontSize: "0.65rem",
                duration: 1
            }, 0)
            .to(".nav-links", {
                paddingTop: "0px",
                duration: 1
            }, 0)
            // Pastikan Search Bar mengecil agar muat dalam 1 baris
            .to(".search-bar", {
                maxWidth: "250px",
                margin: "0 15px",
                duration: 1
            }, 0)
            .to(".search-bar input", {
                paddingTop: "8px",
                paddingBottom: "8px",
                backgroundColor: "#f1f1f1",
                duration: 1
            }, 0);

        return () => { // Cleanup
            window.removeEventListener("scroll", handleDesktopScroll);
            const header = document.querySelector(".main-header");
            if (header) header.classList.remove("sticky"); // Reset
            // GSAP auto reverts style properties
        };
    });

    // =========================================
    // MOBILE MENU TOGGLE
    // =========================================
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinksContainer = document.querySelector('.nav-links');

    if (mobileMenuBtn && navLinksContainer) {
        mobileMenuBtn.addEventListener('click', () => {
            navLinksContainer.classList.toggle('active');

            // Icon transformation (Bars <-> Times)
            const icon = mobileMenuBtn.querySelector('i');
            if (navLinksContainer.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close menu when clicking link
        navLinksContainer.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinksContainer.classList.remove('active');
                const icon = mobileMenuBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });
    }

    // Scroll Spy (Active Menu) - Tetap pakai logic intersect atau manual tapi dioptimalkan
    function moveIndicator(element) {
        if (!element || !indicator) return;
        indicator.style.width = `${element.offsetWidth}px`;
        indicator.style.left = `${element.offsetLeft}px`;
    }

    const initialActive = document.querySelector(".nav-links a.active");
    if (initialActive) moveIndicator(initialActive);

    // Gunakan ScrollTrigger untuk update active state
    if (sections.length > 0) {
        sections.forEach(section => {
            ScrollTrigger.create({
                trigger: section,
                start: "top center",
                end: "bottom center",
                onEnter: () => updateActive(section.id),
                onEnterBack: () => updateActive(section.id)
            });
        });
    }

    function updateActive(id) {
        navLinks.forEach(link => {
            link.classList.remove("active");
            if (link.getAttribute("href").includes(id)) {
                link.classList.add("active");
                moveIndicator(link);
            }
        });
    }

    navLinks.forEach(link => {
        link.addEventListener("click", function () {
            moveIndicator(this);
        });
    });


    // =========================================
    // 5. ANIMASI FADE IN (REVEAL ON SCROLL)
    // =========================================
    const observerOptions = { root: null, threshold: 0.15 };
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal-on-scroll').forEach(el => {
        scrollObserver.observe(el);
    });


    // =========================================
    // 6. MODAL LOGIN LOGIC
    // =========================================
    if (openBtn) {
        openBtn.addEventListener("click", (e) => {
            e.preventDefault();
            if (modal) modal.style.display = "flex";
        });
    }
    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            if (modal) modal.style.display = "none";
        });
    }
    window.addEventListener("click", (e) => {
        if (e.target == modal) modal.style.display = "none";
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
    // 7. FITUR MATA PASSWORD & VALIDASI EMAIL
    // =========================================
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function () {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    document.querySelectorAll('.email-input').forEach(input => {
        input.addEventListener('input', function () {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value === "") {
                this.classList.remove('input-error');
            } else if (!regex.test(this.value)) {
                this.classList.add('input-error');
            } else {
                this.classList.remove('input-error');
            }
        });
    });


    // =========================================
    // 8 & 9. GLOBAL HANDLER FOR CART & MENU ACTIONS (ROBUST FIX)
    // =========================================

    // Variable to prevent double-submission (Timestamp Debounce)
    let lastAddToCartTime = 0;

    document.body.addEventListener('click', function (e) {
        const target = e.target;

        // --- A. MENU: ADD TO CART (+/- handled locally in UI, but Add button here) ---
        const cartBtn = target.closest('.add-to-cart-btn');
        if (cartBtn) {
            e.preventDefault();
            e.stopPropagation();

            const now = Date.now();
            if (now - lastAddToCartTime < 1000) return; // Ignore clicks within 1 second
            lastAddToCartTime = now;

            // Visual feedback - Disable button
            if (cartBtn.disabled) return;
            cartBtn.disabled = true;

            const name = cartBtn.getAttribute('data-name');
            const price = cartBtn.getAttribute('data-price');
            const image = cartBtn.getAttribute('data-image');

            // Find Qty
            const cardActions = cartBtn.parentElement;
            const qtySpan = cardActions.querySelector('.qty-val');
            let qtyVal = 1;
            if (qtySpan) {
                qtyVal = parseInt(qtySpan.innerText) || 1;
            }

            fetch('index.php?action=add_to_cart', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name: name, price: price, qty: qtyVal, image: image })
            })
                .then(response => response.json())
                .then(data => {
                    cartBtn.disabled = false;
                    if (data.status === 'success') {
                        const badge = document.getElementById('cartBadge');
                        if (badge) {
                            let currentCount = parseInt(badge.innerText) || 0;
                            badge.innerText = currentCount + qtyVal;
                            badge.classList.remove('hidden');
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Berhasil masuk keranjang.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal',
                            text: data.message,
                            confirmButtonColor: '#89CFF0'
                        }).then(() => {
                            if (data.message && data.message.toLowerCase().includes('login') && typeof modal !== 'undefined') {
                                modal.style.display = "flex";
                                if (typeof registerFormBox !== 'undefined') registerFormBox.classList.add("hidden");
                                if (typeof loginFormBox !== 'undefined') loginFormBox.classList.remove("hidden");
                            }
                        });
                    }
                })
                .catch(err => {
                    cartBtn.disabled = false;
                    console.error(err);
                });
            return;
        }

        // --- B. MENU: BUTTON PLUS MINUS ---
        if (target.classList.contains('btn-plus')) {
            e.preventDefault();
            const qtySpan = target.previousElementSibling;
            if (qtySpan) {
                let val = parseInt(qtySpan.innerText) || 1;
                qtySpan.innerText = val + 1;
            }
            return;
        }

        if (target.classList.contains('btn-minus')) {
            e.preventDefault();
            const qtySpan = target.nextElementSibling;
            if (qtySpan) {
                let val = parseInt(qtySpan.innerText) || 1;
                if (val > 1) qtySpan.innerText = val - 1;
            }
            return;
        }

        // 2. Qty Update (+/-)
        if (target.classList.contains('cart-qty-btn')) {
            const card = target.closest('.cart-item-card');
            if (!card) return;

            const cartId = card.getAttribute('data-id');
            const qtySpan = card.querySelector('.cart-qty-val');
            const action = target.getAttribute('data-action');
            let currentQty = parseInt(qtySpan.innerText);
            let newQty = currentQty;

            if (action === 'increase') newQty++;
            else if (action === 'decrease' && newQty > 1) newQty--;

            if (newQty !== currentQty) {
                target.disabled = true;
                qtySpan.innerText = newQty;
                if (typeof recalculateCart === 'function') recalculateCart();

                fetch('index.php?action=update_cart', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cart_id: cartId, qty: newQty })
                }).then(() => {
                    target.disabled = false;
                });
            }
        }
    });

    // Helper functions for Cart
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    };

    function recalculateCart() {
        let total = 0;
        let totalQty = 0;
        const items = document.querySelectorAll('.cart-item-card');

        items.forEach(item => {
            const price = parseInt(item.getAttribute('data-price'));
            const qtyEl = item.querySelector('.cart-qty-val');
            const qty = qtyEl ? parseInt(qtyEl.innerText) : 0;
            total += price * qty;
            totalQty += qty;
        });

        const subtotalEl = document.getElementById('cart-subtotal');
        const totalQtyEl = document.getElementById('cart-total-qty');

        if (subtotalEl) subtotalEl.innerText = formatRupiah(total).replace("Rp", "Rp ");
        if (totalQtyEl) totalQtyEl.innerText = totalQty + " Pcs";

        if (items.length === 0) setTimeout(() => location.reload(), 500);
    }

    // =========================================
    // 10. CEK LOGIN SAAT KLIK TOMBOL CART HEADER
    // =========================================
    const navCartBtn = document.getElementById('cartBtn');
    if (navCartBtn) {
        navCartBtn.addEventListener('click', function (e) {
            const isLoggedIn = this.getAttribute('data-login') === 'true';
            if (!isLoggedIn) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Login',
                    text: 'Silakan login dulu untuk melihat keranjang.',
                    confirmButtonText: 'Login',
                    confirmButtonColor: '#89CFF0'
                }).then((result) => {
                    if (result.isConfirmed && modal) {
                        modal.style.display = "flex";
                        registerFormBox.classList.add("hidden");
                        loginFormBox.classList.remove("hidden");
                    }
                });
            }
        });
    }

    // ... (Kode sebelumnya tetap ada) ...

    // =========================================
    // 11. SEARCH BAR FUNCTIONALITY (CLIENT SIDE) - REMOVED (Now Server Side)
    // =========================================
    // Search is now handled via GET request in index.php


    // =========================================
    // 12. INISIALISASI SWIPER JS (CAROUSEL)
    // =========================================
    // Cek apakah ada elemen swiper di halaman


    if (document.querySelector('.product-swiper')) {
        const swiper = new Swiper('.product-swiper', {
            // Pengaturan Dasar
            slidesPerView: 1, // Tampil 1 produk di HP
            spaceBetween: 30, // Jarak antar produk
            loop: true,       // INFINITE LOOP (Tanpa Duplikat Data)

            // Animasi Bergerak Otomatis
            autoplay: {
                delay: 2500, // Bergerak setiap 2.5 detik
                disableOnInteraction: false, // Tetap jalan walau disentuh
            },

            // Titik-titik di bawah
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            // Responsif (Laptop/Tablet)
            breakpoints: {
                640: {
                    slidesPerView: 2, // Tablet: 2 Produk
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3, // Laptop: 3 Produk
                    spaceBetween: 40,
                },
            },
        });
    }

    // =========================================
    // 13. AJAX FILTERING & PAGINATION
    // =========================================
    const filterBtns = document.querySelectorAll('.filter-landing-btn');
    const menuContainer = document.getElementById('menu-container');

    if (filterBtns.length > 0 && menuContainer) {

        // A. Fungsi Load Menu via AJAX
        function loadMenu(url) {
            menuContainer.style.opacity = '0.5';

            // Pisahkan Hash (#) agar parameter tidak dianggap fragment
            let [baseUrl, hash] = url.split('#');

            let fetchUrl = baseUrl;
            if (!fetchUrl.includes('action_ajax')) {
                fetchUrl += (fetchUrl.includes('?') ? '&' : '?') + 'action_ajax=filter_menu';
            }

            fetch(fetchUrl)
                .then(res => res.json())
                .then(data => {
                    menuContainer.innerHTML = data.html;
                    menuContainer.style.opacity = '1';

                    let historyUrl = url.replace(/(&|\?)action_ajax=filter_menu/, '');
                    window.history.pushState({ path: url }, '', historyUrl);
                })
                .catch(err => {
                    console.error(err);
                    menuContainer.style.opacity = '1';
                });
        }

        // B. Listener Filter Buttons
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const kategori = this.getAttribute('data-kategori');
                const urlParams = new URLSearchParams(window.location.search);
                const search = urlParams.get('search') || '';

                let newUrl = `index.php?halaman=1`;
                if (kategori) newUrl += `&kategori=${kategori}`;
                if (search) newUrl += `&search=${search}`;
                newUrl += '#menu';

                loadMenu(newUrl);
            });
        });

        // C. Listener Pagination (Delegation)
        menuContainer.addEventListener('click', function (e) {
            const link = e.target.closest('.page-link');
            if (link) {
                e.preventDefault();
                loadMenu(link.getAttribute('href'));
            }
        });

        // D. Fix Add to Cart for AJAX Content (Delegation)
        // Redundant listener removed to prevent double submission.
        // The global document.body listener handles all clicks.
    }

});