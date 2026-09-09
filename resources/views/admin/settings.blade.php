@extends('layouts.admin')

@section('title', 'वेबसाइट सेटिंग्स (Website Settings)')
@section('header_title', 'वेबसाइट सेटिंग्स एवं कार्यालय प्रबंधन (Global Settings)')

@section('content')
<style>
    .settings-tab-container {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 32px;
    }
    @media(max-width: 992px) {
        .settings-tab-container {
            grid-template-columns: 1fr;
        }
    }
    .settings-tabs {
        display: flex;
        flex-direction: column;
        gap: 8px;
        background: white;
        padding: 20px;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(226, 232, 240, 0.8);
        height: fit-content;
    }
    .tab-btn {
        background: none;
        border: none;
        padding: 12px 18px;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-medium);
        text-align: left;
        border-radius: var(--border-radius-md);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .tab-btn i {
        font-size: 16px;
        width: 20px;
        text-align: center;
        color: var(--text-light);
        transition: var(--transition);
    }
    .tab-btn.active, .tab-btn:hover {
        background: var(--primary-light);
        color: var(--primary);
    }
    .tab-btn.active i, .tab-btn:hover i {
        color: var(--primary);
    }
    .tab-content {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 32px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .office-card {
        background: #fafafa;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--border-radius-md);
        padding: 24px;
        margin-bottom: 24px;
        position: relative;
        transition: var(--transition);
    }
    .office-card:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.05);
    }
    .btn-remove-office {
        position: absolute;
        top: 20px;
        right: 20px;
        color: #ef4444;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
        padding: 6px;
        border-radius: 50%;
        transition: var(--transition);
    }
    .btn-remove-office:hover {
        background: #fef2f2;
        transform: scale(1.1);
    }
</style>

