<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        // Record load start time as early as possible for preloader minimum duration
        window.preloaderStartTime = Date.now();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Optimization -->
    <title>Harita Music Academy Live Online Indian Classical & Instrumental Music Classes</title>
    <meta name="description"
        content="Harita Music Academy offers premium live online music classes specializing in Hindustani Classical Vocal, Bollywood Singing, Piano, Keyboard, Harmonium, and Tabla. Connect with academically qualified mentors.">
    <meta name="keywords"
        content="music academy, online music classes, Hindustani classical vocal, Bollywood singing, piano class, keyboard class, harmonium class, tabla class, live music lessons">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Harita Music Academy">
    <link rel="canonical" href="https://haritamusicacademy.com">

    <!-- Open Graph (Facebook / LinkedIn) SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Harita Music Academy   Live Online Indian Music Classes">
    <meta property="og:description"
        content="Structured curriculum, academically qualified faculty, and personal learning guidance. Learn Hindustani Vocal, Bollywood, Piano, Harmonium, Keyboard, and Tabla.">
    <meta property="og:image" content="{{ asset('landing/assets/') }}/images/logo-navbar.png">
    <meta property="og:url" content="https://haritamusicacademy.com">
    <meta property="og:site_name" content="Harita Music Academy">

    <!-- Twitter Card SEO -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Harita Music Academy   Live Online Music Classes">
    <meta name="twitter:description"
        content="Attend live online music lessons under qualified professionals. Book a demo session for ₹499.">
    <meta name="twitter:image" content="{{ asset('landing/assets/') }}/images/logo-navbar.png">

    <!-- Circular Favicon in browser title bar -->
    <link rel="icon" type="image/png" href="{{ asset('landing/favicon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons/css/flag-icons.min.css">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('landing/assets/') }}/css/style.css?v=1.1.0">

    <!-- JSON-LD Structured Data Schema for Local Business/Music School -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "MusicSchool",
      "name": "Harita Music Academy",
      "url": "https://haritamusicacademy.com",
      "logo": "https://haritamusicacademy.com/{{ asset('landing/assets/') }}/images/logo-navbar.png",
      "image": "https://haritamusicacademy.com/{{ asset('landing/assets/') }}/images/logo-navbar.png",
      "description": "Premium live online music academy specializing in Hindustani Classical Vocal, Bollywood Singing, Piano, Keyboard, Harmonium, and Tabla.",
      "founder": {
        "@@type": "Person",
        "name": "Harita Iyer"
      },
      "offers": {
        "@@type": "AggregateOffer",
        "priceCurrency": "INR",
        "lowPrice": "499",
        "highPrice": "24700"
      },
      "address": {
        "@@type": "PostalAddress",
        "addressCountry": "IN"
      },
      "sameAs": [
        "https://facebook.com",
        "https://instagram.com",
        "https://youtube.com"
      ]
    }
    </script>
</head>

