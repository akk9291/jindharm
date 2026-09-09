@extends('layouts.admin')

@section('title', 'संघ जानकारी एवं लाइव विहार स्थिति प्रबंधन')
@section('header_title', 'संघ जानकारी एवं लाइव विहार स्थिति प्रबंधन (Sangh Info & Real-Time Vihar)')

@section('content')
<style>
    .vihar-grid-layout {
        display: grid;
        grid-template-columns: 1.4fr 1.6fr;
        gap: 28px;
    }
    @media(max-width: 1100px) {
        .vihar-grid-layout {
            grid-template-columns: 1fr;
        }
    }
    .sangh-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--border-radius-lg);
        padding: 24px;
        box-shadow: var(--card-shadow);
        margin-bottom: 24px;
    }
    .sangh-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1.5px solid #f1f5f9;
    }
    .sangh-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sangh-card-title i {
        color: var(--primary);
    }
</style>

<form action="{{ url('admin/sangh-vihar/store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="vihar-grid-layout">
        
        <!-- LEFT COLUMN: Marquee, Event Title, Poster & Digital Links -->
        <div>
            
            <!-- 1. Top Marquee Announcement Bar -->
            <div class="sangh-card" style="border-color: #fde68a; background: #fffdf5;">
                <div class="sangh-card-header" style="border-bottom-color: #fef3c7;">
                    <div class="sangh-card-title" style="color: #92400e;">
                        <i class="fa-solid fa-bullhorn text-warning"></i> शीर्ष मार्की सूचना पट्टी (Top Marquee Announcement Bar)
                    </div>
                    <span class="badge" style="background:#fef3c7; color:#92400e; font-size:11px;">होमपेज शीर्ष पट्टी</span>
                </div>
                <p style="font-size: 12.5px; color: #78716c; margin-bottom: 12px;">
                    यह संदेश होमपेज के शीर्ष पर ऑटो-स्क्रॉलिंग मार्की पट्टी में प्रदर्शित होता है।
                </p>
                <div class="form-group" style="margin-bottom:0;">
                    <textarea name="marquee_text" class="form-control-custom" rows="3" style="background:#ffffff; border-color:#fcd34d;">{{ $settings['marquee_text'] ?? 'एक महत्वपूर्ण संदेश - सभी जैनतीर्थ पर पूर्ण अनुशासन व मर्यादा प्रार्थनीय हैं। सजग रहें, अगर किसी तीर्थ पर अनुशासन न दिखे, तो तुरन्त हमें अवगत करें' }}</textarea>
                </div>
            </div>

            <!-- 2. Event Title & Tirth Location -->
            <div class="sangh-card">
                <div class="sangh-card-header">
                    <div class="sangh-card-title">
                        <i class="fa-solid fa-om"></i> वर्षा योग एवं तीर्थ क्षेत्र (Event & Tirth Location)
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">आयोजन / वर्षा योग मुख्य शीर्षक (Section Main Title)</label>
                    <input type="text" name="vihar_section_title" class="form-control-custom" value="{{ $settings['vihar_section_title'] ?? '२८ वां अनेक कल्याणक भूमि वर्षा योग २०२६' }}" placeholder="उदा. २८ वां अनेक कल्याणक भूमि वर्षा योग २०२६">
                </div>

                <div class="form-group">
                    <label class="form-label">तीर्थ क्षेत्र का नाम एवं राज्य (Tirth Location)</label>
                    <input type="text" name="vihar_tirth_location" class="form-control-custom" value="{{ $settings['vihar_tirth_location'] ?? 'श्री पार्श्वनाथ दिगंबर जैन तीर्थ क्षेत्र भेलूपुर वाराणसी (उत्तर प्रदेश)' }}" placeholder="उदा. श्री पार्श्वनाथ दिगंबर जैन तीर्थ क्षेत्र भेलूपुर वाराणसी">
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">कल्याणक भूमि उप-शीर्षक (Tirth Subtitle / Specialty)</label>
                    <input type="text" name="vihar_tirth_subtitle" class="form-control-custom" value="{{ $settings['vihar_tirth_subtitle'] ?? 'गर्भ, जन्म, तप कल्याणक भूमि' }}" placeholder="उदा. गर्भ, जन्म, तप कल्याणक भूमि">
                </div>
            </div>

            <!-- 3. Poster Image Management -->
            <div class="sangh-card">
                <div class="sangh-card-header">
                    <div class="sangh-card-title">
                        <i class="fa-solid fa-image"></i> मुख्य वर्षा योग / संघ पोस्टर (Poster Image)
                    </div>
                </div>

                <div style="display: flex; gap: 18px; align-items: flex-start; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label class="form-label" style="font-size: 13px;">कंप्यूटर से नया पोस्टर अपलोड करें (Upload File):</label>
                            <input type="file" name="vihar_poster_image_file" class="form-control-custom" accept="image/*" onchange="previewViharPosterFile(this)">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 13px;">या पोस्टर यूआरएल दर्ज करें (Poster URL / Path):</label>
                            <input type="text" name="vihar_poster_image" id="vihar_poster_input_url" class="form-control-custom" value="{{ $settings['vihar_poster_image'] ?? '/images/jain/varsha_yog_poster_cropped.jpg' }}" placeholder="/images/jain/varsha_yog_poster_cropped.jpg">
                        </div>
                    </div>

                    <div style="width: 140px; text-align: center;">
                        <label class="form-label" style="font-size: 12px; color: #64748b;">पोस्टर पूर्वावलोकन:</label>
                        <div style="width: 140px; height: 140px; border-radius: 10px; border: 2px dashed #cbd5e1; overflow: hidden; background: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <img id="vihar_poster_img_preview" src="{{ asset($settings['vihar_poster_image'] ?? 'images/jain/varsha_yog_poster_cropped.jpg') }}" alt="Vihar Poster" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Digital Channels & Social Links -->
            <div class="sangh-card">
                <div class="sangh-card-header">
                    <div class="sangh-card-title">
                        <i class="fa-solid fa-share-nodes"></i> अधिकृत डिजिटल माध्यम व सोशल लिंक (Digital Channels)
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">वेबसाइट पता (Website URL)</label>
                    <input type="text" name="vihar_website_url" class="form-control-custom" value="{{ $settings['vihar_website_url'] ?? 'www.jindharma.com' }}">
                </div>

                <div class="form-group">
                    <label class="form-label">यूट्यूब चैनल विवरण (YouTube Channels)</label>
                    <input type="text" name="vihar_youtube_text" class="form-control-custom" value="{{ $settings['vihar_youtube_text'] ?? 'जैन धर्म वाणी, एवं जैन वर्ल्ड विद्या' }}">
                </div>

                <div class="form-group">
                    <label class="form-label">गूगल मैप्स लोकेशन लिंक (Google Map Direction Link)</label>
                    <input type="text" name="vihar_map_link" class="form-control-custom" value="{{ $settings['vihar_map_link'] ?? '' }}" placeholder="https://maps.google.com/?q=...">
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">डिजिटल लिंक / Linktree / QR कोड लिंक</label>
                    <input type="text" name="vihar_qr_link" class="form-control-custom" value="{{ $settings['vihar_qr_link'] ?? 'https://linktr.ee/Jindharmmatter' }}">
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Sangh Leadership, Real-time Vihar Status & Multi-Contacts -->
        <div>

            <!-- 1. Sangh Patronage & Leadership -->
            <div class="sangh-card">
                <div class="sangh-card-header">
                    <div class="sangh-card-title">
                        <i class="fa-solid fa-hands-praying"></i> पूज्य संघ सानिध्य एवं पावन आशीष (Sangh Patronage)
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">आशीष छांव (Ashish Chhav - महासमाधि धारक आचार्यश्री)</label>
                    <input type="text" name="vihar_ashish_chhav" class="form-control-custom" value="{{ $settings['vihar_ashish_chhav'] ?? 'महासमाधि धारक परम पूज्य आचार्यश्री 108 विद्यासागर जी महामुनिराज' }}">
                </div>

                <div class="form-group">
                    <label class="form-label">आशीर्वाद प्रदाता (Aashirwad Pradata - प.पू. आचार्यश्री)</label>
                    <input type="text" name="vihar_aashirwad_pradata" class="form-control-custom" value="{{ $settings['vihar_aashirwad_pradata'] ?? 'परम पूज्य आचार्यश्री 108 समयसागर जी महामुनिराज' }}">
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">पुण्यवर्धन सानिध्य (Punyavardhan Sanidhya - मुनिश्री गण)</label>
                    <input type="text" name="vihar_punyavardhan_sanidhya" class="form-control-custom" value="{{ $settings['vihar_punyavardhan_sanidhya'] ?? 'परम पूज्य मुनिश्री 108 धर्मसागर जी महाराज, परम पूज्य मुनिश्री 108 भावसागर जी महाराज' }}">
                </div>
            </div>

            <!-- 2. Real-time Live Vihar Status & Daily Routine -->
            <div class="sangh-card" style="border-color: #fed7aa; background: #fffdfa;">
                <div class="sangh-card-header" style="border-bottom-color: #ffedd5;">
                    <div class="sangh-card-title" style="color: #9a3412;">
                        <i class="fa-solid fa-location-dot text-danger"></i> लाइव विहार स्थिति एवं दैनिक चर्या (Real-Time Vihar Status)
                    </div>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="vihar_status_active" value="1" {{ ($settings['vihar_status_active'] ?? '1') == '1' ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary);">
                        <span class="badge" style="background:#dcfce7; color:#15803d; border:1px solid #bbf7d0;">लाइव विहार सक्रिय</span>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">🚶‍♂️ विहार दिशा (Vihar Direction)</label>
                    <input type="text" name="vihar_disha" class="form-control-custom" value="{{ $settings['vihar_disha'] ?? 'वाराणसी से अयोध्या जी की ओर' }}" placeholder="उदा. वाराणसी से अयोध्या जी की ओर">
                </div>

                <div class="form-group">
                    <label class="form-label">🌙 रात्रि विश्राम स्थल (Night Stay Location)</label>
                    <input type="text" name="vihar_ratri_vishram" class="form-control-custom" value="{{ $settings['vihar_ratri_vishram'] ?? 'दिगम्बर जैन धर्मशाला, जौनपुर' }}" placeholder="उदा. दिगम्बर जैन धर्मशाला, जौनपुर">
                </div>

                <div class="form-group">
                    <label class="form-label">🥣 आहार चर्या समय व स्थल (Aahar Charya Time & Location)</label>
                    <input type="text" name="vihar_aahar_charya" class="form-control-custom" value="{{ $settings['vihar_aahar_charya'] ?? 'प्रातः ०९:३० बजे, श्री आदिनाथ जिनालय परिसर' }}" placeholder="उदा. प्रातः ०९:३० बजे, श्री आदिनाथ जिनालय परिसर">
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">📍 वर्तमान प्रवास / पड़ाव (Current Stay / Halt)</label>
                    <input type="text" name="vihar_current_stay" class="form-control-custom" value="{{ $settings['vihar_current_stay'] ?? 'भेलूपुर जैन मन्दिर, वाराणसी' }}" placeholder="उदा. भेलूपुर जैन मन्दिर, वाराणसी">
                </div>
            </div>

            <!-- 3. Multi-Sampark Sutra (Organizer Directory) -->
            <div class="sangh-card">
                <div class="sangh-card-header">
                    <div class="sangh-card-title">
                        <i class="fa-solid fa-phone-volume"></i> बहु-संपर्क सूत्र (Multi Sampark Sutra / Contacts Directory)
                    </div>
                    <button type="button" class="btn-primary" onclick="addSanghContact()" style="padding: 6px 14px; font-size: 12px;">
                        <i class="fa-solid fa-plus"></i> नया संपर्क जोड़ें
                    </button>
                </div>

                <p style="font-size: 12.5px; color: #64748b; margin-bottom: 14px;">
                    होमपेज पर भक्तों व व्यवस्थापकों को सीधा कॉल करने के लिए संपर्क सूत्रों की सूची:
                </p>

                <textarea name="vihar_contacts" id="sanghContactsJson" style="display:none;">{{ is_array($settings['vihar_contacts'] ?? null) ? json_encode($settings['vihar_contacts'], JSON_UNESCAPED_UNICODE) : ($settings['vihar_contacts'] ?? '[]') }}</textarea>

                <div id="sangh-contacts-container">
                    <!-- Dynamic Contacts List -->
                </div>
            </div>

            <!-- Submit Button -->
            <div style="margin-top: 10px; margin-bottom: 30px;">
                <button type="submit" class="btn-primary" onclick="prepareSanghContactsJson()" style="width: 100%; padding: 16px; font-size: 15px; font-weight: 700;">
                    <i class="fa-solid fa-floppy-disk me-1"></i> संघ जानकारी एवं लाइव विहार स्थिति सहेजें (Save All Changes)
                </button>
            </div>

        </div>

    </div>
</form>

<script>
    // Poster Preview Helper
    function previewViharPosterFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('vihar_poster_img_preview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Dynamic Contacts Logic
    let sanghContacts = [];
    try {
        const raw = document.getElementById('sanghContactsJson').value;
        sanghContacts = JSON.parse(raw || '[]');
        if (typeof sanghContacts === 'string') {
            sanghContacts = JSON.parse(sanghContacts);
        }
    } catch(e) {
        sanghContacts = [
            { name: 'रामू भैया नौहटा', phone: '7879068125', role: 'व्यवस्थापक' },
            { name: 'राघव जैन भोपाल', phone: '6260151350', role: 'व्यवस्थापक' },
            { name: 'आर. सी. जैन', phone: '9415201372', role: 'संपर्क' },
            { name: 'विनोद कुमार जैन', phone: '7376895750', role: 'संपर्क' },
            { name: 'सौरभ कुमार जैन', phone: '9415222071', role: 'संपर्क' },
            { name: 'राहुल कुमार जैन', phone: '9984226644', role: 'संपर्क' },
            { name: 'संजय जैन', phone: '6388234656', role: 'संपर्क' },
        ];
    }

    function renderSanghContacts() {
        const container = document.getElementById('sangh-contacts-container');
        if (!container) return;
        container.innerHTML = '';

        sanghContacts.forEach((item, index) => {
            const row = document.createElement('div');
            row.className = 'sangh-contact-item';
            row.style.background = '#f8fafc';
            row.style.border = '1.5px solid #e2e8f0';
            row.style.borderRadius = '10px';
            row.style.padding = '12px 16px';
            row.style.marginBottom = '10px';
            row.style.display = 'flex';
            row.style.alignItems = 'center';
            row.style.gap = '12px';
            row.style.flexWrap = 'wrap';

            row.innerHTML = `
                <div style="flex: 1.5; min-width: 140px;">
                    <label style="font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 2px; display:block;">नाम एवं पद / शहर:</label>
                    <input type="text" class="form-control-custom sangh-name" value="${item.name || ''}" placeholder="उदा. रामू भैया नौहटा" style="padding: 7px 12px; font-size: 13.5px;">
                </div>
                <div style="flex: 1.2; min-width: 130px;">
                    <label style="font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 2px; display:block;">फ़ोन / मोबाइल:</label>
                    <input type="text" class="form-control-custom sangh-phone" value="${item.phone || ''}" placeholder="उदा. 7879068125" style="padding: 7px 12px; font-size: 13.5px;">
                </div>
                <div style="flex: 1; min-width: 110px;">
                    <label style="font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 2px; display:block;">दायित्व:</label>
                    <input type="text" class="form-control-custom sangh-role" value="${item.role || 'व्यवस्थापक'}" placeholder="उदा. व्यवस्थापक" style="padding: 7px 12px; font-size: 13.5px;">
                </div>
                <div style="margin-top: 18px;">
                    <button type="button" onclick="removeSanghContact(${index})" style="background:#fee2e2; color:#ef4444; border:none; border-radius:6px; padding:8px 12px; cursor:pointer; font-size:13px;" title="हटाएं">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(row);
        });
    }

    function addSanghContact() {
        sanghContacts.push({ name: '', phone: '', role: 'व्यवस्थापक' });
        renderSanghContacts();
    }

    function removeSanghContact(index) {
        sanghContacts.splice(index, 1);
        renderSanghContacts();
    }

    function prepareSanghContactsJson() {
        const list = [];
        const items = document.querySelectorAll('.sangh-contact-item');
        items.forEach(el => {
            const name = el.querySelector('.sangh-name').value.trim();
            const phone = el.querySelector('.sangh-phone').value.trim();
            const role = el.querySelector('.sangh-role').value.trim();
            if (name || phone) {
                list.push({ name, phone, role });
            }
        });
        document.getElementById('sanghContactsJson').value = JSON.stringify(list);
    }

    // Initial render
    renderSanghContacts();
</script>
@endsection