<form action="{{ url('admin/settings/store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="settings-tab-container">
        <!-- Tabs selection -->
        <div class="settings-tabs">
            <button type="button" class="tab-btn active" onclick="switchTab('general', this)"><i class="fa-solid fa-gear"></i> सामान्य सेटिंग्स</button>
            <button type="button" class="tab-btn" onclick="switchTab('contact', this)"><i class="fa-solid fa-phone"></i> सम्पर्क सेटिंग्स</button>
            <button type="button" class="tab-btn" onclick="switchTab('social', this)"><i class="fa-solid fa-share-nodes"></i> सोशल मीडिया</button>
            <button type="button" class="tab-btn" onclick="switchTab('smtp', this)"><i class="fa-solid fa-envelope"></i> SMTP मेल</button>
            <button type="button" class="tab-btn" onclick="switchTab('offices', this)"><i class="fa-solid fa-building-user"></i> कार्यालय सूची (Offices)</button>
        </div>

        <!-- Tab content boxes -->
        <div>
            <!-- Tab: General Settings -->
            <div class="tab-content" id="tab-general">
                <h3 style="font-size:16px; font-weight: 700; margin-bottom:24px; border-bottom:1.5px solid #f1f5f9; padding-bottom:12px; color: var(--text-dark);"><i class="fa-solid fa-gear" style="color:var(--primary); margin-right:8px;"></i>सामान्य वेबसाइट सेटिंग्स (General Settings)</h3>
                
                <div class="form-group">
                    <label class="form-label">वेबसाइट का नाम (Website Name)</label>
                    <input type="text" name="website_name" class="form-control-custom" value="{{ App\Models\Setting::get('website_name') }}">
                </div>

                <div class="row-flex">
                    <div class="form-group">
                        <label class="form-label">लोगो यूआरएल / फ़ाइल (Logo)</label>
                        <input type="text" name="logo" id="logo_url_input" class="form-control-custom" value="{{ App\Models\Setting::get('logo') }}" placeholder="/images/logo.png">
                        <div style="margin-top:6px;">
                            <input type="file" name="logo_file" class="form-control-custom" accept="image/*" style="padding:6px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">फेविकॉन यूआरएल / फ़ाइल (Favicon)</label>
                        <input type="text" name="favicon" class="form-control-custom" value="{{ App\Models\Setting::get('favicon') }}" placeholder="/favicon.ico">
                        <div style="margin-top:6px;">
                            <input type="file" name="favicon_file" class="form-control-custom" accept="image/*" style="padding:6px;">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">टैगलाइन (Tagline)</label>
                    <input type="text" name="site_tagline" class="form-control-custom" value="{{ App\Models\Setting::get('site_tagline', 'अहिंसा परमो धर्मः | जीयो और जीने दो') }}">
                </div>
            </div>

            <!-- Tab: Contact Settings -->
            <div class="tab-content" id="tab-contact" style="display:none;">
                <h3 style="font-size:16px; font-weight: 700; margin-bottom:24px; border-bottom:1.5px solid #f1f5f9; padding-bottom:12px; color: var(--text-dark);"><i class="fa-solid fa-phone" style="color:var(--primary); margin-right:8px;"></i>सम्पर्क सेटिंग्स (Contact Settings)</h3>
                
                <div class="row-flex">
                    <div class="form-group">
                        <label class="form-label">मोबाइल नंबर (Mobile)</label>
                        <input type="text" name="contact_mobile" class="form-control-custom" value="{{ App\Models\Setting::get('contact_mobile') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ईमेल पता (Email)</label>
                        <input type="text" name="contact_email" class="form-control-custom" value="{{ App\Models\Setting::get('contact_email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">व्हाट्सएप लिंक (WhatsApp Link - https://wa.me/...)</label>
                    <input type="text" name="contact_whatsapp" class="form-control-custom" value="{{ App\Models\Setting::get('contact_whatsapp') }}">
                </div>
            </div>

            <!-- Tab: Social Media Settings -->
            <div class="tab-content" id="tab-social" style="display:none;">
                <h3 style="font-size:16px; font-weight: 700; margin-bottom:24px; border-bottom:1.5px solid #f1f5f9; padding-bottom:12px; color: var(--text-dark);"><i class="fa-solid fa-share-nodes" style="color:var(--primary); margin-right:8px;"></i>सोशल मीडिया सेटिंग्स (Social Links)</h3>
                
                <div class="row-flex">
                    <div class="form-group">
                        <label class="form-label">फेसबुक (Facebook)</label>
                        <input type="text" name="social_facebook" class="form-control-custom" value="{{ App\Models\Setting::get('social_facebook') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">इंस्टाग्राम (Instagram)</label>
                        <input type="text" name="social_instagram" class="form-control-custom" value="{{ App\Models\Setting::get('social_instagram') }}">
                    </div>
                </div>

                <div class="row-flex">
                    <div class="form-group">
                        <label class="form-label">यूट्यूब चैनल (YouTube Channel)</label>
                        <input type="text" name="social_youtube" class="form-control-custom" value="{{ App\Models\Setting::get('social_youtube') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">टेलीग्राम (Telegram)</label>
                        <input type="text" name="social_telegram" class="form-control-custom" value="{{ App\Models\Setting::get('social_telegram') }}">
                    </div>
                </div>
            </div>

            <!-- Tab: SMTP configuration -->
            <div class="tab-content" id="tab-smtp" style="display:none;">
                <h3 style="font-size:16px; font-weight: 700; margin-bottom:24px; border-bottom:1.5px solid #f1f5f9; padding-bottom:12px; color: var(--text-dark);"><i class="fa-solid fa-envelope" style="color:var(--primary); margin-right:8px;"></i>SMTP सर्वर सेटिंग्स (Mail Setup)</h3>
                
                <div class="row-flex">
                    <div class="form-group">
                        <label class="form-label">SMTP Host</label>
                        <input type="text" name="smtp_host" class="form-control-custom" value="{{ App\Models\Setting::get('smtp_host') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">SMTP Port</label>
                        <input type="text" name="smtp_port" class="form-control-custom" value="{{ App\Models\Setting::get('smtp_port') }}">
                    </div>
                </div>

                <div class="row-flex">
                    <div class="form-group">
                        <label class="form-label">SMTP Username</label>
                        <input type="text" name="smtp_username" class="form-control-custom" value="{{ App\Models\Setting::get('smtp_username') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">SMTP Password</label>
                        <input type="password" name="smtp_password" class="form-control-custom" value="{{ App\Models\Setting::get('smtp_password') }}">
                    </div>
                </div>
            </div>

            <!-- Tab: Offices JSON list builder -->
            <div class="tab-content" id="tab-offices" style="display:none;">
                <h3 style="font-size:16px; font-weight: 700; margin-bottom:24px; border-bottom:1.5px solid #f1f5f9; padding-bottom:12px; color: var(--text-dark);"><i class="fa-solid fa-building-user" style="color:var(--primary); margin-right:8px;"></i>कार्यालय सूची (Multiple Offices)</h3>
                
                <!-- We will render the offices JSON in a hidden textarea and build it dynamically via JS -->
                <textarea name="offices" id="officesJson" style="display:none;">{{ json_encode(App\Models\Setting::get('offices', []), JSON_UNESCAPED_UNICODE) }}</textarea>
                
                <div id="offices-container">
                    <!-- Office cards inserted here dynamically -->
                </div>

                <button type="button" class="btn-primary" onclick="addOfficeCard()" style="background:#475569; margin-bottom: 20px;"><i class="fa-solid fa-plus"></i> कार्यालय जोड़ें (Add Office)</button>
            </div>

            <!-- Global Submit -->
            <div style="margin-top:20px;">
                <button type="submit" class="btn-primary" onclick="prepareOfficesJson()"><i class="fa-solid fa-floppy-disk"></i> सभी सेटिंग्स सहेजें (Save All Settings)</button>
            </div>
        </div>
    </div>
</form>

<script>
    function switchTab(tabName, btnElement) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.style.display = 'none';
        });
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Show selected tab
        const target = document.getElementById('tab-' + tabName);
        if (target) {
            target.style.display = 'block';
        }
        
        // Find corresponding button
        if (btnElement) {
            btnElement.classList.add('active');
        }
    }

    // Dynamic office builder logic
    let offices = [];
    try {
        offices = JSON.parse(document.getElementById('officesJson').value || '[]');
    } catch(e) {
        offices = [];
    }

    function renderOffices() {
        const container = document.getElementById('offices-container');
        if (!container) return;
        container.innerHTML = '';

        offices.forEach((office, index) => {
            const card = document.createElement('div');
            card.className = 'office-card';
            card.innerHTML = `
                <button type="button" class="btn-remove-office" onclick="removeOfficeCard(${index})"><i class="fa-solid fa-trash"></i></button>
                <div class="form-group">
                    <label class="form-label">कार्यालय नाम (Office Name)</label>
                    <input type="text" class="form-control-custom office-name" value="${office.name || ''}" placeholder="उदा: प्रधान कार्यालय - दिल्ली">
                </div>
                <div class="form-group">
                    <label class="form-label">कार्यालय पता (Address)</label>
                    <input type="text" class="form-control-custom office-address" value="${office.address || ''}" placeholder="उदा: चांदनी चौक, दिल्ली">
                </div>
                <div class="row-flex" style="margin-bottom:0;">
                    <div class="form-group">
                        <label class="form-label">मोबाइल नंबर (Mobile)</label>
                        <input type="text" class="form-control-custom office-mobile" value="${office.mobile || ''}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ईमेल (Email)</label>
                        <input type="text" class="form-control-custom office-email" value="${office.email || ''}">
                    </div>
                </div>
                <div class="form-group" style="margin-top:12px; margin-bottom:0;">
                    <label class="form-label">गूगल मैप लिंक (Google Map Link)</label>
                    <input type="text" class="form-control-custom office-map" value="${office.google_map || ''}">
                </div>
            `;
            container.appendChild(card);
        });
    }

    function addOfficeCard() {
        offices.push({ name: '', address: '', mobile: '', email: '', google_map: '' });
        renderOffices();
    }

    function removeOfficeCard(index) {
        offices.splice(index, 1);
        renderOffices();
    }

    function prepareOfficesJson() {
        const list = [];
        const cards = document.querySelectorAll('.office-card');
        
        cards.forEach(card => {
            list.push({
                name: card.querySelector('.office-name').value,
                address: card.querySelector('.office-address').value,
                mobile: card.querySelector('.office-mobile').value,
                email: card.querySelector('.office-email').value,
                google_map: card.querySelector('.office-map').value,
            });
        });

        document.getElementById('officesJson').value = JSON.stringify(list);
    }

    // Initial render
    renderOffices();
</script>
@endsection
