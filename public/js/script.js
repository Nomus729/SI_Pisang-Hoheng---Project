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
    const sections = document.querySelectorAll("section");


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
            if(keepModal === 'login') {
                const emailInput = document.querySelector('input[name="email"]');
                if(emailInput) emailInput.focus();
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
            form.addEventListener('submit', function() {
                const btn = form.querySelector('button[type="submit"]');
                if(btn) {
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
    // 4. STICKY HEADER & SCROLL SPY
    // =========================================
    function moveIndicator(element) {
        if (!element || !indicator) return;
        indicator.style.width = `${element.offsetWidth}px`;
        indicator.style.left = `${element.offsetLeft}px`;
    }

    const initialActive = document.querySelector(".nav-links a.active");
    if(initialActive) moveIndicator(initialActive);

    window.addEventListener("scroll", function() {
        // Matikan animasi jika di halaman cart/checkout
        if (document.body.classList.contains('no-animation')) return;

        let currentScroll = window.pageYOffset;

        // Sticky Header
        if (header) {
            if (currentScroll > 20) header.classList.add("sticky");
            else header.classList.remove("sticky");
        }

        // Scroll Spy (Active Menu)
        let currentSectionId = "";
        if(sections.length > 0) {
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (currentScroll >= (sectionTop - 250)) {
                    currentSectionId = section.getAttribute("id");
                }
            });
        }

        navLinks.forEach(link => {
            link.classList.remove("active");
            if (currentSectionId && link.getAttribute("href").includes(currentSectionId)) {
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
            if(modal) modal.style.display = "flex";
        });
    }
    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            if(modal) modal.style.display = "none";
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
    // 8. ADD TO CART (DARI MENU UTAMA)
    // =========================================
    const menuGrid = document.querySelector('.menu-grid');
    
    // Klik Tombol + / - di Menu Utama (Hanya Visual)
    if (menuGrid) {
        menuGrid.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-plus')) {
                const qtySpan = e.target.previousElementSibling;
                let val = parseInt(qtySpan.innerText);
                qtySpan.innerText = val + 1;
            }
            if (e.target.classList.contains('btn-minus')) {
                const qtySpan = e.target.nextElementSibling;
                let val = parseInt(qtySpan.innerText);
                if (val > 1) qtySpan.innerText = val - 1;
            }

            // Klik Tombol Keranjang (Kirim ke Database)
            const cartBtn = e.target.closest('.add-to-cart-btn');
            if (cartBtn) {
                const name = cartBtn.getAttribute('data-name');
                const price = cartBtn.getAttribute('data-price');
                const image = cartBtn.getAttribute('data-image');
                const qtyVal = cartBtn.parentElement.querySelector('.qty-val').innerText;

                fetch('index.php?action=add_to_cart', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name: name, price: price, qty: qtyVal, image: image })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Masuk keranjang.',
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
                            if(data.message.includes('login') && modal) modal.style.display = "flex";
                        });
                    }
                });
            }
        });
    }


    // =========================================
    // 9. HALAMAN KERANJANG (UPDATE & DELETE)
    // =========================================
    const cartContainer = document.querySelector('.cart-items-container');

    // Format Rupiah
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    };

    // Hitung Ulang Total
    function recalculateCart() {
        let total = 0;
        let totalQty = 0;
        const items = document.querySelectorAll('.cart-item-card');
        
        items.forEach(item => {
            const price = parseInt(item.getAttribute('data-price'));
            const qty = parseInt(item.querySelector('.cart-qty-val').innerText);
            total += price * qty;
            totalQty += qty;
        });

        const subtotalEl = document.getElementById('cart-subtotal');
        const totalQtyEl = document.getElementById('cart-total-qty');
        
        if(subtotalEl) subtotalEl.innerText = formatRupiah(total).replace("Rp", "Rp ");
        if(totalQtyEl) totalQtyEl.innerText = totalQty + " Pcs";

        if (items.length === 0) setTimeout(() => location.reload(), 500);
    }

    if (cartContainer) {
        cartContainer.addEventListener('click', function(e) {
            const target = e.target;
            const card = target.closest('.cart-item-card');
            if (!card) return;

            const cartId = card.getAttribute('data-id');
            const qtySpan = card.querySelector('.cart-qty-val');

            // A. Tombol Tambah/Kurang di Cart
            if (target.classList.contains('cart-qty-btn')) {
                let currentQty = parseInt(qtySpan.innerText);
                const action = target.getAttribute('data-action');
                let newQty = currentQty;

                if (action === 'increase') newQty++;
                else if (action === 'decrease' && newQty > 1) newQty--;

                if (newQty !== currentQty) {
                    qtySpan.innerText = newQty; // Update UI langsung
                    recalculateCart(); // Update Total Harga

                    // Kirim ke Database
                    fetch('index.php?action=update_cart', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ cart_id: cartId, qty: newQty })
                    });
                }
            }

            // B. Tombol Hapus (Trash)
            // B. Tombol Hapus (Trash) - DIPERBAIKI
if (target.closest('.cart-remove-btn') || target.classList.contains('cart-remove-btn')) {
    const card = target.closest('.cart-item-card');
    if (!card) return;

    const cartId = card.getAttribute('data-id');

    Swal.fire({
        title: 'Hapus item?',
        text: "Item akan dihapus permanen dari keranjang.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('index.php?action=remove_item', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_id: cartId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    card.style.transition = "all 0.4s ease";
                    card.style.opacity = "0";
                    card.style.transform = "translateX(50px)";
                    setTimeout(() => {
                        card.remove();
                        recalculateCart();
                        Swal.fire({
                            title: 'Terhapus!',
                            text: 'Item telah dihapus dari keranjang.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }, 400);
                } else {
                    Swal.fire('Gagal', 'Tidak dapat menghapus item.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
            });
        }
    });
}
        });
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
    // 11. INISIALISASI SWIPER JS (CAROUSEL)
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

});