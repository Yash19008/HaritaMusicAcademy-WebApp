@extends('layouts.landing')
@section('content')
    <!-- 1. Hero Section (Cinematic Two-Column Luxury Layout) -->
    <section class="hero-luxury" id="home">
        <div class="container">
            <div class="hero-luxury-grid">
                <!-- Left Side: Editorial Content -->
                <div class="hero-luxury-left reveal-left">
                    <span class="hero-luxury-badge">WORLD-CLASS MUSIC EDUCATION</span>
                    <h1 class="hero-luxury-title">Discover the Beauty of Indian Music Through Live <span
                            style="color: #C8A56A;;">Online
                            Classes</span>
                    </h1>
                    <p class="hero-luxury-desc">
                        Whether you're beginning your musical journey or looking to refine your skills, Harita Music
                        Academy offers live online classes in Hindustani Classical Vocal, Bollywood Singing, Piano,
                        Keyboard, Harmonium, and Tabla.
                    </p>

                    <div class="hero-luxury-actions">
                        <a href="#trial" class="btn btn-primary">Book Demo</a>
                        <a href="{{ asset('landing/assets/') }}/videos/HMA-Video-1.mp4"
                            data-video-src="{{ asset('landing/assets/') }}/videos/HMA-Video-1.mp4"
                            class="btn btn-secondary video-play-trigger-hero" id="hero-video-btn"
                            style="color: #ffffff; border-color: rgba(255, 255, 255, 0.45);">
                            <svg class="play-icon" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"
                                style="margin-right: 8px;">
                                <path d="M8 5v14l11-7z"></path>
                            </svg>
                            Watch Video
                        </a>
                    </div>

                    <!-- Statistics Block -->
                    <div class="hero-luxury-stats">
                        <div class="stat-item">
                            <span class="stat-number" data-target="10000">10,000+</span>
                            <span class="stat-label">Students</span>
                        </div>

                        <div class="stat-item">
                            <span class="stat-number" data-target="25">25+</span>
                            <span class="stat-label">Countries</span>
                        </div>

                        <div class="stat-item">
                            <span class="stat-number" data-target="50">50+</span>
                            <span class="stat-label">Teachers</span>
                        </div>

                        <div class="stat-item">
                            <span class="stat-number" data-target="4.9">4.9★</span>
                            <span class="stat-label">Rating</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Spacer to let the background illustration show through -->
                <div class="hero-luxury-right reveal-right">
                    <!-- Empty spacer to preserve grid columns and allow background graphic to show -->
                </div>
            </div>
        </div>
    </section>

    <!-- 9. Booking Widget / Trial Class Section (Snapshot 3  Demo Calendar Widget) -->
    <section class="booking-widget-section" id="trial">
        <div class="container">
            <div class="booking-widget-card">

                <!-- Column 1: Steps List -->
                <div class="booking-widget-col reveal-left">
                    <span class="section-label" style="margin-bottom: 0.5rem;">BOOK YOUR DEMO</span>
                    <div style="display: flex; justify-content: flex-start; margin: 0.25rem 0 0.75rem 0;">
                        <svg width="60" height="12" viewBox="0 0 60 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 6C15 1.5, 20 10.5, 30 6 C40 1.5, 45 10.5, 55 6" stroke="#C8A56A" stroke-width="1.5"
                                stroke-linecap="round" />
                            <circle cx="30" cy="6" r="2.5" fill="#C8A56A" />
                        </svg>
                    </div>
                    <h2 class="section-title" style="font-size: 1.85rem; line-height: 1.25; margin-bottom: 1.25rem;">
                        Experience HMA Before You Begin</h2>
                    <div
                        style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; margin: 1.25rem 0;">
                        <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                        </div>
                        <div
                            style="width: 5px; height: 5px; transform: rotate(45deg); background-color: var(--color-accent);">
                        </div>
                        <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                        </div>
                    </div>
                    <div class="booking-widget-steps">
                        <div class="booking-step-item">
                            <div class="booking-step-num">01</div>
                            <div>
                                <h4 class="booking-step-title">Choose Date & Time</h4>
                                <p class="booking-step-desc">Pick a slot that works for you.</p>
                            </div>
                        </div>
                        <div class="booking-step-item">
                            <div class="booking-step-num">02</div>
                            <div>
                                <h4 class="booking-step-title">Make Payment</h4>
                                <p class="booking-step-desc">Securely pay
                                    {{ $locale['currency_sym'] ?? '₹' }}{{ number_format($locale['demo_price'] ?? 499) }}
                                    online.</p>
                            </div>
                        </div>
                        <div class="booking-step-item">
                            <div class="booking-step-num">03</div>
                            <div>
                                <h4 class="booking-step-title">Join Your Live Demo</h4>
                                <p class="booking-step-desc">Experience a real HMA class.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Monthly Calendar -->
                <div class="booking-widget-col reveal-right">
                    <div class="booking-calendar-box">
                        <div class="calendar-header">
                            <button class="calendar-nav-btn" id="prev-month" aria-label="Previous Month">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>
                            <span class="calendar-title" id="calendar-month-year">July 2026</span>
                            <button class="calendar-nav-btn" id="next-month" aria-label="Next Month">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                        </div>
                        <div class="calendar-weekdays">
                            <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                        </div>
                        <div class="calendar-days" id="calendar-days-grid">
                            <!-- Calendar days injected via JS -->
                        </div>
                    </div>
                </div>

                <!-- Column 3: Available Slots -->
                <div class="booking-widget-col">
                    <div class="booking-slots-card">
                        <span class="booking-slots-title">Available Slots</span>
                        <div class="booking-slots-list" id="slots-container">
                            <button class="booking-slot-pill">08:00 AM</button>
                            <button class="booking-slot-pill">08:40 AM</button>
                            <button class="booking-slot-pill">09:20 AM</button>
                            <button class="booking-slot-pill active">10:00 AM</button>
                            <button class="booking-slot-pill">10:40 AM</button>
                            <button class="booking-slot-pill">11:20 AM</button>
                            <button class="booking-slot-pill">12:00 PM</button>
                            <button class="booking-slot-pill">12:40 PM</button>
                            <button class="booking-slot-pill">01:20 PM</button>
                            <button class="booking-slot-pill">02:00 PM</button>
                            <button class="booking-slot-pill">02:40 PM</button>
                            <button class="booking-slot-pill">03:20 PM</button>
                            <button class="booking-slot-pill">04:00 PM</button>
                            <button class="booking-slot-pill">04:40 PM</button>
                            <button class="booking-slot-pill">05:20 PM</button>
                            <button class="booking-slot-pill">06:00 PM</button>
                            <button class="booking-slot-pill">06:40 PM</button>
                            <button class="booking-slot-pill">07:20 PM</button>
                            <button class="booking-slot-pill">08:00 PM</button>
                            <button class="booking-slot-pill">08:40 PM</button>
                            <button class="booking-slot-pill">09:20 PM</button>
                            <button class="booking-slot-pill">10:00 PM</button>
                            <button class="booking-slot-pill">10:40 PM</button>
                            <button class="booking-slot-pill">11:20 PM</button>
                            <button class="booking-slot-pill">12:00 AM</button>
                            <button class="booking-slot-pill">12:40 AM</button>
                            <button class="booking-slot-pill">01:20 AM</button>
                        </div>
                    </div>
                </div>

                <!-- Column 4: Payment Summary Box -->
                <div class="booking-widget-col">
                    <div class="booking-security-card">
                        <div class="security-card-header">
                            <div class="security-card-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </div>
                            <h3 class="security-card-title">Safe & Secure Payments</h3>
                            <p class="security-card-desc">
                                {{ $locale['currency_sym'] ?? '₹' }}{{ number_format($locale['demo_price'] ?? 499) }} Demo
                                Class Fee is Fully Adjustable.</p>
                            <div class="security-features-list">

                                <div class="security-feature-bullet">
                                    <span class="bullet-icon-wrapper green-check">✓</span>
                                    <span class="bullet-text">We charge a fee of
                                        {{ $locale['currency_sym'] ?? '₹' }}{{ number_format($locale['demo_price'] ?? 499) }}
                                        to filter out non-serious
                                        applications.</span>
                                </div>
                                <div class="security-feature-bullet">
                                    <span class="bullet-icon-wrapper green-check">✓</span>
                                    <span class="bullet-text">If you enroll in any course after the demo class, the
                                        entire
                                        {{ $locale['currency_sym'] ?? '₹' }}{{ number_format($locale['demo_price'] ?? 499) }}
                                        will be adjusted in your course fee. If you choose not to
                                        enroll, the demo fee is non-refundable.</span>
                                </div>
                                <div class="security-feature-bullet">
                                    <span class="bullet-icon-wrapper green-check">✓</span>
                                    <span class="bullet-text">Fully adjusted against your course fee if you decide
                                        to join.</span>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem;">
                            <button onclick="openDemoModal()" class="btn btn-primary"
                                style="width: 100%; padding: 0.85rem 1rem; font-size: 0.85rem; border-radius: 50px;">
                                Book Demo
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- DEMO BOOKING MODAL -->
    <div id="publicDemoModal" class="modal-backdrop">
        <div class="modal" style="max-width: 560px; position: relative;">
            <div class="modal-header"
                style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                <div>
                    <h3 class="font-semibold text-serif" style="margin:0; font-size: 1.4rem; color: #111;">Book Your Demo
                        Class</h3>
                    <p style="margin:0.25rem 0 0; font-size:0.8rem; color:#888;">
                        {{ $locale['currency_sym'] ?? '₹' }}{{ number_format($locale['demo_price'] ?? 499) }} · Fully
                        adjustable towards course fee</p>
                </div>
                <button class="modal-close" onclick="closeDemoModal()"
                    style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #888;">&times;</button>
            </div>

            <form id="demoBookingForm" onsubmit="handleDemoSubmit(event)" novalidate>
                @csrf
                <div class="modal-body" style="padding: 1.5rem 0;">

                    <!-- Row 1: Name + Phone -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div class="form-group">
                            <label
                                style="display:block; margin-bottom:0.4rem; font-weight:600; font-size:0.82rem; color:#444;">Full
                                Name *</label>
                            <input type="text" id="demo_student_name" name="student_name" required
                                placeholder="e.g. Priya Sharma"
                                style="width:100%; padding:0.7rem 0.9rem; border:1.5px solid #ddd; border-radius:8px; font-size:0.9rem; box-sizing:border-box; transition:border-color .2s;"
                                onfocus="this.style.borderColor='#51040e'" onblur="this.style.borderColor='#ddd'">
                        </div>
                        <div class="form-group">
                            <label
                                style="display:block; margin-bottom:0.4rem; font-weight:600; font-size:0.82rem; color:#444;">Phone
                                Number *</label>
                            <input type="tel" id="demo_phone" name="phone" required placeholder="+91 98765 43210"
                                style="width:100%; padding:0.7rem 0.9rem; border:1.5px solid #ddd; border-radius:8px; font-size:0.9rem; box-sizing:border-box; transition:border-color .2s;"
                                onfocus="this.style.borderColor='#51040e'" onblur="this.style.borderColor='#ddd'">
                        </div>
                    </div>

                    <!-- Row 2: Email -->
                    <div class="form-group" style="margin-bottom: 0.75rem;">
                        <label
                            style="display:block; margin-bottom:0.4rem; font-weight:600; font-size:0.82rem; color:#444;">Email
                            Address *</label>
                        <input type="email" id="demo_email" name="email" required placeholder="you@example.com"
                            style="width:100%; padding:0.7rem 0.9rem; border:1.5px solid #ddd; border-radius:8px; font-size:0.9rem; box-sizing:border-box; transition:border-color .2s;"
                            onfocus="this.style.borderColor='#51040e'" onblur="this.style.borderColor='#ddd'">
                    </div>

                    <!-- Row 3: Plan Type + Programme -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div class="form-group">
                            <label style="display:block; margin-bottom:0.4rem; font-weight:600; font-size:0.82rem; color:#444;">Plan Type *</label>
                            <select id="demo_plan_type" name="plan_type" required onchange="updateProgrammeOptions()"
                                style="width:100%; padding:0.7rem 0.9rem; border:1.5px solid #ddd; border-radius:8px; background:white; font-size:0.9rem; box-sizing:border-box;">
                                <option value="">Select Plan Type...</option>
                                <option value="individual">Individual Plans</option>
                                <option value="group">Group Plans</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="display:block; margin-bottom:0.4rem; font-weight:600; font-size:0.82rem; color:#444;">Programme *</label>
                            <select id="demo_programme" name="programme" required
                                style="width:100%; padding:0.7rem 0.9rem; border:1.5px solid #ddd; border-radius:8px; background:white; font-size:0.9rem; box-sizing:border-box;">
                                <option value="">Select Programme...</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 4: Preferred Date + Time -->
                    <div id="demo_datetime_row"
                        style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div class="form-group">
                            <label
                                style="display:block; margin-bottom:0.4rem; font-weight:600; font-size:0.82rem; color:#444;">Preferred
                                Date *</label>
                            <input type="date" id="demo_preferred_date" name="preferred_date" required
                                style="width:100%; padding:0.7rem 0.9rem; border:1.5px solid #ddd; border-radius:8px; font-size:0.9rem; box-sizing:border-box;"
                                min="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label
                                style="display:block; margin-bottom:0.4rem; font-weight:600; font-size:0.82rem; color:#444;">Preferred
                                Time *</label>
                            <select id="demo_preferred_time" name="preferred_time" required
                                style="width:100%; padding:0.7rem 0.9rem; border:1.5px solid #ddd; border-radius:8px; background:white; font-size:0.9rem; box-sizing:border-box;">
                                <option value="">Select time slot...</option>
                                <option value="08:00">8:00 AM – 8:40 AM</option>
                                <option value="08:40">8:40 AM – 9:20 AM</option>
                                <option value="09:20">9:20 AM – 10:00 AM</option>
                                <option value="10:00">10:00 AM – 10:40 AM</option>
                                <option value="10:40">10:40 AM – 11:20 AM</option>
                                <option value="11:20">11:20 AM – 12:00 PM</option>
                                <option value="12:00">12:00 PM – 12:40 PM</option>
                                <option value="12:40">12:40 PM – 1:20 PM</option>
                                <option value="13:20">1:20 PM – 2:00 PM</option>
                                <option value="14:00">2:00 PM – 2:40 PM</option>
                                <option value="14:40">2:40 PM – 3:20 PM</option>
                                <option value="15:20">3:20 PM – 4:00 PM</option>
                                <option value="16:00">4:00 PM – 4:40 PM</option>
                                <option value="16:40">4:40 PM – 5:20 PM</option>
                                <option value="17:20">5:20 PM – 6:00 PM</option>
                                <option value="18:00">6:00 PM – 6:40 PM</option>
                                <option value="18:40">6:40 PM – 7:20 PM</option>
                                <option value="19:20">7:20 PM – 8:00 PM</option>
                                <option value="20:00">8:00 PM – 8:40 PM</option>
                                <option value="20:40">8:40 PM – 9:20 PM</option>
                                <option value="21:20">9:20 PM – 10:00 PM</option>
                                <option value="22:00">10:00 PM – 10:40 PM</option>
                                <option value="22:40">10:40 PM – 11:20 PM</option>
                                <option value="23:20">11:20 PM – 12:00 AM</option>
                                <option value="00:00">12:00 AM – 12:40 AM</option>
                                <option value="00:40">12:40 AM – 1:20 AM</option>
                                <option value="01:20">1:20 AM – 2:00 AM</option>
                            </select>
                        </div>
                    </div>

                    <!-- Error message -->
                    <div id="demoFormError"
                        style="display:none; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.75rem 1rem; border-radius:8px; font-size:0.85rem; margin-bottom:0.75rem;">
                    </div>

                    <!-- Security trust signals -->
                    <div
                        style="display:flex; align-items:center; gap:0.5rem; background:#f9fafb; border-radius:8px; padding:0.6rem 0.9rem; margin-top:0.5rem;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <span style="font-size:0.78rem; color:#555;">100% Secure · Powered by Razorpay ·
                            {{ $locale['currency_sym'] ?? '₹' }}{{ number_format($locale['demo_price'] ?? 499) }}
                            adjustable towards your course</span>
                    </div>
                </div>

                <div class="modal-footer" style="display:flex; gap:0.75rem; border-top:1px solid #eee; padding-top:1rem;">
                    <button type="button" onclick="closeDemoModal()"
                        style="flex:1; padding:0.85rem; background:#f3f4f6; border:none; border-radius:50px; font-weight:600; cursor:pointer; color:#555;">
                        Cancel
                    </button>
                    <button type="submit" id="demoSubmitBtn" class="btn btn-primary"
                        style="flex:2; padding:0.85rem; border-radius:50px; font-weight:700; display:flex; align-items:center; justify-content:center; gap:0.5rem;">
                        <span id="demoSubmitText">Pay
                            {{ $locale['currency_sym'] ?? '₹' }}{{ number_format($locale['demo_price'] ?? 499) }} &amp;
                            Book Demo</span>
                        <svg id="demoSubmitSpinner" style="display:none; animation:spin 1s linear infinite;"
                            width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path
                                d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal (shown after payment verified) -->
    <div id="demoSuccessModal" class="modal-backdrop" style="display:none;">
        <div class="modal" style="max-width:420px; text-align:center; padding:2.5rem;">
            <div
                style="width:70px; height:70px; background:#dcfce7; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#16a34a"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 style="font-size:1.4rem; color:#111; margin:0 0 0.5rem;">Demo Booked! 🎵</h3>
            <p style="color:#555; font-size:0.9rem; line-height:1.6; margin:0 0 1.5rem;">
                Your payment is confirmed. Our team will reach out within a few hours to confirm your exact demo slot.
            </p>
            <button
                onclick="let m = document.getElementById('demoSuccessModal'); m.classList.remove('show'); setTimeout(() => m.style.display='none', 300);"
                class="btn btn-primary" style="padding:0.75rem 2rem; border-radius:50px; font-weight:600;">
                Done
            </button>
        </div>
    </div>

    @if (session('demo_success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sModal = document.getElementById('demoSuccessModal');
                sModal.style.display = 'flex';
                void sModal.offsetWidth;
                sModal.classList.add('show');
            });
        </script>
    @endif

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        (function() {
            const VERIFY_URL = "{{ route('payment.verify') }}";
            const CREATE_URL = "{{ route('payment.create') }}";
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}";
            const COUNTRY = "{{ $country ?? 'in' }}";
            const CURRENCY = "{{ $locale['currency_code'] ?? 'INR' }}";
            const DEMO_PRICE = {{ $locale['demo_price'] ?? 499 }};
            const CUR_SYM = "{{ $locale['currency_sym'] ?? '₹' }}";

            window.handleDemoSubmit = async function(e) {
                e.preventDefault();
                const form = document.getElementById('demoBookingForm');
                const errBox = document.getElementById('demoFormError');
                const btn = document.getElementById('demoSubmitBtn');
                const spinner = document.getElementById('demoSubmitSpinner');
                const btnText = document.getElementById('demoSubmitText');

                // — Client-side validation —
                const name = document.getElementById('demo_student_name').value.trim();
                const phone = document.getElementById('demo_phone').value.trim();
                const email = document.getElementById('demo_email').value.trim();
                const planTypeRaw = document.getElementById('demo_plan_type').value;
                const programmeRaw = document.getElementById('demo_programme').value;
                const date = document.getElementById('demo_preferred_date').value;
                const time = document.getElementById('demo_preferred_time').value;

                errBox.style.display = 'none';

                if (!name || !phone || !email || !planTypeRaw || !programmeRaw || !date || !time) {
                    errBox.textContent = 'Please fill in all required fields.';
                    errBox.style.display = 'block';
                    return;
                }
                
                // Format Plan Type for backend
                const planTypeFormatted = planTypeRaw.charAt(0).toUpperCase() + planTypeRaw.slice(1);
                const instrument = `${planTypeFormatted} - ${programmeRaw}`;
                if (!/^\S+@\S+\.\S+$/.test(email)) {
                    errBox.textContent = 'Please enter a valid email address.';
                    errBox.style.display = 'block';
                    return;
                }

                // — Show loading state —
                btn.disabled = true;
                spinner.style.display = 'inline-block';
                btnText.textContent = 'Preparing Payment…';

                try {
                    // Step 1: Create order server-side
                    const orderResp = await fetch(CREATE_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            student_name: name,
                            email,
                            phone,
                            instrument,
                            preferred_date: date,
                            preferred_time: time,
                            country: COUNTRY // sent to server; server resolves price/currency from config
                        })
                    });
                    const orderData = await orderResp.json();

                    if (!orderData.success) {
                        throw new Error(orderData.message || 'Could not initialize payment.');
                    }

                    // Step 2: Open Razorpay Checkout
                    const displayText = `Pay ${CUR_SYM}${DEMO_PRICE.toLocaleString()} & Book Demo`;
                    btnText.textContent = displayText;
                    spinner.style.display = 'none';
                    btn.disabled = false;

                    const options = {
                        key: orderData.key,
                        amount: orderData.amount, // smallest unit, set by server
                        currency: orderData.currency ?? CURRENCY, // from server response
                        name: 'Harita Music Academy',
                        description: 'Demo Class Booking',
                        order_id: orderData.order_id,
                        prefill: {
                            name: name,
                            email: email,
                            contact: phone,
                        },
                        theme: {
                            color: '#51040e'
                        },
                        modal: {
                            ondismiss: function() {
                                // User closed modal without paying — no action needed
                            }
                        },
                        handler: async function(response) {
                            // Step 3: Verify payment signature server-side
                            btn.disabled = true;
                            btnText.textContent = 'Verifying…';
                            spinner.style.display = 'inline-block';

                            try {
                                const verifyResp = await fetch(VERIFY_URL, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': CSRF_TOKEN,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        razorpay_order_id: response.razorpay_order_id,
                                        razorpay_payment_id: response
                                            .razorpay_payment_id,
                                        razorpay_signature: response.razorpay_signature,
                                        payment_db_id: orderData.payment_db_id,
                                    })
                                });
                                const verifyData = await verifyResp.json();

                                if (verifyData.success) {
                                    closeDemoModal();
                                    const sModal = document.getElementById('demoSuccessModal');
                                    sModal.style.display = 'flex';
                                    void sModal.offsetWidth;
                                    sModal.classList.add('show');
                                } else {
                                    throw new Error(verifyData.message || 'Verification failed.');
                                }
                            } catch (verifyErr) {
                                errBox.textContent = verifyErr.message ||
                                    'Payment verified but confirmation failed. Please contact support.';
                                errBox.style.display = 'block';
                            } finally {
                                btn.disabled = false;
                                btnText.textContent = displayText;
                                spinner.style.display = 'none';
                            }
                        }
                    };

                    const rzp = new Razorpay(options);
                    rzp.on('payment.failed', function(resp) {
                        errBox.textContent = 'Payment failed: ' + (resp.error?.description ||
                            'Please try again.');
                        errBox.style.display = 'block';
                    });
                    rzp.open();

                } catch (err) {
                    errBox.textContent = err.message || 'Something went wrong. Please try again.';
                    errBox.style.display = 'block';
                    btn.disabled = false;
                    btnText.textContent = `Pay ${CUR_SYM}${DEMO_PRICE.toLocaleString()} & Book Demo`;
                    spinner.style.display = 'none';
                }
            };
        })();
    </script>

    <!-- 4. Courses Offered Section (Explore by Category design preference) -->
    <section class="section reveal" id="courses">
        <div class="container">
            <div class="split-layout">
                <div class="split-left-sticky reveal-left">
                    <span class="split-left-label">SOMETHING FOR EVERYONE</span>
                    <div style="display: flex; justify-content: flex-start; margin: 0.25rem 0 0.75rem 0;">
                        <svg width="60" height="12" viewBox="0 0 60 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 6C15 1.5, 20 10.5, 30 6 C40 1.5, 45 10.5, 55 6" stroke="#C8A56A"
                                stroke-width="1.5" stroke-linecap="round" />
                            <circle cx="30" cy="6" r="2.5" fill="#C8A56A" />
                        </svg>
                    </div>
                    <h2 class="split-left-title">Explore by category</h2>
                    <div
                        style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; margin: 1.25rem 0;">
                        <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                        </div>
                        <div
                            style="width: 5px; height: 5px; transform: rotate(45deg); background-color: var(--color-accent);">
                        </div>
                        <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                        </div>
                    </div>
                    <p class="split-left-desc">
                        Follow your curiosity across vocal depth, classical foundations, instrumental dexterity, and
                        traditional rhythm structures.
                    </p>

                    <div class="split-controls">
                        <button class="control-btn" id="course-scroll-prev" aria-label="Previous Category">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <button class="control-btn" id="course-scroll-next" aria-label="Next Category">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="cards-scroll-wrap" id="course-cards-container">
                    <!-- Category Card 1: Vocal Music -->
                    <div class="category-card reveal-right" style="transition-delay: 0.00s;">
                        <div class="category-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"></path>
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                                <line x1="12" y1="19" x2="12" y2="22"></line>
                            </svg>
                        </div>
                        <div class="category-card-body">
                            <h3 class="category-card-title">Vocal Music</h3>
                            <ul class="category-card-list">
                                <li class="category-card-item">Hindustani Classical Vocal</li>
                                <li class="category-card-item">Bollywood Singing</li>
                                <li class="category-card-item">Devotional Music (Bhajan & Aarti)</li>
                                <li class="category-card-item">Semi-Classical Vocal</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category Card 2: Instrumental Music -->
                    <div class="category-card reveal-right" style="transition-delay: 0.15s;">
                        <div class="category-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M9 18V5l12-2v13"></path>
                                <circle cx="6" cy="18" r="3"></circle>
                                <circle cx="18" cy="16" r="3"></circle>
                            </svg>
                        </div>
                        <div class="category-card-body">
                            <h3 class="category-card-title">Instrumental Music</h3>
                            <ul class="category-card-list">
                                <li class="category-card-item">Harmonium</li>
                                <li class="category-card-item">Keyboard</li>
                                <li class="category-card-item">Piano</li>
                                <li class="category-card-item">Guitar</li>
                                <li class="category-card-item">Tabla</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category Card 3: Indian Foundations -->
                    <div class="category-card reveal-right" style="transition-delay: 0.30s;">
                        <div class="category-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                </polygon>
                            </svg>
                        </div>
                        <div class="category-card-body">
                            <h3 class="category-card-title">Music Education
                            </h3>
                            <ul class="category-card-list">
                                <li class="category-card-item">Music Theory by Professors</li>
                                <li class="category-card-item">UGC NET Music
                                </li>
                                <li class="category-card-item">University Music Courses</li>
                                <li class="category-card-item">Musicology</li>
                                <li class="category-card-item">Academic Guidance & Research Support
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suitable For Tag Cloud -->
            <div class="suitable-container">
                <h3 class="suitable-title">Suitable for Learners of All Ages</h3>
                <div class="suitable-tags">
                    <div class="suitable-tag">Children</div>
                    <div class="suitable-tag">Teenagers</div>
                    <div class="suitable-tag">Adults</div>
                    <div class="suitable-tag">Working Professionals</div>
                    <div class="suitable-tag">Senior Learners</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Learning Journey Section -->
    <section class="section reveal" id="journey">
        <div class="container">
            <div class="section-header reveal-scale">
                <span class="section-label">Academic Roadmap</span>
                <div style="display: flex; justify-content: center; margin: 0.25rem auto 0.75rem auto;">
                    <svg width="60" height="12" viewBox="0 0 60 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 6C15 1.5, 20 10.5, 30 6 C40 1.5, 45 10.5, 55 6" stroke="#C8A56A" stroke-width="1.5"
                            stroke-linecap="round" />
                        <circle cx="30" cy="6" r="2.5" fill="#C8A56A" />
                    </svg>
                </div>
                <h2 class="section-title">A Structured Path to Musical Excellence</h2>
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin: 1.25rem 0;">
                    <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                    </div>
                    <div style="width: 5px; height: 5px; transform: rotate(45deg); background-color: var(--color-accent);">
                    </div>
                    <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                    </div>
                </div>
                <p class="section-description">Our curriculum is carefully designed to build strong fundamentals
                    before moving toward advanced performance skills.</p>
            </div>

            <div class="journey-grid">
                <!-- Stage 1 -->
                <div class="journey-step reveal-scale" style="transition-delay: 0s;">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Foundation Level</h3>
                    <ul class="step-list">
                        <li class="step-item">Voice Culture</li>
                        <li class="step-item">Swar Sadhana</li>
                        <li class="step-item">Basic Rhythm</li>
                        <li class="step-item">Ear Training</li>
                        <li class="step-item">Introduction to Indian Classical Music</li>
                    </ul>
                </div>
                <!-- Stage 2 -->
                <div class="journey-step reveal-scale" style="transition-delay: 0.15s;">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Intermediate Level</h3>
                    <ul class="step-list">
                        <li class="step-item">Alankars</li>
                        <li class="step-item">Raag Introduction</li>
                        <li class="step-item">Bandish Learning</li>
                        <li class="step-item">Lay & Taal Understanding</li>
                        <li class="step-item">Breath Control</li>
                    </ul>
                </div>
                <!-- Stage 3 -->
                <div class="journey-step reveal-scale" style="transition-delay: 0.3s;">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Advanced Level</h3>
                    <ul class="step-list">
                        <li class="step-item">Raag Development</li>
                        <li class="step-item">Aalap & Taan</li>
                        <li class="step-item">Performance Techniques</li>
                        <li class="step-item">Expression & Stage Presentation</li>
                    </ul>
                </div>
            </div>

            <p class="journey-note">
                * Students learning instrumental music follow a similar structured roadmap tailored to their
                instrument.
            </p>
        </div>
    </section>
    <!-- 4. Course Journey Section (New) -->
    <!-- 4. Course Journey Section (Dedicated Pristine Grid) -->
    <section class="section reveal" id="course-journey">
        <div class="container">
            <div class="split-layout">

                <!-- Left Content -->
                <div class="split-left-sticky reveal-left">
                    <span class="split-left-label">OUR STEP-BY-STEP METHODOLOGY</span>

                    <div style="display: flex; justify-content: flex-start; margin: 0.25rem 0 0.75rem 0;">
                        <svg width="60" height="12" viewBox="0 0 60 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 6C15 1.5, 20 10.5, 30 6 C40 1.5, 45 10.5, 55 6" stroke="#C8A56A"
                                stroke-width="1.5" stroke-linecap="round" />
                            <circle cx="30" cy="6" r="2.5" fill="#C8A56A" />
                        </svg>
                    </div>

                    <h2 class="split-left-title">Your Learning Journey</h2>

                    <div
                        style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; margin: 1.25rem 0;">
                        <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                        </div>
                        <div
                            style="width: 5px; height: 5px; transform: rotate(45deg); background-color: var(--color-accent);">
                        </div>
                        <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                        </div>
                    </div>

                    <p class="split-left-desc">
                        Every student follows a structured learning path—from the first demo session to
                        live performances and certifications. With expert mentorship and personalized
                        guidance, you'll develop your skills with confidence at every stage.
                    </p>

                    <div class="split-controls">
                        <button class="control-btn" id="journey-scroll-prev" aria-label="Previous Step">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>

                        <button class="control-btn" id="journey-scroll-next" aria-label="Next Step">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Cards -->
                <div class="cards-scroll-wrap" id="journey-cards-container">

                    <!-- STEP 1 -->
                    <div class="category-card reveal-right" style="transition-delay:0s;">
                        <div class="category-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>

                        <div class="category-card-body">
                            <h3 class="category-card-title">Step 01 - Book Demo</h3>

                            <ul class="category-card-list">
                                <li class="category-card-item">Choose a convenient schedule</li>
                                <li class="category-card-item">Meet an expert mentor</li>
                                <li class="category-card-item">Assess your current level</li>
                                <li class="category-card-item">Receive personalized guidance</li>
                            </ul>
                        </div>

                    </div>

                    <!-- STEP 2 -->
                    <div class="category-card reveal-right" style="transition-delay:.15s;">
                        <div class="category-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>

                        <div class="category-card-body">
                            <h3 class="category-card-title">Step 02 - Learn Live</h3>

                            <ul class="category-card-list">
                                <li class="category-card-item">One-on-one live classes</li>
                                <li class="category-card-item">Experienced music faculty</li>
                                <li class="category-card-item">Interactive online sessions</li>
                                <li class="category-card-item">Flexible class timings</li>
                            </ul>
                        </div>

                    </div>

                    <!-- STEP 3 -->
                    <div class="category-card reveal-right" style="transition-delay:.30s;">
                        <div class="category-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M9 18V5l12-2v13"></path>
                                <circle cx="6" cy="18" r="3"></circle>
                                <circle cx="18" cy="16" r="3"></circle>
                            </svg>
                        </div>

                        <div class="category-card-body">
                            <h3 class="category-card-title">Step 03 - Practice Daily</h3>

                            <ul class="category-card-list">
                                <li class="category-card-item">Structured Riyaz plans</li>
                                <li class="category-card-item">Study notes & recordings</li>
                                <li class="category-card-item">Regular mentor feedback</li>
                                <li class="category-card-item">Track your progress</li>
                            </ul>
                        </div>

                    </div>

                    <!-- STEP 4 -->
                    <div class="category-card reveal-right" style="transition-delay:.45s;">
                        <div class="category-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                </polygon>
                            </svg>
                        </div>

                        <div class="category-card-body">
                            <h3 class="category-card-title">Step 04 - Perform</h3>

                            <ul class="category-card-list">
                                <li class="category-card-item">Student showcases</li>
                                <li class="category-card-item">Virtual recitals</li>
                                <li class="category-card-item">Build stage confidence</li>
                                <li class="category-card-item">Celebrate every milestone</li>
                            </ul>
                        </div>

                    </div>

                    <!-- STEP 5 -->
                    <div class="category-card reveal-right" style="transition-delay:.60s;">
                        <div class="category-card-icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="8" r="7"></circle>
                                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                            </svg>
                        </div>

                        <div class="category-card-body">
                            <h3 class="category-card-title">Step 05 - Get Certified</h3>

                            <ul class="category-card-list">
                                <li class="category-card-item">Periodic assessments</li>
                                <li class="category-card-item">Level-wise certifications</li>
                                <li class="category-card-item">Recognize your achievements</li>
                                <li class="category-card-item">Continue your musical journey</li>
                            </ul>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- 5. Why Choose Harita Music Academy / Student Benefits Section -->
    <section class="section section-luxury-about reveal" id="why-us">
        <div class="container">
            <div class="section-header-center">
                <span class="section-label">THE HMA ADVANTAGE</span>
                <div style="display: flex; justify-content: left; margin: 0.25rem 0 0.75rem 0;">
                    <svg width="60" height="12" viewBox="0 0 60 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 6C15 1.5, 20 10.5, 30 6 C40 1.5, 45 10.5, 55 6" stroke="#C8A56A" stroke-width="1.5"
                            stroke-linecap="round" />
                        <circle cx="30" cy="6" r="2.5" fill="#C8A56A" />
                    </svg>
                </div>
                <h2 class="section-title">Why Choose Harita Music Academy</h2>
                <p class="section-description">Experience premium music learning designed to nurture discipline,
                    consistency, and artistic excellence.</p>
            </div>

            <!-- 1 Single Row Container with Right-to-Left Slide Animation -->
            <div class="why-choose-row">
                <!-- Benefit 1: Fixed Batch -->
                <div class="about-feature-card reveal-right" style="transition-delay: 0s;">
                    <div class="feat-card-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <path d="M9 16l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h4 class="feat-card-title">Structured Fixed Batches</h4>
                    <p class="feat-card-desc">Unlike self-paced or random scheduling, our fixed batches establish
                        consistent discipline, collaborative peer learning, and regular progress tracking.</p>
                </div>

                <!-- Benefit 2: Personal Guidance -->
                <div class="about-feature-card reveal-right" style="transition-delay: 0.12s;">
                    <div class="feat-card-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h4 class="feat-card-title">Personal Guidance</h4>
                    <p class="feat-card-desc">Academically qualified music mentors monitor your posture, vocal scale
                        register, and finger placements live in interactive 1-on-1 environments.</p>
                </div>

                <!-- Benefit 3: Weekly Feedback -->
                <div class="about-feature-card reveal-right" style="transition-delay: 0.24s;">
                    <div class="feat-card-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <h4 class="feat-card-title">Weekly Progress Feedback</h4>
                    <p class="feat-card-desc">Receive detailed weekly lesson logs, post-class recordings, and riyaz
                        backing practice tracks tailored directly to your milestone needs.</p>
                </div>

                <!-- Benefit 4: Performance Opportunities -->
                <div class="about-feature-card reveal-right" style="transition-delay: 0.36s;">
                    <div class="feat-card-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <h4 class="feat-card-title">Performance Opportunities</h4>
                    <p class="feat-card-desc">Showcase your musical growth in virtual concerts, digital recitals,
                        and public student showcases organized throughout the academic year.</p>
                </div>

                <!-- Benefit 5: Adjustable Demo Fee -->
                <div class="about-feature-card reveal-right" style="transition-delay: 0.48s;">
                    <div class="feat-card-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <h4 class="feat-card-title">₹499 Adjustable Demo Fee</h4>
                    <p class="feat-card-desc">Book your personalized live demo session for a nominal fee of ₹499,
                        which is 100% adjustable and credited into your course fees after enrollment.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Scrolling Text Marquee Ticker (Infinite Scroll Left-to-Right) -->
    <div class="text-marquee-container">
        <div class="text-marquee-wrapper">
            <div class="text-marquee-content">
                <span>HARITA MUSIC ACADEMY</span>
                <span>GLOBAL MUSIC FAMILY</span>
                <span>HARITA MUSIC ACADEMY</span>
                <span>GLOBAL MUSIC FAMILY</span>
                <span>HARITA MUSIC ACADEMY</span>
                <span>GLOBAL MUSIC FAMILY</span>
            </div>
            <div class="text-marquee-content" aria-hidden="true">
                <span>HARITA MUSIC ACADEMY</span>
                <span>GLOBAL MUSIC FAMILY</span>
                <span>HARITA MUSIC ACADEMY</span>
                <span>GLOBAL MUSIC FAMILY</span>
                <span>HARITA MUSIC ACADEMY</span>
                <span>GLOBAL MUSIC FAMILY</span>
            </div>
        </div>
    </div>
    <!-- 10. Global Community Section -->
    <section class="section section-global-community reveal" id="community">
        <div class="container">
            <div class="section-header reveal-scale" style="text-align: center; margin-bottom: 3rem;">
                <span class="section-label">GLOBAL MUSIC FAMILY</span>
                <div style="display: flex; justify-content: center; margin: 0.25rem auto 0.75rem auto;">
                    <svg width="60" height="12" viewBox="0 0 60 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 6C15 1.5, 20 10.5, 30 6 C40 1.5, 45 10.5, 55 6" stroke="#C8A56A" stroke-width="1.5"
                            stroke-linecap="round" />
                        <circle cx="30" cy="6" r="2.5" fill="#C8A56A" />
                    </svg>
                </div>
                <h2 class="section-title">Nurturing Music Across Borders</h2>
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin: 1.25rem 0;">
                    <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                    </div>
                    <div style="width: 5px; height: 5px; transform: rotate(45deg); background-color: var(--color-accent);">
                    </div>
                    <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                    </div>
                </div>
                <p class="section-description" style="max-width: 700px; margin: 0 auto;">
                    Harita Music Academy connects passionate learners across the globe, bringing authentic classical
                    music training straight to homes in 25+ countries.
                </p>
            </div>

            <!-- Map Card Wrapper -->
            <div class="map-container-outer reveal-scale">
                <div class="map-wrapper">
                    <!-- Loaded vector map -->
                    <img src="{{ asset('landing/assets/') }}/images/world-map.svg" alt="HMA Global Community World Map"
                        class="map-world-img">

                    <!-- Pulsing Pins with Visible Labels -->
                    <!-- India Pin (Hub) -->
                    <div class="map-pulse-pin" style="top: 54%; left: 67%;">
                        <div class="pin-dot hub"></div>
                        <div class="pin-glow"></div>
                        <span class="pin-label">India (Hub)</span>
                    </div>
                    <!-- USA Pin -->
                    <div class="map-pulse-pin" style="top: 44%; left: 25%;">
                        <div class="pin-dot"></div>
                        <div class="pin-glow"></div>
                        <span class="pin-label">USA</span>
                    </div>
                    <!-- UK Pin -->
                    <div class="map-pulse-pin" style="top: 31%; left: 48.5%;">
                        <div class="pin-dot"></div>
                        <div class="pin-glow"></div>
                        <span class="pin-label">UK</span>
                    </div>
                    <!-- UAE Pin -->
                    <div class="map-pulse-pin" style="top: 49%; left: 58.5%;">
                        <div class="pin-dot"></div>
                        <div class="pin-glow"></div>
                        <span class="pin-label">UAE</span>
                    </div>
                    <!-- Canada Pin -->
                    <div class="map-pulse-pin" style="top: 35%; left: 24%;">
                        <div class="pin-dot"></div>
                        <div class="pin-glow"></div>
                        <span class="pin-label label-left">Canada</span>
                    </div>
                </div>

                <!-- Statistics footer row -->
                <div class="map-stats-footer-grid">
                    <div class="map-stat-col">
                        <div class="map-stat-num">25+</div>
                        <div class="map-stat-lbl">Countries Connected</div>
                    </div>
                    <div class="map-stat-col">
                        <div class="map-stat-num">10,000+</div>
                        <div class="map-stat-lbl">Active Students</div>
                    </div>
                    <div class="map-stat-col">
                        <div class="map-stat-num">50+</div>
                        <div class="map-stat-lbl">Expert Mentors</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. Student Success Stories Section (Verified Students design preference) -->
    <section class="section reveal" id="testimonials">
        <div class="container">
            <div class="split-layout">
                <div class="split-left-sticky reveal-left">
                    <span class="split-left-label">TRUSTED BY LEARNERS</span>
                    <div style="display: flex; justify-content: flex-start; margin: 0.25rem 0 0.75rem 0;">
                        <svg width="60" height="12" viewBox="0 0 60 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 6C15 1.5, 20 10.5, 30 6 C40 1.5, 45 10.5, 55 6" stroke="#C8A56A"
                                stroke-width="1.5" stroke-linecap="round" />
                            <circle cx="30" cy="6" r="2.5" fill="#C8A56A" />
                        </svg>
                    </div>
                    <h2 class="split-left-title">Hear from Harita's verified students</h2>
                    <div
                        style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; margin: 1.25rem 0;">
                        <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                        </div>
                        <div
                            style="width: 5px; height: 5px; transform: rotate(45deg); background-color: var(--color-accent);">
                        </div>
                        <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                        </div>
                    </div>
                    <p class="split-left-desc">
                        Nothing reflects our live music instruction better than the real, direct feedback of our
                        global music student family.
                    </p>

                    <div class="split-controls">
                        <button class="control-btn" id="test-scroll-prev" aria-label="Previous Testimonial">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <button class="control-btn" id="test-scroll-next" aria-label="Next Testimonial">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>{{-- end split-left-sticky --}}

                <div class="cards-scroll-wrap-three" id="test-cards-container">

                    @foreach ($locale['reviews'] as $i => $review)
                        @php
                            $colors = ['#ffffff', '#51040e'];
                            $bgColor = $colors[$i % count($colors)];
                            $isDark = $bgColor === '#51040e';
                            $styleOverride = $isDark ? "--color-text-main: #ffffff; --color-text-light: rgba(255,255,255,0.8); border-color: #51040e;" : "";
                        @endphp
                        <div class="testimonial-card reveal-right"
                            style="transition-delay: {{ number_format($i * 0.15, 2) }}s; background-color: {{ $bgColor }}; {{ $styleOverride }}">
                            <div>
                                <div class="testimonial-card-top">
                                    <div class="test-avatar-ring">
                                        <img src="{{ asset('landing/assets/reviews/' . $review['img']) }}"
                                            alt="{{ $review['name'] }} Portrait" loading="lazy">
                                    </div>
                                    <div class="test-badge-info">
                                        <div class="test-stars-row" aria-label="5 star rating">
                                            @for ($s = 0; $s < 5; $s++)
                                                <svg viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z">
                                                    </path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="test-verified-badge">
                                            <svg viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z">
                                                </path>
                                            </svg>
                                            Verified {{ $review['type'] }}
                                        </span>
                                        <span class="test-location-lbl">{{ $review['loc'] }}</span>
                                    </div>
                                </div>

                                <p class="testimonial-card-quote">{{ $review['quote'] }}</p>
                            </div>
                            <div class="testimonial-card-footer">
                                <span class="testimonial-card-name">{{ $review['name'] }}</span>
                                <span class="testimonial-card-date">{{ $review['date'] }}</span>
                            </div>
                        </div>
                    @endforeach

                </div>{{-- end cards-scroll-wrap-three --}}

            </div>{{-- end split-layout --}}
        </div>{{-- end container --}}
    </section>

    <!-- 5. Course Fees & Pricing Section -->
    <section class="section section-alt reveal" id="pricing"
        style="overflow: hidden; background: url('{{ asset('landing/assets/') }}/images/lotus-background.png') no-repeat left bottom;">
        <div class="container pricing-grid-wrap">

            <div class="section-header reveal-scale">
                <span class="section-label">Tuition Plans</span>
                <div style="display: flex; justify-content: center; margin: 0.25rem auto 0.75rem auto;">
                    <svg width="60" height="12" viewBox="0 0 60 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 6C15 1.5, 20 10.5, 30 6 C40 1.5, 45 10.5, 55 6" stroke="#C8A56A" stroke-width="1.5"
                            stroke-linecap="round" />
                        <circle cx="30" cy="6" r="2.5" fill="#C8A56A" />
                    </svg>
                </div>
                <h2 class="section-title" style="color: var(--color-primary-green);">Simple &amp; Transparent Pricing</h2>
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin: 1.25rem 0;">
                    <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;"></div>
                    <div style="width: 5px; height: 5px; transform: rotate(45deg); background-color: var(--color-accent);">
                    </div>
                    <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;"></div>
                </div>
                <p class="section-description">Choose a flexible learning structure that fits your musical goals. No hidden
                    fees.</p>
            </div>

            {{-- ── Plan Type Tabs ── --}}
            <div class="plan-type-tabs">
                <button class="plan-type-tab active" data-tab="individual"
                    onclick="switchPlanTab('individual', this)">Individual Plans</button>
                <button class="plan-type-tab" data-tab="group" onclick="switchPlanTab('group', this)">Group
                    Plans</button>
            </div>

            {{-- ── Individual Plans ── --}}
            @php
                $sym = $locale['currency_sym'] ?? '₹';
            @endphp

            <div id="tab-individual" class="plan-tab-content">
                <div class="pricing-grid pricing-grid-3">
                    @foreach ($locale['individual_plans'] ?? [] as $plan)
                        <div
                            class="pricing-card new-pricing-card reveal-scale {{ $plan['popular'] ?? false ? 'is-popular' : '' }} {{ $plan['premium'] ?? false ? 'is-premium' : '' }}">
                            @if ($plan['popular'] ?? false)
                                <span class="badge-popular">MOST POPULAR</span>
                            @endif
                            @if ($plan['premium'] ?? false)
                                <span class="badge-premium">⭐ PREMIUM</span>
                            @endif
                            <div class="new-pricing-card-top">
                                <div class="new-pricing-card-meta">
                                    <span class="plan-programme-label">{{ $plan['programme'] }}</span>
                                    <span class="plan-duration-pill">{{ $plan['duration'] }}</span>
                                </div>
                                <h3 class="plan-tier-title">{{ $plan['tier'] }}</h3>
                                <div class="plan-detail-row">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        width="14" height="14">
                                        <path
                                            d="M15 10l4.553-2.069A1 1 0 0121 8.869v6.262a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                    </svg>
                                    <span>{{ $plan['classes'] }} Live Classes</span>
                                </div>
                                <div class="plan-detail-row">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        width="14" height="14">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span>{{ $plan['per_week'] }} classes/week · 40 min/class</span>
                                </div>
                            </div>
                            <div class="new-pricing-card-price">
                                <span class="price-amount">{{ $sym }}{{ number_format($plan['price']) }}</span>
                            </div>
                            <a href="#trial" class="btn btn-solid pricing-cta" onclick="selectPlanAndScroll('individual', '{{ $plan['programme'] }} ({{ $plan['tier'] }})')">Enroll Now</a>
                        </div>
                    @endforeach
                </div>
            </div>{{-- end #tab-individual --}}

            {{-- ── Group Plans ── --}}
            <div id="tab-group" class="plan-tab-content" style="display:none;">
                <div class="pricing-grid pricing-grid-2">
                    @foreach ($locale['group_plans'] ?? [] as $plan)
                        <div class="pricing-card new-pricing-card reveal-scale">
                            <div class="new-pricing-card-top">
                                <div class="new-pricing-card-meta">
                                    <span class="plan-programme-label">{{ $plan['programme'] }}</span>
                                    <span class="plan-duration-pill">{{ $plan['duration'] }}</span>
                                </div>
                                <div class="group-type-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        width="15" height="15">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    Group Learning
                                </div>
                                <div class="plan-detail-row" style="margin-top:0.75rem;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        width="14" height="14">
                                        <path
                                            d="M15 10l4.553-2.069A1 1 0 0121 8.869v6.262a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                    </svg>
                                    <span>{{ $plan['classes'] }} Live Classes</span>
                                </div>
                                <div class="plan-detail-row">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        width="14" height="14">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span>{{ $plan['per_week'] }} classes/week · 40 min/class</span>
                                </div>
                            </div>
                            <div class="new-pricing-card-price">
                                <span class="price-amount">{{ $sym }}{{ number_format($plan['price']) }}</span>
                                <span class="price-per-label">per student</span>
                            </div>
                            <a href="#trial" class="btn btn-solid pricing-cta" onclick="selectPlanAndScroll('group', '{{ $plan['programme'] }}')">Enroll Now</a>
                        </div>
                    @endforeach
                </div>
            </div>{{-- end #tab-group --}}

            <!-- Pricing Inclusions Grid -->
            <div class="inclusions-block">
                <h3 class="inclusions-title">Every Course Includes</h3>
                <div class="inclusions-grid">
                    <div class="inclusion-item">
                        <div class="inclusion-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        Live Online Classes
                    </div>
                    <div class="inclusion-item">
                        <div class="inclusion-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        Personal Learning Guidance
                    </div>
                    <div class="inclusion-item">
                        <div class="inclusion-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        Structured Curriculum
                    </div>
                    <div class="inclusion-item">
                        <div class="inclusion-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        Digital Practice Material
                    </div>
                    <div class="inclusion-item">
                        <div class="inclusion-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        Progress Tracking
                    </div>
                    <div class="inclusion-item">
                        <div class="inclusion-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        Flexible Scheduling
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function switchPlanTab(tab, btn) {
            document.querySelectorAll('.plan-tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.plan-type-tab').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + tab).style.display = 'block';
            btn.classList.add('active');
        }
    </script>

    <!-- 11. Meet Our Founder Section -->
    <section class="heritage-section">
        <div class="container">

            <div class="heritage-wrapper">

                <!-- Left -->
                <div class="heritage-left">

                    <span class="section-tag">
                        OUR HERITAGE
                    </span>

                    <h2>
                        Preserving Tradition,<br>
                        Inspiring Tomorrow
                    </h2>

                    <p>
                        At Harita Music Academy, we believe music is more than a skill—it is a way of seeing the
                        world. Music nurtures the mind, refines emotions, and teaches us to appreciate the beauty in
                        every note, every silence, and every moment.<br><br> It knows no boundaries of age,
                        language, or
                        background.<br><br> Rooted in the timeless traditions of Indian music, our purpose is to
                        help every
                        student experience music not just as an art, but as a lifelong source of learning,
                        expression, and inner growth.
                    </p>



                </div>

                <!-- Divider -->
                <div class="heritage-divider"></div>

                <!-- Right -->
                <div class="heritage-right">

                    <span class="section-tag">
                        OUR VISION
                    </span>

                    <h2>
                        Empowering Musical Excellence
                    </h2>

                    <div class="vision-list">

                        <div class="vision-item">
                            <div class="vision-icon">🎼</div>
                            <div>
                                <h4>Authentic Learning</h4>
                                <p>Traditional teaching with modern accessibility.</p>
                            </div>
                        </div>

                        <div class="vision-item">
                            <div class="vision-icon">🌍</div>
                            <div>
                                <h4>Global Community</h4>
                                <p>Students learning from more than 25 countries.</p>
                            </div>
                        </div>

                        <div class="vision-item">
                            <div class="vision-icon">🎓</div>
                            <div>
                                <h4>Academic Excellence</h4>
                                <p>Structured curriculum guided by experienced faculty.</p>
                            </div>
                        </div>

                        <div class="vision-item">
                            <div class="vision-icon">⭐</div>
                            <div>
                                <h4>Lifelong Growth</h4>
                                <p>Building discipline, confidence and musical expression.</p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- 12. Frequently Asked Questions Section (Two Columns) -->
    <section class="section reveal" id="faq">
        <div class="container">
            <div class="section-header reveal-scale">
                <span class="section-label">Support</span>
                <div style="display: flex; justify-content: center; margin: 0.25rem auto 0.75rem auto;">
                    <svg width="60" height="12" viewBox="0 0 60 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 6C15 1.5, 20 10.5, 30 6 C40 1.5, 45 10.5, 55 6" stroke="#C8A56A" stroke-width="1.5"
                            stroke-linecap="round" />
                        <circle cx="30" cy="6" r="2.5" fill="#C8A56A" />
                    </svg>
                </div>
                <h2 class="section-title">Frequently Asked Questions</h2>
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin: 1.25rem 0;">
                    <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                    </div>
                    <div
                        style="width: 5px; height: 5px; transform: rotate(45deg); background-color: var(--color-accent);">
                    </div>
                    <div style="width: 50px; height: 1px; background-color: var(--color-accent); opacity: 0.35;">
                    </div>
                </div>
                <p class="section-description">Have questions? We have answers. If you cannot find what you are
                    looking for, contact us directly.</p>
            </div>

            <div class="faq-list">
                <!-- Q1 -->
                <details class="faq-item reveal-scale" style="transition-delay: 0.00s;">
                    <summary class="faq-summary">
                        Can complete beginners join?
                        <svg class="faq-icon-toggle" viewBox="0 0 24 24" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </summary>
                    <div class="faq-content">
                        Yes! Our curriculum is designed to support complete beginners. We start from the absolute
                        basics, including Swar Sadhana, voice culture, and basic rhythm controls before moving to
                        raags.
                    </div>
                </details>

                <!-- Q2 -->
                <details class="faq-item reveal-scale" style="transition-delay: 0.10s;">
                    <summary class="faq-summary">
                        Do I need previous music knowledge?
                        <svg class="faq-icon-toggle" viewBox="0 0 24 24" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </summary>
                    <div class="faq-content">
                        No previous knowledge is required. We structure our learning journey from the Foundation
                        Level so that anyone with a passion for music can learn successfully.
                    </div>
                </details>

                <!-- Q3 -->
                <details class="faq-item reveal-scale" style="transition-delay: 0.20s;">
                    <summary class="faq-summary">
                        Which age groups can learn?
                        <svg class="faq-icon-toggle" viewBox="0 0 24 24" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </summary>
                    <div class="faq-content">
                        We teach students of almost all age groups, including Children, Teenagers, Adults, Working
                        Professionals, and Senior Learners. The curriculum is tailored appropriately for each group.
                    </div>
                </details>

                <!-- Q4 -->
                <details class="faq-item reveal-scale" style="transition-delay: 0.30s;">
                    <summary class="faq-summary">
                        Are classes live?
                        <svg class="faq-icon-toggle" viewBox="0 0 24 24" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </summary>
                    <div class="faq-content">
                        Yes, all classes at Harita Music Academy are live and interactive, conducted online by
                        academically qualified music faculty. We do not use pre-recorded video lessons for core
                        learning.
                    </div>
                </details>

                <!-- Q5 -->
                <details class="faq-item reveal-scale" style="transition-delay: 0.00s;">
                    <summary class="faq-summary">
                        Are recordings available?
                        <svg class="faq-icon-toggle" viewBox="0 0 24 24" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </summary>
                    <div class="faq-content">
                        Yes, class session summaries and study guidelines are logged. You will have access to
                        digital study materials and support guides to help you practice in between your live
                        sessions.
                    </div>
                </details>

                <!-- Q6 -->
                <details class="faq-item reveal-scale" style="transition-delay: 0.10s;">
                    <summary class="faq-summary">
                        Can I reschedule classes?
                        <svg class="faq-icon-toggle" viewBox="0 0 24 24" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </summary>
                    <div class="faq-content">
                        Yes! Using your personal student dashboard portal, you can reschedule sessions and manage
                        calendar conflicts based on trainer availability.
                    </div>
                </details>

                <!-- Q7 -->
                <details class="faq-item reveal-scale" style="transition-delay: 0.20s;">
                    <summary class="faq-summary">
                        Do you teach international students?
                        <svg class="faq-icon-toggle" viewBox="0 0 24 24" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </summary>
                    <div class="faq-content">
                        Absolutely. We build a global community of music learners and offer flexible schedules to
                        accommodate different time zones for students residing outside India.
                    </div>
                </details>

                <!-- Q8 -->
                <details class="faq-item reveal-scale" style="transition-delay: 0.30s;">
                    <summary class="faq-summary">
                        How does the demo class work?
                        <svg class="faq-icon-toggle" viewBox="0 0 24 24" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </summary>
                    <div class="faq-content">
                        Once you book a demo for ₹499, you'll attend a live 45-minute online class. The educator
                        will understand your goals, assess your baseline, and recommend the appropriate courses and
                        plans.
                    </div>
                </details>

                <!-- Q9 -->
                <details class="faq-item reveal-scale" style="transition-delay: 0.00s;">
                    <summary class="faq-summary">
                        Which course should I choose?
                        <svg class="faq-icon-toggle" viewBox="0 0 24 24" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </summary>
                    <div class="faq-content">
                        During your 45-minute live demo class, our qualified educator will assess your voice or
                        musical aptitude and suggest whether Hindustani Vocal, Bollywood, Keyboard, Piano, Tabla, or
                        Harmonium fits your profile best.
                    </div>
                </details>

                <!-- Q10 -->
                <details class="faq-item reveal-scale" style="transition-delay: 0.10s;">
                    <summary class="faq-summary">
                        How do I enrol?
                        <svg class="faq-icon-toggle" viewBox="0 0 24 24" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </summary>
                    <div class="faq-content">
                        You can initiate enrolment directly by booking a demo class. After the demo, you can
                        choose a 1-month or 3-month fee structure, make payment, and receive your portal access
                        credentials.
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- 13. Final Call to Action / Lifestyle Banner (Snapshot 4 Lifestyle Banner) -->
    <!-- 13. Final Call to Action / Lifestyle Banner (Snapshot 4 Lifestyle Banner Redesigned) -->
    <section class="lifestyle-banner reveal" id="contact">
        <div class="container">
            <div class="lifestyle-banner-grid">

                <!-- Left Column: Content & Features Row -->
                <div class="lifestyle-content-col">
                    <span class="lifestyle-badge">JOIN THE HARITA LEGACY</span>
                    <h3 class="lifestyle-title">More Than Learning,<br>It's a Musical Lifestyle.</h3>
                    <p class="lifestyle-desc">Join HMA and be a part of a legacy that nurtures talent, builds
                        confidence, and creates true musicians.</p>

                    <div class="lifestyle-features-row">
                        <!-- Feature 1 -->
                        <div class="lifestyle-feature-inline">
                            <div class="lifestyle-feature-icon-wrapper">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="lifestyle-feature-inline-text">
                                <span class="feat-title">Live Classes</span>
                                <span class="feat-desc">1-on-1 Attention</span>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="lifestyle-feature-inline">
                            <div class="lifestyle-feature-icon-wrapper">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="lifestyle-feature-inline-text">
                                <span class="feat-title">Top Mentors</span>
                                <span class="feat-desc">Expert Guidance</span>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="lifestyle-feature-inline">
                            <div class="lifestyle-feature-icon-wrapper">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path
                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                    </path>
                                </svg>
                            </div>
                            <div class="lifestyle-feature-inline-text">
                                <span class="feat-title">Global Reach</span>
                                <span class="feat-desc">Learn Anywhere</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Dedicated High-Conversion Action Box -->
                <div class="lifestyle-action-col">
                    <div class="lifestyle-action-card">
                        <h4 class="action-card-header">Book Your Live Session</h4>
                        <p class="action-card-sub">Choose your slot and start learning live from anywhere in the
                            world.</p>

                        <div class="action-card-buttons">
                            <a href="#trial" class="btn btn-gold action-btn-primary">
                                Book Demo
                            </a>
                            <a href="https://wa.me/+918796823332?text=Hi%2C%20I%20would%20like%20to%20learn%20more%20about%20music%20classes%20at%20Harita%20Music%20Academy."
                                target="_blank" class="btn-whatsapp-outline-dark">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.965C16.528 2.056 14.1 1.033 11.996 1.033c-5.432 0-9.855 4.37-9.859 9.802-.001 1.76.478 3.479 1.388 5.048l-.999 3.648 3.73-.974zm12.355-6.735c-.3-.15-1.776-.875-2.036-.971-.26-.096-.45-.144-.64.15-.19.29-.735.91-.9.1.096-.192.19-.382.19-.68 0-.298-.15-.597-.3-.695-.3-.098-.598-.1-.787.1-.19.199-.38.399-.38.749 0 .349.099.699.25.998.15.299.3.599.45.898a12.56 12.56 0 0 0 3.25 2.879c.81.599 1.449.849 1.999.749.55-.1 1.776-.724 2.025-1.424.25-.7.25-1.299.175-1.424-.075-.125-.275-.199-.575-.349z" />
                                </svg>
                                Chat on WhatsApp
                            </a>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </section>

    <style>
        /* Modal Styles */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-backdrop.show {
            display: flex;
            opacity: 1;
        }

        .modal {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal-backdrop.show .modal {
            transform: translateY(0);
        }

        @keyframes slideInUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <script>
        let autoSelectedPlan = null;
        let autoSelectedProgramme = null;

        function selectPlanAndScroll(type, programme) {
            autoSelectedPlan = type;
            autoSelectedProgramme = programme;
            
            // Scroll to #trial section
            const trialSection = document.getElementById('trial');
            if(trialSection) {
                trialSection.scrollIntoView({ behavior: 'smooth' });
            }
        }

        const programmesData = {
            'group': [
                'Musical Foundation',
                'Musical Development'
            ],
            'individual': [
                'Musical Foundation (Essential)',
                'Musical Foundation (Accelerated)',
                'Musical Development (Essential)',
                'Musical Development (Accelerated)',
                'Musical Transformation (Advance)',
                'Musical Transformation (Signature)'
            ]
        };

        function updateProgrammeOptions(selectedProg = '') {
            const planType = document.getElementById('demo_plan_type').value;
            const progSelect = document.getElementById('demo_programme');
            
            progSelect.innerHTML = '<option value="">Select Programme...</option>';
            
            if (planType && programmesData[planType]) {
                programmesData[planType].forEach(prog => {
                    const opt = document.createElement('option');
                    opt.value = prog;
                    opt.textContent = prog;
                    if(prog === selectedProg) {
                        opt.selected = true;
                    }
                    progSelect.appendChild(opt);
                });
            }
        }

        function openDemoModal() {
            const modal = document.getElementById('publicDemoModal');

            // Apply auto-selected plan type and programme if available
            if (autoSelectedPlan && autoSelectedProgramme) {
                const planSelect = document.getElementById('demo_plan_type');
                if (planSelect) {
                    planSelect.value = autoSelectedPlan;
                    updateProgrammeOptions(autoSelectedProgramme);
                }
            }

            // Pre-fill date and time based on calendar selection
            if (typeof selectedDate !== 'undefined' && typeof selectedSlotText !== 'undefined') {
                const yyyy = selectedDate.getFullYear();
                const mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
                const dd = String(selectedDate.getDate()).padStart(2, '0');
                const dateStr = `${yyyy}-${mm}-${dd}`;

                const timeMatch = selectedSlotText.match(/(\d+):(\d+)\s(AM|PM)/i);
                if (timeMatch) {
                    let hours = parseInt(timeMatch[1], 10);
                    const mins = timeMatch[2];
                    const ampm = timeMatch[3].toUpperCase();

                    if (ampm === 'PM' && hours < 12) hours += 12;
                    if (ampm === 'AM' && hours === 12) hours = 0;

                    const timeStr = hours.toString().padStart(2, '0') + ':' + mins;

                    const dateInput = document.getElementById('demo_preferred_date');
                    const timeSelect = document.getElementById('demo_preferred_time');
                    const datetimeRow = document.getElementById('demo_datetime_row');

                    if (dateInput && timeSelect) {
                        dateInput.value = dateStr;
                        timeSelect.value = timeStr;
                        if (datetimeRow) {
                            datetimeRow.style.display = 'none';
                        }
                    }
                }
            } else {
                // Ensure it is visible if no calendar selection (fallback)
                const datetimeRow = document.getElementById('demo_datetime_row');
                if (datetimeRow) {
                    datetimeRow.style.display = 'grid';
                }
            }

            modal.style.display = 'flex';
            // Trigger reflow to restart animation
            void modal.offsetWidth;
            modal.classList.add('show');
        }

        function closeDemoModal() {
            const modal = document.getElementById('publicDemoModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300); // match transition duration
        }

        // Optional: Auto-hide the success toast after 5 seconds
        const toast = document.getElementById('demoSuccessToast');
        if (toast) {
            setTimeout(() => {
                toast.style.display = 'none';
            }, 5000);
        }
    </script>
@endsection
