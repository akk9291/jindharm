@extends('layouts.admin')

@section('title', 'दैनिक सुविचार आर्काइव (Daily Quotes Archive)')
@section('header_title', 'दैनिक सुविचार एवं अमृत वचन प्रबंधन (Daily Spiritual Quotes & Archive)')

@section('content')
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 1.6fr 1.4fr;
        gap: 28px;
    }
    @media(max-width: 1200px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }
    .quote-badge-featured {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .quote-preset-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 11.5px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .quote-preset-btn:hover {
        background: #e2e8f0;
        border-color: #94a3b8;
    }
</style>

<div class="grid-container">
    <!-- Left: Quotes Archive List -->
    <div class="panel-card">
        <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div class="panel-title"><i class="fa-solid fa-quote-left"></i> सुविचार सूची (Total: {{ $quotes->total() }})</div>
            
            <!-- Filter / Search form -->
            <form action="{{ url('admin/quotes') }}" method="GET" style="display:flex; gap:8px; align-items:center;">
                <select name="category" class="form-control-custom" style="padding:4px 8px; font-size:12.5px;" onchange="this.form.submit()">
                    <option value="">सभी श्रेणियाँ (All)</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="खोजें..." class="form-control-custom" style="padding:4px 8px; font-size:12.5px; width:130px;">
                <button type="submit" class="btn-primary" style="padding:5px 10px; font-size:12px;"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>चित्र</th>
                        <th>दिनांक / श्रेणी</th>
                        <th>सुविचार संदेश</th>
                        <th>रचयिता</th>
                        <th>क्रिया</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotes as $q)
                        <tr style="{{ $q->is_featured ? 'background:#fffbeb;' : '' }}">
                            <td>
                                <img src="{{ asset($q->image) }}" style="width:46px; height:46px; object-fit:cover; border-radius:6px; border: 1.5px solid #d9c39e;">
                            </td>
                            <td>
                                <div style="font-weight:700; font-size:13px; color:var(--text-dark);">
                                    {{ $q->quote_date ? $q->quote_date->format('d M, Y') : '-' }}
                                </div>
                                <span class="badge" style="background:#e0e7ff; color:#3730a3; font-size:10.5px;">{{ $q->category ?: 'अहिंसा' }}</span>
                                @if($q->is_featured)
                                    <div class="mt-1"><span class="quote-badge-featured"><i class="fa-solid fa-star"></i> आज का मुख्य</span></div>
                                @endif
                            </td>
                            <td style="max-width: 240px;">
                                <div style="font-size:13px; font-weight:500; color:var(--text-dark); line-height:1.4;">
                                    "{{ Str::limit($q->getLocalized('quote_text'), 65) }}"
                                </div>
                                <small style="color:var(--text-light); font-size:11px;">{{ $q->getLocalized('title') }}</small>
                            </td>
                            <td>
                                <div style="font-size:12.5px; font-weight:600; color:#85380c;">
                                    {{ Str::limit($q->getLocalized('author'), 25) }}
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; gap:4px; align-items:center;">
                                    @if(!$q->is_featured)
                                        <a href="{{ url('admin/quotes/feature/'.$q->id) }}" class="btn-action" title="आज का मुख्य संदेश बनाएं" style="background:#fef3c7; color:#92400e;">
                                            <i class="fa-solid fa-star"></i>
                                        </a>
                                    @endif
                                    <button onclick="editQuote({{ json_encode($q) }})" class="btn-action btn-action-edit" title="संपादित करें">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a href="{{ url('admin/quotes/delete/'.$q->id) }}" onclick="return confirm('क्या आप इस सुविचार को मिटाना चाहते हैं?')" class="btn-action btn-action-delete" title="मिटाएं">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:var(--text-light); padding:30px;">कोई सुविचार दर्ज नहीं है।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotes->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>

    <!-- Right: Add / Edit Form -->
    <div class="form-section">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-circle-plus"></i> <span id="form-title">नया सुविचार जोड़ें</span></div>
        </div>
        <form action="{{ url('admin/quotes/store') }}" method="POST" enctype="multipart/form-data" id="quoteForm">
            @csrf
            <input type="hidden" name="id" id="quote_id">
            
            <div class="row-flex">
                <div class="form-group">
                    <label class="form-label">दिनांक (Date) <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="quote_date" id="quote_date" class="form-control-custom" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">श्रेणी (Category)</label>
                    <input type="text" name="category" id="quote_category" class="form-control-custom" placeholder="उदा: अहिंसा, क्षमा, संयम, स्वाध्याय" list="categoryList">
                    <datalist id="categoryList">
                        <option value="अहिंसा">
                        <option value="सत्य">
                        <option value="संयम">
                        <option value="तप एवं त्याग">
                        <option value="उत्तम क्षमा">
                        <option value="स्वाध्याय">
                        <option value="अनेकांतवाद">
                        <option value="जिन भक्ति">
                    </datalist>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">शीर्षक (Title - Hindi)</label>
                <input type="text" name="title_hi" id="quote_title_hi" class="form-control-custom" placeholder="उदा: आज का आध्यात्मिक संदेश" value="आज का आध्यात्मिक संदेश">
            </div>

            <div class="form-group">
                <label class="form-label">सुविचार संदेश (Quote Text - Hindi) <span style="color:#ef4444;">*</span></label>
                <textarea name="quote_text_hi" id="quote_text_hi" class="form-control-custom" rows="3" placeholder="पवित्र जैन अमृत वचन दर्ज करें..." required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Quote Text (English - Optional)</label>
                <textarea name="quote_text_en" id="quote_text_en" class="form-control-custom" rows="2" placeholder="Spiritual quote in English..."></textarea>
            </div>

            <div class="row-flex">
                <div class="form-group">
                    <label class="form-label">रचयिता / पूज्य संत (Author - Hindi)</label>
                    <input type="text" name="author_hi" id="quote_author_hi" class="form-control-custom" placeholder="उदा: भगवान महावीर स्वामी" value="भगवान महावीर स्वामी">
                </div>
                <div class="form-group">
                    <label class="form-label">Author (English)</label>
                    <input type="text" name="author_en" id="quote_author_en" class="form-control-custom" placeholder="Lord Mahavira">
                </div>
            </div>

            <!-- Image Selection & Upload -->
            <div style="background:#fafafa; border:1.5px solid #e2e8f0; border-radius:8px; padding:16px; margin-bottom:16px;">
                <label class="form-label" style="font-weight:700; color:var(--primary);"><i class="fa-solid fa-image me-1"></i> सुविचार चित्र (Quote Image)</label>
                
                <div class="form-group" style="margin-bottom:10px;">
                    <label class="form-label" style="font-size:12.5px;">फ़ाइल अपलोड करें:</label>
                    <input type="file" name="image_file" class="form-control-custom" accept="image/*" onchange="previewQuoteImg(this)">
                </div>

                <div class="form-group" style="margin-bottom:10px;">
                    <label class="form-label" style="font-size:12.5px;">या चित्र यूआरएल / पाथ:</label>
                    <input type="text" name="image" id="quote_image_input" class="form-control-custom" value="/images/jain/muni_vidyasagar.jpg" oninput="document.getElementById('form_img_preview').src = this.value">
                </div>

                <!-- Presets -->
                <div style="margin-bottom:12px;">
                    <small style="color:#64748b; font-weight:600; display:block; margin-bottom:6px;">त्वरित चयन (Quick Jain Presets):</small>
                    <div style="display:flex; gap:6px; flex-wrap:wrap;">
                        <button type="button" class="quote-preset-btn" onclick="setPresetImg('/images/jain/muni_vidyasagar.jpg')">🧘 मुनि विद्यासागर जी</button>
                        <button type="button" class="quote-preset-btn" onclick="setPresetImg('/images/jain/muni_pravachan.jpg')">🎙️ मुनि प्रवचन</button>
                        <button type="button" class="quote-preset-btn" onclick="setPresetImg('/images/jain/tirthankara_idol.jpg')">🛕 तीर्थंकर प्रतिमा</button>
                        <button type="button" class="quote-preset-btn" onclick="setPresetImg('/images/jain/temple_shikhar.jpg')">🚩 जिन मंदिर</button>
                        <button type="button" class="quote-preset-btn" onclick="setPresetImg('/images/jain/granth_manuscript.jpg')">📜 पवित्र ग्रन्थ</button>
                    </div>
                </div>

                <!-- Preview Box -->
                <div style="display:flex; align-items:center; gap:12px;">
                    <img id="form_img_preview" src="/images/jain/muni_vidyasagar.jpg" style="width:70px; height:70px; object-fit:cover; border-radius:6px; border:1.5px solid #cbd5e1;">
                    <span style="font-size:12px; color:#64748b;">यह चित्र कार्ड और विवरण में दिखाई देगा।</span>
                </div>
            </div>

            <div style="display:flex; gap:20px; align-items:center; margin-bottom:20px;">
                <label style="display:flex; align-items:center; gap:6px; font-size:13.5px; cursor:pointer;">
                    <input type="checkbox" name="is_active" id="quote_is_active" value="1" checked>
                    <span>सक्रिय (Active)</span>
                </label>
                <label style="display:flex; align-items:center; gap:6px; font-size:13.5px; cursor:pointer;">
                    <input type="checkbox" name="is_featured" id="quote_is_featured" value="1">
                    <span style="color:#92400e; font-weight:600;"><i class="fa-solid fa-star text-warning"></i> आज का मुख्य सुविचार (Set as Featured)</span>
                </label>
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn-primary" style="flex:1;"><i class="fa-solid fa-floppy-disk"></i> सहेजें (Save Quote)</button>
                <button type="button" class="btn-primary" onclick="resetQuoteForm()" style="background:#64748b;"><i class="fa-solid fa-rotate-left"></i> रीसेट</button>
            </div>
        </form>
    </div>