<body>

    <!-- Premium Preloader with Logo -->
    <div class="preloader" id="preloader">
        <div class="preloader-content-wrap">
            <div class="preloader-spinner"></div>
            <div class="preloader-logo-wrap">
                <img src="{{ asset('landing/assets/') }}/images/logo-preloader.png?v=1.0.1" alt="Harita Music Academy Loading logo">
            </div>
        </div>
    </div>

    <!-- Header Navigation -->
    <header class="header" id="header">
        <div class="header-container">
            <a href="#home" class="logo-wrap" aria-label="Harita Music Academy Home">
                <!-- Circular Logo Container -->
                <div class="logo-img-container circle-logo-fav">
                    <img src="{{ asset('landing/assets/') }}/images/logo-navbar.png?v=1.0.1" alt="Harita Music Academy Logo">
                </div>
                <span class="logo-text">Harita Music Academy</span>
            </a>

            <nav class="nav-menu" aria-label="Main Navigation">
                <a href="#courses" class="nav-link">Our Courses</a>
                <a href="#journey" class="nav-link">Journey</a>
                <a href="#why-us" class="nav-link">Why Us</a>
                <a href="#pricing" class="nav-link">Pricing</a>
                <a href="#faq" class="nav-link">FAQ</a>
                <a href="#trial" class="btn btn-primary" style="padding: 0.6rem 1.5rem; font-size: 0.85rem;">Book
                    Demo</a>
            </nav>

            <button class="hamburger" id="menu-toggle" aria-label="Toggle Menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div class="overlay-menu" id="menu-overlay"></div>
    <nav class="mobile-nav-drawer" id="mobile-menu" aria-label="Mobile Navigation">
        <a href="#courses" class="mobile-nav-link">Our Courses</a>
        <a href="#journey" class="mobile-nav-link">Journey</a>
        <a href="#why-us" class="mobile-nav-link">Why Us</a>
        <a href="#pricing" class="mobile-nav-link">Pricing</a>
        <a href="#faq" class="mobile-nav-link">FAQ</a>
        <a href="#trial" class="btn btn-primary">Book Demo Class</a>
    </nav>

    <main>
        @yield('content')
    </main>
    <!-- Scrolling Text Marquee Ticker (Infinite Scroll Left-to-Right) -->
    <div class="text-marquee-container-1">
        <div class="text-marquee-wrapper">
            <div class="text-marquee-content">
                <span>HARITA MUSIC ACADEMY</span>
                <span class="outline-text">SING • PLAY • LEARN • PERFORM</span>
                <span>HARITA MUSIC ACADEMY</span>
                <span class="outline-text">SING • PLAY • LEARN • PERFORM</span>
                <span>HARITA MUSIC ACADEMY</span>
                <span class="outline-text">SING • PLAY • LEARN • PERFORM</span>
            </div>
            <div class="text-marquee-content" aria-hidden="true">
                <span>HARITA MUSIC ACADEMY</span>
                <span class="outline-text">SING • PLAY • LEARN • PERFORM</span>
                <span>HARITA MUSIC ACADEMY</span>
                <span class="outline-text">SING • PLAY • LEARN • PERFORM</span>
                <span>HARITA MUSIC ACADEMY</span>
                <span class="outline-text">SING • PLAY • LEARN • PERFORM</span>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-top">
                <div class="footer-col">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                        <!-- Circular Logo Container in Footer -->
                        <div class="logo-img-container"
                            style="border-color: rgba(255, 255, 255, 0.2); width: 120px; height: 120px;">
                            <img src="{{ asset('landing/assets/') }}/images/logo-navbar.png" alt="Harita Music Academy Footer Logo">
                        </div>
                    </div>
                    <h4 style="font-family: var(--font-serif); font-size: 1.35rem; color: #ffffff; margin-bottom: 0;">
                        Harita Music Academy</h4>
                    <p class="footer-info">
                        Providing premium online Indian classical vocal and instrumental music education under qualified
                        teachers worldwide.
                    </p>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#why-us" class="footer-link">Why Us</a></li>
                        <li><a href="#courses" class="footer-link">Our Courses</a></li>
                        <li><a href="#pricing" class="footer-link">Pricing Plans</a></li>
                        <li><a href="#journey" class="footer-link">Learning Journey</a></li>
                        <li><a href="privacy.html" class="footer-link">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact Us</h4>
                    <p class="footer-info">
                        <strong>Email:</strong> info@haritamusicacademy.com<br>
                        <strong>Phone:</strong> +918796823332<br>
                        <strong>Timing:</strong> Mon - Sun: 8:00 AM - 2:00 AM IST
                    </p>
                    <div class="footer-serving">
                        <h4>Currently Serving Students In</h4>
                        <div class="country-list">
                            <span><img src="https://flagcdn.com/w160/in.png" alt="India"></span>
                            <span><img src="https://flagcdn.com/w160/us.png" alt="USA"> </span>
                            <span><img src="https://flagcdn.com/w160/gb.png" alt="UK"></span>
                            <span><img src="https://flagcdn.com/w160/ca.png" alt="Canada"></span>
                            <span><img src="https://flagcdn.com/w160/ae.png" alt="UAE"></span>
                        </div>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Follow Us</h4>
                    <div class="footer-socials">
                        <a href="https://facebook.com" class="footer-social-btn" aria-label="Facebook">
                            <svg viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="https://instagram.com" class="footer-social-btn" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="https://youtube.com" class="footer-social-btn" aria-label="YouTube">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z">
                                </path>
                                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com"
                        target="_blank" rel="noopener" class="sitesoch-link">Sitesoch</a></p>

            </div>
        </div>
    </footer>

    <!-- Fixed Floating WhatsApp Button -->
    <a href="https://wa.me/+918796823332?text=Hi%2C%20I%20would%20like%20to%20enquire%20about%20music%20classes%20at%20Harita%20Music%20Academy."
        class="whatsapp-float" target="_blank" aria-label="Chat with Harita Music Academy on WhatsApp">
        <svg viewBox="0 0 24 24">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.458 5.706 1.458h.008c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
    </a>

    <!-- Video Modal Structure -->
    <div class="video-modal" id="video-modal" aria-hidden="true" role="dialog">
        <div class="video-modal-content">
            <button class="video-close-btn" id="video-modal-close" aria-label="Close video player">
                <svg viewBox="0 0 24 24" fill="none">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                Close
            </button>
            <video id="modal-video-player" controls preload="none">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <!-- JavaScript Actions -->
    <script>
        // Preloader Fader with a minimum display time of 1.5 seconds (1500ms)
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                const startTime = window.preloaderStartTime || Date.now();
                const elapsedTime = Date.now() - startTime;
                const delay = Math.max(0, 1500 - elapsedTime);

                setTimeout(() => {
                    preloader.classList.add('fade-out');
                }, delay);
            }
        });

        // Header scroll effect using classes instead of inline styles
        const header = document.getElementById('header');

        function handleScroll() {
            if (window.scrollY > 50) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        }

        window.addEventListener('scroll', handleScroll);
        // Run on load to handle scrolled refresh state
        handleScroll();

        // Mobile Menu Drawer Control
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuOverlay = document.getElementById('menu-overlay');
        const mobileLinks = document.querySelectorAll('.mobile-nav-link');

        function toggleMenu() {
            const isOpen = mobileMenu.classList.contains('open');
            mobileMenu.classList.toggle('open');
            menuOverlay.classList.toggle('active');
            menuToggle.setAttribute('aria-expanded', !isOpen);

            // Toggle active state on header to show solid background
            if (!isOpen) {
                header.classList.add('mobile-menu-active');
            } else {
                header.classList.remove('mobile-menu-active');
            }

            // Toggle hamburger animation
            const spans = menuToggle.querySelectorAll('span');
            if (!isOpen) {
                spans[0].style.transform = 'translateY(8px) rotate(45deg)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'translateY(-8px) rotate(-45deg)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        }

        menuToggle.addEventListener('click', toggleMenu);
        menuOverlay.addEventListener('click', toggleMenu);
        mobileLinks.forEach(link => link.addEventListener('click', toggleMenu));



        // Helper function for looping/wrapping slider scrolling
        function setupLoopingScroll(prevBtn, nextBtn, container, step = 360) {
            if (!prevBtn || !nextBtn || !container) return;

            nextBtn.addEventListener('click', () => {
                const maxScrollLeft = container.scrollWidth - container.clientWidth;
                // Wrap back to beginning if we are at the end
                if (container.scrollLeft >= maxScrollLeft - 15) {
                    container.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    container.scrollBy({ left: step, behavior: 'smooth' });
                }
            });

            prevBtn.addEventListener('click', () => {
                const maxScrollLeft = container.scrollWidth - container.clientWidth;
                // Wrap to the end if we are at the beginning
                if (container.scrollLeft <= 15) {
                    container.scrollTo({ left: maxScrollLeft, behavior: 'smooth' });
                } else {
                    container.scrollBy({ left: -step, behavior: 'smooth' });
                }
            });
        }

        // Initialize looping scroll for the horizontal card containers
        setupLoopingScroll(
            document.getElementById('why-scroll-prev'),
            document.getElementById('why-scroll-next'),
            document.getElementById('why-cards-container')
        );

        setupLoopingScroll(
            document.getElementById('course-scroll-prev'),
            document.getElementById('course-scroll-next'),
            document.getElementById('course-cards-container')
        );

        setupLoopingScroll(
            document.getElementById('journey-scroll-prev'),
            document.getElementById('journey-scroll-next'),
            document.getElementById('journey-cards-container')
        );

        setupLoopingScroll(
            document.getElementById('test-scroll-prev'),
            document.getElementById('test-scroll-next'),
            document.getElementById('test-cards-container')
        );

        // Video Modal Control (Hero & Testimonials)
        const videoModal = document.getElementById('video-modal');
        const videoPlayer = document.getElementById('modal-video-player');
        const videoModalClose = document.getElementById('video-modal-close');
        const videoTriggers = document.querySelectorAll('.video-play-trigger-hero, .testimonial-card-video-trigger, #hero-video-btn');

        videoTriggers.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const videoSrc = btn.getAttribute('data-video-src') || btn.getAttribute('href') || '{{ asset('landing/assets/') }}/videos/HMA-Video-1.mp4';
                if (videoPlayer) {
                    videoPlayer.src = videoSrc;
                    if (videoModal) {
                        videoModal.classList.add('active');
                        videoModal.setAttribute('aria-hidden', 'false');
                    }
                    videoPlayer.play().catch(err => console.log('Autoplay blocked:', err));
                }
            });
        });

        function closeVideo() {
            videoPlayer.pause();
            videoPlayer.src = '';
            videoModal.classList.remove('active');
            videoModal.setAttribute('aria-hidden', 'true');
        }

        videoModalClose.addEventListener('click', closeVideo);
        videoModal.addEventListener('click', (e) => {
            if (e.target === videoModal) {
                closeVideo();
            }
        });

        // Intersection Observer Scroll Reveal Animation
        const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.1 });

        revealElements.forEach(el => revealObserver.observe(el));

        // Scrollspy Navigation Highlights
        const sections = document.querySelectorAll('section, header, footer');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let currentSectionId = '';
            const scrollPos = window.scrollY + 200; // Offset for sticky header

            sections.forEach(sec => {
                const top = sec.offsetTop;
                const height = sec.offsetHeight;
                if (scrollPos >= top && scrollPos < top + height) {
                    currentSectionId = sec.getAttribute('id');
                }
            });

            if (currentSectionId) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${currentSectionId}`) {
                        link.classList.add('active');
                    }
                });
            }
        });

        // Interactive Calendar booking widget logic (Snapshot 3)
        const monthYearEl = document.getElementById('calendar-month-year');
        const daysGridEl = document.getElementById('calendar-days-grid');
        const prevMonthBtn = document.getElementById('prev-month');
        const nextMonthBtn = document.getElementById('next-month');
        const slotsContainer = document.getElementById('slots-container');
        const bookDemoBtn = document.getElementById('book-demo-btn');

        // Setup today's date with zeroed hours for comparisons
        const todayDate = new Date();
        todayDate.setHours(0, 0, 0, 0);

        // Minimum selectable date is the later of today OR July 23, 2026
        const absoluteMinDate = new Date(2026, 6, 23);
        const minDate = todayDate > absoluteMinDate ? todayDate : absoluteMinDate;

        // Default selectedDate is today's date (or absoluteMinDate if today is earlier)
        let selectedDate = new Date(minDate);
        let currentCalMonth = selectedDate.getMonth();
        let currentCalYear = selectedDate.getFullYear();
        let selectedSlotText = '10:00 AM';

        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        function renderCalendar() {
            if (!monthYearEl || !daysGridEl) return;
            monthYearEl.textContent = `${monthNames[currentCalMonth]} ${currentCalYear}`;
            daysGridEl.innerHTML = '';

            // Get first day of the month
            let firstDayIndex = new Date(currentCalYear, currentCalMonth, 1).getDay();
            // Convert to Mon-start index: Sun=6, Mon=0, Tue=1, etc.
            firstDayIndex = firstDayIndex === 0 ? 6 : firstDayIndex - 1;

            const totalDays = new Date(currentCalYear, currentCalMonth + 1, 0).getDate();

            // Render empty cells for leading weekdays
            for (let i = 0; i < firstDayIndex; i++) {
                const emptyCell = document.createElement('span');
                emptyCell.className = 'calendar-day empty';
                daysGridEl.appendChild(emptyCell);
            }

            // Render active calendar days
            for (let day = 1; day <= totalDays; day++) {
                const dayCell = document.createElement('span');
                dayCell.className = 'calendar-day';
                dayCell.textContent = day;

                const cellDate = new Date(currentCalYear, currentCalMonth, day);
                cellDate.setHours(0, 0, 0, 0);

                if (cellDate < minDate) {
                    dayCell.classList.add('disabled');
                } else {
                    if (selectedDate.getDate() === day && selectedDate.getMonth() === currentCalMonth && selectedDate.getFullYear() === currentCalYear) {
                        dayCell.classList.add('active');
                    }

                    dayCell.addEventListener('click', () => {
                        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('active'));
                        dayCell.classList.add('active');
                        selectedDate = cellDate;
                        updateWhatsAppLink();
                    });
                }
                daysGridEl.appendChild(dayCell);
            }
        }

        function updateWhatsAppLink() {
            if (!bookDemoBtn) return;
            const formattedDate = `${selectedDate.getDate()} ${monthNames[selectedDate.getMonth()]} ${selectedDate.getFullYear()}`;
            const message = `Hi, I would like to book a demo class at Harita Music Academy on ${formattedDate} at ${selectedSlotText}.`;
            bookDemoBtn.href = `https://wa.me/+918796823332?text=${encodeURIComponent(message)}`;
        }

        if (prevMonthBtn && nextMonthBtn) {
            prevMonthBtn.addEventListener('click', () => {
                const minYear = minDate.getFullYear();
                const minMonth = minDate.getMonth();

                // Allow going back only if we are past the minimum month/year
                if (currentCalYear > minYear || (currentCalYear === minYear && currentCalMonth > minMonth)) {
                    currentCalMonth--;
                    if (currentCalMonth < 0) {
                        currentCalMonth = 11;
                        currentCalYear--;
                    }
                    renderCalendar();
                }
            });

            nextMonthBtn.addEventListener('click', () => {
                currentCalMonth++;
                if (currentCalMonth > 11) {
                    currentCalMonth = 0;
                    currentCalYear++;
                }
                renderCalendar();
            });
        }

        if (slotsContainer) {
            slotsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('booking-slot-pill')) {
                    document.querySelectorAll('.booking-slot-pill').forEach(pill => pill.classList.remove('active'));
                    e.target.classList.add('active');
                    selectedSlotText = e.target.textContent;
                    updateWhatsAppLink();
                }
            });
        }

        // Numbers Counter Animation (Intersection Observer based)
        const counterElements = document.querySelectorAll('.counter-num');
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const endVal = parseInt(target.getAttribute('data-target'), 10);
                    const suffix = target.getAttribute('data-suffix') || '';
                    const duration = 1500; // ms animation duration
                    const startTime = performance.now();

                    function updateCounter(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        // Ease out quadratic progress
                        const easeProgress = progress * (2 - progress);
                        const currentVal = Math.floor(easeProgress * endVal);

                        target.textContent = currentVal + suffix;

                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        } else {
                            target.textContent = endVal + suffix;
                        }
                    }
                    requestAnimationFrame(updateCounter);
                    counterObserver.unobserve(target); // Only animate once
                }
            });
        }, { threshold: 0.1 });

        counterElements.forEach(el => counterObserver.observe(el));

        // Clickable Cards Active States (Red background, white text)
        document.addEventListener('click', (e) => {
            const card = e.target.closest('.category-card, .pricing-card, .commitment-feature-item, .about-feature-card, .step-card');
            if (card) {
                // Find sibling cards inside the same grid container to deselect them
                const container = card.closest('.about-features-grid, .pricing-grid, .cards-scroll-wrap, .journey-grid, .why-us-features-grid');
                if (container) {
                    container.querySelectorAll('.category-card, .pricing-card, .commitment-feature-item, .about-feature-card, .step-card').forEach(sibling => {
                        if (sibling !== card) sibling.classList.remove('active-red');
                    });
                }
                card.classList.toggle('active-red');
            }
        });

        renderCalendar();
        updateWhatsAppLink();


        const counters = document.querySelectorAll(".stat-number, .map-stat-num");

        const animateCounter = (counter) => {
            const originalText = counter.textContent.trim();

            // Use data-target if available, otherwise read from the text
            const target = counter.dataset.target
                ? parseFloat(counter.dataset.target)
                : parseFloat(originalText.replace(/,/g, "").replace(/[^\d.]/g, ""));

            const hasPlus = originalText.includes("+");
            const hasStar = originalText.includes("★");

            const duration = 3500;
            const startTime = performance.now();

            function update(currentTime) {
                const progress = Math.min((currentTime - startTime) / duration, 1);

                // Ease out
                const eased = 1 - Math.pow(1 - progress, 4);

                const value = target * eased;

                if (hasStar) {
                    counter.textContent = value.toFixed(1) + "★";
                } else if (target >= 1000) {
                    counter.textContent =
                        Math.floor(value).toLocaleString("en-IN") + (hasPlus ? "+" : "");
                } else {
                    counter.textContent =
                        Math.floor(value) + (hasPlus ? "+" : "");
                }

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    if (hasStar) {
                        counter.textContent = target.toFixed(1) + "★";
                    } else if (target >= 1000) {
                        counter.textContent =
                            target.toLocaleString("en-IN") + (hasPlus ? "+" : "");
                    } else {
                        counter.textContent =
                            target + (hasPlus ? "+" : "");
                    }
                }
            }

            requestAnimationFrame(update);
        };
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target); // Run only once
                }
            });
        }, {
            threshold: 0.4
        });

        counters.forEach(counter => observer.observe(counter));
    </script>
</body>

</html>