</div>

<script>
    function setPresetImg(url) {
        document.getElementById('quote_image_input').value = url;
        document.getElementById('form_img_preview').src = url;
    }

    function previewQuoteImg(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('form_img_preview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function editQuote(data) {
        document.getElementById('form-title').innerText = 'सुविचार संपादित करें (Edit Quote)';
        document.getElementById('quote_id').value = data.id;
        document.getElementById('quote_date').value = data.quote_date ? data.quote_date.substring(0, 10) : '';
        document.getElementById('quote_category').value = data.category || '';
        
        const title = data.title || {};
        document.getElementById('quote_title_hi').value = title.hi || title.en || '';

        const quoteText = data.quote_text || {};
        document.getElementById('quote_text_hi').value = quoteText.hi || quoteText.en || '';
        document.getElementById('quote_text_en').value = quoteText.en || '';

        const author = data.author || {};
        document.getElementById('quote_author_hi').value = author.hi || author.en || '';
        document.getElementById('quote_author_en').value = author.en || '';

        document.getElementById('quote_image_input').value = data.image || '';
        document.getElementById('form_img_preview').src = data.image || '/images/jain/muni_vidyasagar.jpg';

        document.getElementById('quote_is_active').checked = !!data.is_active;
        document.getElementById('quote_is_featured').checked = !!data.is_featured;

        window.scrollTo({ top: document.querySelector('.form-section').offsetTop - 100, behavior: 'smooth' });
    }

    function resetQuoteForm() {
        document.getElementById('form-title').innerText = 'नया सुविचार जोड़ें';
        document.getElementById('quoteForm').reset();
        document.getElementById('quote_id').value = '';
        document.getElementById('quote_date').value = new Date().toISOString().substring(0, 10);
        document.getElementById('quote_image_input').value = '/images/jain/muni_vidyasagar.jpg';
        document.getElementById('form_img_preview').src = '/images/jain/muni_vidyasagar.jpg';
    }
</script>
@endsection
