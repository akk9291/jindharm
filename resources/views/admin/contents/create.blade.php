@extends('layouts.admin')

@section('title', isset($content) ? 'सामग्री संपादित करें (Edit Content)' : 'सामग्री जोड़ें (Add Content)')
@section('header_title', isset($content) ? 'सामग्री संपादक (Content Editor)' : 'नयी सामग्री जोड़ें (Add Content)')

@section('content')
<!-- Quill Rich Text Editor Stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<style>
    .editor-grid {
        display: grid;
        grid-template-columns: 1.85fr 1.15fr;
        gap: 28px;
    }
    @media(max-width: 1200px) {
        .editor-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Media Repeater Styling */
    .repeater-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: var(--border-radius-md);
        padding: 16px;
        margin-bottom: 14px;
        position: relative;
        transition: var(--transition);
    }
    .repeater-box:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .repeater-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e2e8f0;
    }
    .repeater-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .type-pill-group {
        display: inline-flex;
        background: #e2e8f0;
        padding: 3px;
        border-radius: 8px;
        gap: 4px;
    }
    .type-pill-btn {
        border: none;
        background: transparent;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        color: var(--text-medium);
        transition: all 0.2s;
    }
    .type-pill-btn.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    .btn-add-repeater {
        background: #fff7ed;
        color: var(--primary);
        border: 1.5px dashed var(--primary);
        font-weight: 600;
        font-size: 13px;
        padding: 10px 16px;
        border-radius: var(--border-radius-md);
        width: 100%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: var(--transition);
        margin-top: 8px;
    }
    .btn-add-repeater:hover {
        background: var(--primary);
        color: white;
        border-style: solid;
    }
    .btn-remove-row {
        background: #fee2e2;
        color: #dc2626;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        transition: all 0.2s;
    }
    .btn-remove-row:hover {
        background: #dc2626;
        color: white;
    }

    /* Modal Styling */
    .modal-tab-btn {
        padding: 8px 16px;
        border: none;
        background: transparent;
        border-bottom: 2px solid transparent;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: #fff7ed;
        border-radius: 6px 6px 0 0;
    }

    /* Quill Overrides */
    .ql-toolbar {
        border-top-left-radius: var(--border-radius-md) !important;
        border-top-right-radius: var(--border-radius-md) !important;
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
    }
    .ql-container {
        border-bottom-left-radius: var(--border-radius-md) !important;
        border-bottom-right-radius: var(--border-radius-md) !important;
        border-color: #cbd5e1 !important;
        font-family: 'Noto Sans Devanagari', 'Inter', sans-serif !important;
        font-size: 15px !important;
        min-height: 260px;
    }
    .ql-editor {
        min-height: 260px;
        line-height: 1.8;
    }
</style>

<form action="{{ url('admin/contents/store') }}" method="POST" id="contentForm">
    @csrf
    @if(isset($content))
        <input type="hidden" name="id" value="{{ $content->id }}">
    @endif

    <div class="editor-grid">
        <!-- Main Form Column (Left) -->
        <div>
            <!-- Basic Details -->
            <div class="panel-card" style="margin-bottom: 24px;">
                <h3 style="font-size:16px; font-weight: 700; margin-bottom:20px; border-bottom:1.5px solid #f1f5f9; padding-bottom:12px; color: var(--text-dark);">
                    <i class="fa-solid fa-file-invoice" style="color:var(--primary); margin-right:8px;"></i>प्राथमिक विवरण (Basic Details)
                </h3>
                
                <div class="form-group">
                    <label class="form-label">सामग्री शीर्षक (Title - Hindi Unicode) <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="title_hi" class="form-control-custom" value="{{ $content->title['hi'] ?? '' }}" placeholder="उदा: ॐ जय जिनेन्द्र आरती या समयसार प्रवचन" required>
                </div>

                <div class="row-flex">
                    <div class="form-group">
                        <label class="form-label">शीर्षक (Title - English)</label>
                        <input type="text" name="title_en" class="form-control-custom" value="{{ $content->title['en'] ?? '' }}" placeholder="Example: Om Jai Jinendra Aarti">
                    </div>
                    <div class="form-group">
                        <label class="form-label">लेखक / वक्ता / रचयिता (Author / Speaker)</label>
                        <input type="text" name="author_hi" class="form-control-custom" value="{{ $content->author['hi'] ?? '' }}" placeholder="उदा: पूज्य आचार्य श्री विद्यासागर जी महाराज">
                    </div>
                </div>

                <div class="row-flex">
                    <div class="form-group">
                        <label class="form-label">कंटेंट प्रकार (Content Type) <span style="color:#dc2626;">*</span></label>
                        <select name="content_type_id" class="form-control-custom" required>
                            <option value="">कंटेंट प्रकार चुनें</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ (isset($content) && $content->content_type_id == $type->id) ? 'selected' : '' }}>
                                    {{ $type->getLocalized('name') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">श्रेणी (Category)</label>
                        <select name="category_id" class="form-control-custom">
                            <option value="">श्रेणी चुनें (वैकल्पिक)</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (isset($content) && $content->category_id == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->getLocalized('name') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Description / WYSIWYG Rich Text Editor -->
            <div class="panel-card" style="margin-bottom: 24px;">
                <h3 style="font-size:16px; font-weight: 700; margin-bottom:20px; border-bottom:1.5px solid #f1f5f9; padding-bottom:12px; color: var(--text-dark);">
                    <i class="fa-solid fa-paragraph" style="color:var(--primary); margin-right:8px;"></i>सामग्री का विवरण एवं टेक्स्ट एडिटर (Content & Rich Editor)
                </h3>
                
                <div class="form-group">
                    <label class="form-label">संक्षिप्त विवरण (Short Description / सार)</label>
                    <textarea name="short_description_hi" class="form-control-custom" style="height:75px;" placeholder="सामग्री की मुख्य मुख्य बातें या संक्षिप्त सार लिखें...">{{ $content->short_description['hi'] ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">पूर्ण सामग्री / विवरण (Full Text & Body - Rich Text Editor)</label>
                        <span class="small text-muted"><i class="fa-solid fa-feather text-warning me-1"></i> यूनिकोड हिंदी समर्थित</span>
                    </div>
                    
                    <!-- Hidden field for form submit -->
                    <input type="hidden" name="full_description_hi" id="full_description_hi" value="{{ $content->full_description['hi'] ?? '' }}">
                    
                    <!-- Quill Rich Editor Container -->
                    <div id="quillEditorContainer">{!! $content->full_description['hi'] ?? '' !!}</div>
                </div>
            </div>

            <!-- 1. MULTIPLE VIDEOS REPEATER (YouTube & Self-Hosted) -->
            <div class="panel-card" style="margin-bottom: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h3 style="font-size:16px; font-weight: 700; margin-bottom:0; color: var(--text-dark);">
                        <i class="fa-solid fa-video" style="color:var(--primary); margin-right:8px;"></i>वीडियो संकलन (Multiple Videos - YouTube & Self-Hosted)
                    </h3>
                    <span class="badge" style="background:#fff7ed; color:var(--primary); font-size:12px; padding:4px 10px; border-radius:12px;">
                        यूट्यूब अथवा MP4 वीडियो
                    </span>
                </div>
                <p style="font-size:12.5px; color:var(--text-light); margin-bottom:16px;">
                    यहाँ आप एक या एक से अधिक यूट्यूब वीडियो अथवा सर्वर पर अपलोड की गई MP4 वीडियो जोड़ सकते हैं।
                </p>

                <div id="videosRepeaterList">
                    @php
                        $existingVideos = isset($content) && is_array($content->videos) ? $content->videos : [];
                    @endphp

                    @forelse($existingVideos as $index => $v)
                        @php
                            $vType = is_array($v) ? ($v['type'] ?? 'youtube') : (preg_match('/(?:youtube|youtu\.be)/', $v) ? 'youtube' : 'self_hosted');
                            $vUrl = is_array($v) ? ($v['url'] ?? '') : $v;
                            $vTitle = is_array($v) ? ($v['title'] ?? '') : '';
                        @endphp
                        <div class="repeater-box" data-index="{{ $index }}">
                            <div class="repeater-header">
                                <div class="repeater-title">
                                    <i class="fa-solid fa-circle-play text-danger"></i> वीडियो #{{ $index + 1 }}
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="type-pill-group">
                                        <button type="button" class="type-pill-btn {{ $vType === 'youtube' ? 'active' : '' }}" onclick="switchVideoType(this, 'youtube')">
                                            <i class="fa-brands fa-youtube text-danger me-1"></i> YouTube
                                        </button>
                                        <button type="button" class="type-pill-btn {{ $vType === 'self_hosted' ? 'active' : '' }}" onclick="switchVideoType(this, 'self_hosted')">
                                            <i class="fa-solid fa-server text-primary me-1"></i> Self-Hosted
                                        </button>
                                    </div>
                                    <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="videos[{{ $index }}][type]" class="video-type-input" value="{{ $vType }}">
                            
                            <div class="row-flex" style="margin-bottom: 10px;">
                                <div class="form-group" style="margin-bottom:0; flex:1;">
                                    <label class="form-label" style="font-size:12px;">वीडियो शीर्षक / भाग (उदा. भाग 1: मंगलाचरण)</label>
                                    <input type="text" name="videos[{{ $index }}][title]" class="form-control-custom" value="{{ $vTitle }}" placeholder="उदा. भाग 1: सम्यक दर्शन का स्वरूप">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size:12px;">वीडियो लिंक / पाथ (URL / File Path)</label>
                                <div style="display:flex; gap:8px;">
                                    <input type="text" name="videos[{{ $index }}][url]" id="video_url_{{ $index }}" class="form-control-custom video-url-input" value="{{ $vUrl }}" placeholder="{{ $vType === 'youtube' ? 'https://www.youtube.com/watch?v=...' : '/storage/media/video.mp4' }}">
                                    <button type="button" onclick="openMediaSelector('video_url_{{ $index }}', 'video')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                                        <i class="fa-solid fa-folder-open"></i> ब्राउज़
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Default empty 1st video row -->
                        <div class="repeater-box" data-index="0">
                            <div class="repeater-header">
                                <div class="repeater-title">
                                    <i class="fa-solid fa-circle-play text-danger"></i> वीडियो #1
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="type-pill-group">
                                        <button type="button" class="type-pill-btn active" onclick="switchVideoType(this, 'youtube')">
                                            <i class="fa-brands fa-youtube text-danger me-1"></i> YouTube
                                        </button>
                                        <button type="button" class="type-pill-btn" onclick="switchVideoType(this, 'self_hosted')">
                                            <i class="fa-solid fa-server text-primary me-1"></i> Self-Hosted
                                        </button>
                                    </div>
                                    <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="videos[0][type]" class="video-type-input" value="youtube">
                            
                            <div class="row-flex" style="margin-bottom: 10px;">
                                <div class="form-group" style="margin-bottom:0; flex:1;">
                                    <label class="form-label" style="font-size:12px;">वीडियो शीर्षक / भाग (उदा. भाग 1: मंगलाचरण)</label>
                                    <input type="text" name="videos[0][title]" class="form-control-custom" placeholder="उदा. भाग 1: सम्यक दर्शन का स्वरूप">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size:12px;">वीडियो लिंक / पाथ (URL / File Path)</label>
                                <div style="display:flex; gap:8px;">
                                    <input type="text" name="videos[0][url]" id="video_url_0" class="form-control-custom video-url-input" placeholder="https://www.youtube.com/watch?v=...">
                                    <button type="button" onclick="openMediaSelector('video_url_0', 'video')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                                        <i class="fa-solid fa-folder-open"></i> ब्राउज़
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" class="btn-add-repeater" onclick="addVideoRow()">
                    <i class="fa-solid fa-circle-plus"></i> ➕ और वीडियो जोड़ें (Add More Video)
                </button>
            </div>

            <!-- 2. MULTIPLE AUDIOS REPEATER -->
            <div class="panel-card" style="margin-bottom: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h3 style="font-size:16px; font-weight: 700; margin-bottom:0; color: var(--text-dark);">
                        <i class="fa-solid fa-headphones" style="color:var(--primary); margin-right:8px;"></i>ऑडियो संकलन (Multiple Audio Tracks)
                    </h3>
                    <span class="badge" style="background:#fff7ed; color:var(--primary); font-size:12px; padding:4px 10px; border-radius:12px;">
                        MP3 / Audio
                    </span>
                </div>
                <p style="font-size:12.5px; color:var(--text-light); margin-bottom:16px;">
                    यहाँ आप एक या अधिक ऑडियो ट्रैक्स, प्रवचन ऑडियो या भजन MP3 फाइल्स जोड़ सकते हैं।
                </p>

                <div id="audiosRepeaterList">
                    @php
                        $existingAudios = isset($content) && is_array($content->audios) ? $content->audios : [];
                    @endphp

                    @forelse($existingAudios as $index => $a)
                        @php
                            $aUrl = is_array($a) ? ($a['url'] ?? '') : $a;
                            $aTitle = is_array($a) ? ($a['title'] ?? '') : '';
                        @endphp
                        <div class="repeater-box" data-index="{{ $index }}">
                            <div class="repeater-header">
                                <div class="repeater-title">
                                    <i class="fa-solid fa-music text-success"></i> ऑडियो #{{ $index + 1 }}
                                </div>
                                <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>

                            <div class="row-flex" style="margin-bottom: 10px;">
                                <div class="form-group" style="margin-bottom:0; flex:1;">
                                    <label class="form-label" style="font-size:12px;">ऑडियो शीर्षक / ट्रैक नाम (उदा. मंगलाचरण)</label>
                                    <input type="text" name="audios[{{ $index }}][title]" class="form-control-custom" value="{{ $aTitle }}" placeholder="उदा. प्रवचन भाग 1 ऑडियो">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size:12px;">ऑडियो फ़ाइल पथ / लिंक (Audio Path / URL)</label>
                                <div style="display:flex; gap:8px;">
                                    <input type="text" name="audios[{{ $index }}][url]" id="audio_url_{{ $index }}" class="form-control-custom" value="{{ $aUrl }}" placeholder="/storage/media/audio.mp3">
                                    <button type="button" onclick="openMediaSelector('audio_url_{{ $index }}', 'audio')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                                        <i class="fa-solid fa-folder-open"></i> ब्राउज़
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="repeater-box" data-index="0">
                            <div class="repeater-header">
                                <div class="repeater-title">
                                    <i class="fa-solid fa-music text-success"></i> ऑडियो #1
                                </div>
                                <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>

                            <div class="row-flex" style="margin-bottom: 10px;">
                                <div class="form-group" style="margin-bottom:0; flex:1;">
                                    <label class="form-label" style="font-size:12px;">ऑडियो शीर्षक / ट्रैक नाम</label>
                                    <input type="text" name="audios[0][title]" class="form-control-custom" placeholder="उदा. मंगलाचरण एवं भक्ति गीत">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size:12px;">ऑडियो फ़ाइल पथ / लिंक (Audio Path / URL)</label>
                                <div style="display:flex; gap:8px;">
                                    <input type="text" name="audios[0][url]" id="audio_url_0" class="form-control-custom" placeholder="/storage/media/audio1.mp3">
                                    <button type="button" onclick="openMediaSelector('audio_url_0', 'audio')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                                        <i class="fa-solid fa-folder-open"></i> ब्राउज़
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" class="btn-add-repeater" onclick="addAudioRow()">
                    <i class="fa-solid fa-circle-plus"></i> ➕ और ऑडियो जोड़ें (Add More Audio)
                </button>
            </div>

            <!-- 3. MULTIPLE PDFS REPEATER -->
            <div class="panel-card" style="margin-bottom: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h3 style="font-size:16px; font-weight: 700; margin-bottom:0; color: var(--text-dark);">
                        <i class="fa-solid fa-file-pdf" style="color:var(--primary); margin-right:8px;"></i>पीडीएफ व ग्रंथ संकलन (Multiple PDF Documents / Books)
                    </h3>
                    <span class="badge" style="background:#fff7ed; color:var(--primary); font-size:12px; padding:4px 10px; border-radius:12px;">
                        PDF Books
                    </span>
                </div>
                <p style="font-size:12.5px; color:var(--text-light); margin-bottom:16px;">
                    यहाँ आप एक या अधिक पीडीएफ ग्रंथ, ई-बुक्स, पाठ-संग्रह या शोध पत्र जोड़ सकते हैं।
                </p>

                <div id="pdfsRepeaterList">
                    @php
                        $existingPdfs = isset($content) && is_array($content->pdfs) ? $content->pdfs : [];
                    @endphp

                    @forelse($existingPdfs as $index => $p)
                        @php
                            $pUrl = is_array($p) ? ($p['url'] ?? '') : $p;
                            $pTitle = is_array($p) ? ($p['title'] ?? '') : '';
                        @endphp
                        <div class="repeater-box" data-index="{{ $index }}">
                            <div class="repeater-header">
                                <div class="repeater-title">
                                    <i class="fa-solid fa-file-lines text-danger"></i> पीडीएफ #{{ $index + 1 }}
                                </div>
                                <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>

                            <div class="row-flex" style="margin-bottom: 10px;">
                                <div class="form-group" style="margin-bottom:0; flex:1;">
                                    <label class="form-label" style="font-size:12px;">दस्तावेज़ / पुस्तक का शीर्षक (Document Title)</label>
                                    <input type="text" name="pdfs[{{ $index }}][title]" class="form-control-custom" value="{{ $pTitle }}" placeholder="उदा. समयसार मूल गाथा व हिन्दी टीका.pdf">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size:12px;">पीडीएफ फ़ाइल पथ / लिंक (PDF Path / URL)</label>
                                <div style="display:flex; gap:8px;">
                                    <input type="text" name="pdfs[{{ $index }}][url]" id="pdf_url_{{ $index }}" class="form-control-custom" value="{{ $pUrl }}" placeholder="/storage/media/granth1.pdf">
                                    <button type="button" onclick="openMediaSelector('pdf_url_{{ $index }}', 'document')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                                        <i class="fa-solid fa-folder-open"></i> ब्राउज़
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="repeater-box" data-index="0">
                            <div class="repeater-header">
                                <div class="repeater-title">
                                    <i class="fa-solid fa-file-lines text-danger"></i> पीडीएफ #1
                                </div>
                                <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>

                            <div class="row-flex" style="margin-bottom: 10px;">
                                <div class="form-group" style="margin-bottom:0; flex:1;">
                                    <label class="form-label" style="font-size:12px;">दस्तावेज़ / पुस्तक का शीर्षक (Document Title)</label>
                                    <input type="text" name="pdfs[0][title]" class="form-control-custom" placeholder="उदा. समयसार मूल गाथा व हिन्दी टीका.pdf">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size:12px;">पीडीएफ फ़ाइल पथ / लिंक (PDF Path / URL)</label>
                                <div style="display:flex; gap:8px;">
                                    <input type="text" name="pdfs[0][url]" id="pdf_url_0" class="form-control-custom" placeholder="/storage/media/granth1.pdf">
                                    <button type="button" onclick="openMediaSelector('pdf_url_0', 'document')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                                        <i class="fa-solid fa-folder-open"></i> ब्राउज़
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" class="btn-add-repeater" onclick="addPdfRow()">
                    <i class="fa-solid fa-circle-plus"></i> ➕ और पीडीएफ जोड़ें (Add More PDF)
                </button>
            </div>

            <!-- 4. MULTIPLE IMAGES / GALLERY REPEATER -->
            <div class="panel-card" style="margin-bottom: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h3 style="font-size:16px; font-weight: 700; margin-bottom:0; color: var(--text-dark);">
                        <i class="fa-solid fa-images" style="color:var(--primary); margin-right:8px;"></i>चित्र दीर्घा (Multiple Images / Gallery)
                    </h3>
                    <span class="badge" style="background:#fff7ed; color:var(--primary); font-size:12px; padding:4px 10px; border-radius:12px;">
                        Photo Gallery
                    </span>
                </div>
                <p style="font-size:12.5px; color:var(--text-light); margin-bottom:16px;">
                    इस सामग्री से संबंधित अतिरिक्त चित्र एवं फोटो दीर्घा यहाँ जोड़ें।
                </p>

                <div id="galleryRepeaterList">
                    @php
                        $existingGallery = isset($content) && is_array($content->media_gallery) ? $content->media_gallery : [];
                    @endphp

                    @forelse($existingGallery as $index => $g)
                        @php
                            $gUrl = is_array($g) ? ($g['url'] ?? '') : $g;
                            $gCaption = is_array($g) ? ($g['caption'] ?? '') : '';
                        @endphp
                        <div class="repeater-box" data-index="{{ $index }}">
                            <div class="repeater-header">
                                <div class="repeater-title">
                                    <i class="fa-solid fa-image text-warning"></i> चित्र #{{ $index + 1 }}
                                </div>
                                <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>

                            <div style="display:flex; gap:16px; align-items:flex-start;">
                                <div style="width:75px; height:65px; border-radius:8px; border:1px solid #e2e8f0; overflow:hidden; background:#f1f5f9; flex-shrink:0;">
                                    <img src="{{ $gUrl ? asset($gUrl) : 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'><rect fill=\'%23f1f5f9\' width=\'100\' height=\'100\'/><text fill=\'%2394a3b8\' x=\'50%\' y=\'50%\' text-anchor=\'middle\' dominant-baseline=\'middle\' font-size=\'12\'>Preview</text></svg>' }}" id="preview_gallery_url_{{ $index }}" style="width:100%; height:100%; object-fit:cover;">
                                </div>
                                <div style="flex-grow:1;">
                                    <div class="form-group" style="margin-bottom:8px;">
                                        <div style="display:flex; gap:8px;">
                                            <input type="text" name="media_gallery[{{ $index }}][url]" id="gallery_url_{{ $index }}" class="form-control-custom" value="{{ $gUrl }}" placeholder="/storage/media/photo.jpg" oninput="document.getElementById('preview_gallery_url_{{ $index }}').src = this.value">
                                            <button type="button" onclick="openMediaSelector('gallery_url_{{ $index }}', 'image', 'preview_gallery_url_{{ $index }}')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                                                <i class="fa-solid fa-folder-open"></i> ब्राउज़
                                            </button>
                                        </div>
                                    </div>
                                    <input type="text" name="media_gallery[{{ $index }}][caption]" class="form-control-custom" value="{{ $gCaption }}" placeholder="चित्र कैप्शन / विवरण (उदा. सभा दृश्य)">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="repeater-box" data-index="0">
                            <div class="repeater-header">
                                <div class="repeater-title">
                                    <i class="fa-solid fa-image text-warning"></i> चित्र #1
                                </div>
                                <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>

                            <div style="display:flex; gap:16px; align-items:flex-start;">
                                <div style="width:75px; height:65px; border-radius:8px; border:1px solid #e2e8f0; overflow:hidden; background:#f1f5f9; flex-shrink:0;">
                                    <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'><rect fill='%23f1f5f9' width='100' height='100'/><text fill='%2394a3b8' x='50%' y='50%' text-anchor='middle' dominant-baseline='middle' font-size='12'>Preview</text></svg>" id="preview_gallery_url_0" style="width:100%; height:100%; object-fit:cover;">
                                </div>
                                <div style="flex-grow:1;">
                                    <div class="form-group" style="margin-bottom:8px;">
                                        <div style="display:flex; gap:8px;">
                                            <input type="text" name="media_gallery[0][url]" id="gallery_url_0" class="form-control-custom" placeholder="/storage/media/photo.jpg" oninput="document.getElementById('preview_gallery_url_0').src = this.value">
                                            <button type="button" onclick="openMediaSelector('gallery_url_0', 'image', 'preview_gallery_url_0')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                                                <i class="fa-solid fa-folder-open"></i> ब्राउज़
                                            </button>
                                        </div>
                                    </div>
                                    <input type="text" name="media_gallery[0][caption]" class="form-control-custom" placeholder="चित्र कैप्शन / विवरण (उदा. सभा दृश्य)">
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" class="btn-add-repeater" onclick="addGalleryRow()">
                    <i class="fa-solid fa-circle-plus"></i> ➕ और चित्र जोड़ें (Add More Image)
                </button>
            </div>
        </div>

        <!-- Sidebar Actions & Settings Column (Right) -->
        <div>
            <!-- Publishing Actions -->
            <div class="panel-card" style="margin-bottom: 24px; position: sticky; top: 20px;">
                <h3 style="font-size:15px; font-weight: 700; margin-bottom:20px; border-bottom:1.5px solid #f1f5f9; padding-bottom:10px; color: var(--text-dark);">
                    <i class="fa-solid fa-circle-check" style="color:var(--primary); margin-right:6px;"></i>प्रकाशन (Publish)
                </h3>
                
                <div class="form-group">
                    <label class="form-label">प्रकाशन स्थिति (Status)</label>
                    <select name="status" class="form-control-custom">
                        <option value="draft" {{ (isset($content) && $content->status === 'draft') ? 'selected' : '' }}>प्रारूप (Draft)</option>
                        <option value="published" {{ (!isset($content) || $content->status === 'published') ? 'selected' : '' }}>प्रकाशित (Published)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">प्रकाशन तिथि (Publish Date)</label>
                    <input type="date" name="publish_date" class="form-control-custom" value="{{ isset($content) ? $content->publish_date->format('Y-m-d') : now()->format('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">क्रम (Display Order)</label>
                    <input type="number" name="display_order" class="form-control-custom" value="{{ $content->display_order ?? 0 }}">
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; font-size:15px; padding:12px;"><i class="fa-solid fa-cloud-arrow-up"></i> सहेजें (Save Content)</button>
            </div>

            <!-- Featured Image Selection -->
            <div class="panel-card" style="margin-bottom: 24px;">
                <h3 style="font-size:15px; font-weight: 700; margin-bottom:20px; border-bottom:1.5px solid #f1f5f9; padding-bottom:10px; color: var(--text-dark);">
                    <i class="fa-solid fa-image" style="color:var(--primary); margin-right:6px;"></i>मुख्य चित्र (Featured Image)
                </h3>
                
                <div class="form-group">
                    <label class="form-label">चित्र का पाथ (Image Path)</label>
                    <input type="text" name="featured_image" id="featured_image" class="form-control-custom" value="{{ $content->featured_image ?? '' }}" placeholder="/storage/media/img.jpg" oninput="document.getElementById('featured_preview').src = this.value">
                </div>

                <!-- Preview Box -->
                <div style="border-radius:var(--border-radius-md); overflow:hidden; border:1px solid #e2e8f0; height:150px; background:#f8fafc; margin-bottom:12px; display:flex; align-items:center; justify-content:center;">
                    <img id="featured_preview" src="{{ (!empty($content->featured_image)) ? asset($content->featured_image) : 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'><rect fill=\'%23f8fafc\' width=\'100\' height=\'100\'/><text fill=\'%2394a3b8\' x=\'50%\' y=\'50%\' text-anchor=\'middle\' dominant-baseline=\'middle\' font-size=\'13\'>मुख्य चित्र प्रीव्यू</text></svg>' }}" style="width:100%; height:100%; object-fit:cover;">
                </div>
                
                <!-- Quick Media Library Selector -->
                <div style="background:#f8fafc; border:2px dashed #cbd5e1; border-radius:var(--border-radius-md); padding:14px; text-align:center;">
                    <p style="font-size:12px; color:var(--text-light); margin-bottom:10px; font-weight: 500;">मीडिया लाइब्रेरी या कंप्यूटर से चित्र चुनें</p>
                    <button type="button" onclick="openMediaSelector('featured_image', 'image', 'featured_preview')" class="btn-secondary" style="font-size:12px; padding:8px 16px;">
                        <i class="fa-solid fa-photo-film"></i> चुनें / अपलोड करें (Browse)
                    </button>
                </div>
            </div>

            <!-- Global Visibility -->
            <div class="panel-card" style="margin-bottom: 24px;">
                <h3 style="font-size:15px; font-weight: 700; margin-bottom:20px; border-bottom:1.5px solid #f1f5f9; padding-bottom:10px; color: var(--text-dark);">
                    <i class="fa-solid fa-eye" style="color:var(--primary); margin-right:6px;"></i>दृश्यता (Visibility)
                </h3>
                <div class="checkbox-group" style="flex-direction:column; gap:12px; margin-bottom: 0;">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" {{ (!isset($content) || $content->is_active) ? 'checked' : '' }}>
                        <span>सक्रिय (Active)</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" {{ (isset($content) && $content->is_featured) ? 'checked' : '' }}>
                        <span>मुख्य सामग्री (Featured on Home)</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="show_on_website" {{ (!isset($content) || $content->show_on_website) ? 'checked' : '' }}>
                        <span>वेबसाइट पर दिखाएं (Web)</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="show_on_app" {{ (!isset($content) || $content->show_on_app) ? 'checked' : '' }}>
                        <span>मोबाइल ऐप पर दिखाएं (App)</span>
                    </label>
                </div>
            </div>

            <!-- SEO fields -->
            <div class="panel-card" style="margin-bottom: 24px;">
                <h3 style="font-size:15px; font-weight: 700; margin-bottom:20px; border-bottom:1.5px solid #f1f5f9; padding-bottom:10px; color: var(--text-dark);">
                    <i class="fa-solid fa-search" style="color:var(--primary); margin-right:6px;"></i>SEO सेटिंग्स
                </h3>
                <div class="form-group">
                    <label class="form-label">मेटा शीर्षक (Meta Title)</label>
                    <input type="text" name="meta_title_hi" class="form-control-custom" value="{{ $content->meta_title['hi'] ?? '' }}" placeholder="खोज इंजनों के लिए शीर्षक">
                </div>
                <div class="form-group">
                    <label class="form-label">मेटा विवरण (Meta Description)</label>
                    <textarea name="meta_description_hi" class="form-control-custom" style="height:65px;" placeholder="गूगल सर्च के लिए संक्षिप्त विवरण...">{{ $content->meta_description['hi'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">टैग्स (Tags - अल्पविराम से अलग करें)</label>
                    <input type="text" name="tags" class="form-control-custom" value="{{ isset($content) && is_array($content->tags) ? implode(',', $content->tags) : '' }}" placeholder="उदा: प्रवचन, जिनवाणी, तत्वार्थसूत्र">
                </div>
            </div>
        </div>
    </div>
</form>

<!-- UNIVERSAL TABBED MEDIA SELECTOR MODAL WITH INSTANT AJAX UPLOAD -->
<div id="mediaModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.65); z-index:9999; justify-content:center; align-items:center; backdrop-filter: blur(5px);">
    <div class="panel-card" style="width:92%; max-width:880px; height:85%; display:flex; flex-direction:column; padding:24px; background: white; border-radius:16px;">
        
        <!-- Modal Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1.5px solid #f1f5f9; padding-bottom:14px; margin-bottom:14px;">
            <h3 class="panel-title" style="margin-bottom:0; font-size:17px;">
                <i class="fa-solid fa-photo-film text-warning"></i> <span>मीडिया चयन व अपलोडर (Universal Media Selector)</span>
            </h3>
            <button onclick="closeMediaSelector()" class="btn-action btn-action-delete" style="font-size: 18px;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <!-- Filter Tabs & Direct Upload Tab -->
        <div style="display:flex; gap:6px; border-bottom:1.5px solid #e2e8f0; margin-bottom:16px; overflow-x:auto;">
            <button type="button" class="modal-tab-btn active" onclick="filterModalMedia('all', this)"><i class="fa-solid fa-layer-group me-1"></i> सभी (All)</button>
            <button type="button" class="modal-tab-btn" onclick="filterModalMedia('image', this)"><i class="fa-solid fa-image me-1"></i> चित्र (Images)</button>
            <button type="button" class="modal-tab-btn" onclick="filterModalMedia('video', this)"><i class="fa-solid fa-video me-1"></i> वीडियो (Videos)</button>
            <button type="button" class="modal-tab-btn" onclick="filterModalMedia('audio', this)"><i class="fa-solid fa-headphones me-1"></i> ऑडियो (Audio)</button>
            <button type="button" class="modal-tab-btn" onclick="filterModalMedia('document', this)"><i class="fa-solid fa-file-pdf me-1"></i> PDF / ग्रंथ</button>
            <button type="button" class="modal-tab-btn" onclick="showModalUploadTab(this)" style="color:var(--primary);"><i class="fa-solid fa-cloud-arrow-up me-1"></i> ⬆️ नयी फ़ाइल अपलोड करें</button>
        </div>

        <!-- Media Gallery Grid View -->
        <div id="modalGalleryView" style="flex-grow:1; overflow-y:auto; padding-right:6px;">
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(135px, 1fr)); gap:14px;" id="mediaListGrid">
                @foreach($media as $m)
                    @php
                        $mType = $m->file_type ?? 'document';
                        if ($mType === 'pdf') $mType = 'document';
                    @endphp
                    <div class="media-item-card" data-type="{{ $mType }}" onclick="selectMediaItem('{{ $m->file_path }}', '{{ $m->name }}')" style="border:1.5px solid #e2e8f0; border-radius:var(--border-radius-md); padding:10px; cursor:pointer; text-align:center; transition:var(--transition); background: #fafafa; position:relative;" onmouseover="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.06)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        @if($mType === 'image')
                            <img src="{{ asset($m->file_path) }}" style="width:100%; height:90px; object-fit:cover; border-radius:6px; border: 1px solid #e2e8f0;">
                        @elseif($mType === 'video')
                            <div style="height:90px; background:#fef2f2; border-radius:6px; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#dc2626;">
                                <i class="fa-solid fa-video" style="font-size:28px; margin-bottom:4px;"></i>
                                <span style="font-size:10px; font-weight:700;">MP4 VIDEO</span>
                            </div>
                        @elseif($mType === 'audio')
                            <div style="height:90px; background:#f0fdf4; border-radius:6px; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#16a34a;">
                                <i class="fa-solid fa-headphones" style="font-size:28px; margin-bottom:4px;"></i>
                                <span style="font-size:10px; font-weight:700;">AUDIO MP3</span>
                            </div>
                        @else
                            <div style="height:90px; background:#fff7ed; border-radius:6px; display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--primary);">
                                <i class="fa-solid fa-file-pdf" style="font-size:28px; margin-bottom:4px;"></i>
                                <span style="font-size:10px; font-weight:700;">PDF BOOK</span>
                            </div>
                        @endif
                        <p style="font-size:11px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; margin-top:8px; font-weight:600; color: var(--text-dark); margin-bottom:0;" title="{{ $m->name }}">{{ $m->name }}</p>
                    </div>
                @endforeach
            </div>
            <div id="emptyMediaNotice" style="display:none; text-align:center; padding:40px; color:var(--text-light);">
                <i class="fa-solid fa-folder-open" style="font-size:40px; margin-bottom:12px; color:#cbd5e1;"></i>
                <p>इस श्रेणी में कोई मीडिया फ़ाइल नहीं मिली। कृपया नयी फ़ाइल अपलोड करें।</p>
            </div>
        </div>

        <!-- Direct Upload View inside Modal -->
        <div id="modalUploadView" style="display:none; flex-grow:1; flex-direction:column; align-items:center; justify-content:center; padding:30px; text-align:center; background:#fafafa; border:2px dashed #cbd5e1; border-radius:12px;">
            <i class="fa-solid fa-cloud-arrow-up" style="font-size:48px; color:var(--primary); margin-bottom:16px;"></i>
            <h4 style="font-size:16px; font-weight:700; margin-bottom:8px;">कंप्यूटर से फ़ाइल चुनें (Upload File)</h4>
            <p style="font-size:13px; color:var(--text-light); max-width:400px; margin-bottom:20px;">
                इमेज, वीडियो (MP4), ऑडियो (MP3) या पीडीएफ दस्तावेज चुनें। फ़ाइल तुरंत अपलोड होकर फ़ील्ड में सेट हो जाएगी।
            </p>
            <input type="file" id="modalAjaxFileInput" style="display:none;" onchange="handleModalAjaxUpload(this)">
            <button type="button" onclick="document.getElementById('modalAjaxFileInput').click()" class="btn-primary" style="padding:10px 24px; font-size:14px;">
                <i class="fa-solid fa-upload"></i> फ़ाइल चुनें और अपलोड करें
            </button>
            <div id="uploadProgressBar" style="display:none; width:100%; max-width:350px; margin-top:20px;">
                <div style="background:#e2e8f0; border-radius:10px; height:8px; overflow:hidden;">
                    <div id="uploadProgressFill" style="background:var(--primary); height:100%; width:0%; transition:width 0.2s;"></div>
                </div>
                <span id="uploadStatusText" style="font-size:11px; color:var(--text-light); margin-top:6px; display:block;">अपलोड हो रहा है...</span>
            </div>
        </div>
    </div>
</div>

<!-- Quill Rich Text Editor Script -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    // 1. Initialize Quill WYSIWYG Editor
    document.addEventListener("DOMContentLoaded", function() {
        var quill = new Quill('#quillEditorContainer', {
            theme: 'snow',
            placeholder: 'यहाँ संपूर्ण सामग्री, प्रवचन व्याख्या, गाथा अर्थ, भक्ति सार विस्तार से लिखें...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'clean']
                ]
            }
        });

        // Sync Quill content to hidden form input on form submission
        var contentForm = document.getElementById('contentForm');
        if (contentForm) {
            contentForm.addEventListener('submit', function() {
                var html = quill.root.innerHTML;
                if (quill.getText().trim().length === 0 && html === '<p><br></p>') {
                    html = '';
                }
                document.getElementById('full_description_hi').value = html;
            });
        }
    });

    // 2. Video Repeater Functions (YouTube & Self-Hosted)
    function switchVideoType(btn, type) {
        var box = btn.closest('.repeater-box');
        var pillBtns = box.querySelectorAll('.type-pill-btn');
        pillBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        var typeInput = box.querySelector('.video-type-input');
        if (typeInput) typeInput.value = type;

        var urlInput = box.querySelector('.video-url-input');
        if (urlInput) {
            if (type === 'youtube') {
                urlInput.placeholder = 'https://www.youtube.com/watch?v=...';
            } else {
                urlInput.placeholder = '/storage/media/video.mp4';
            }
        }
    }

    function addVideoRow() {
        var list = document.getElementById('videosRepeaterList');
        var count = list.children.length;
        var newIndex = Date.now(); // unique index

        var html = `
        <div class="repeater-box" data-index="${newIndex}">
            <div class="repeater-header">
                <div class="repeater-title">
                    <i class="fa-solid fa-circle-play text-danger"></i> वीडियो #${count + 1}
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="type-pill-group">
                        <button type="button" class="type-pill-btn active" onclick="switchVideoType(this, 'youtube')">
                            <i class="fa-brands fa-youtube text-danger me-1"></i> YouTube
                        </button>
                        <button type="button" class="type-pill-btn" onclick="switchVideoType(this, 'self_hosted')">
                            <i class="fa-solid fa-server text-primary me-1"></i> Self-Hosted
                        </button>
                    </div>
                    <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>
            <input type="hidden" name="videos[${newIndex}][type]" class="video-type-input" value="youtube">
            
            <div class="row-flex" style="margin-bottom: 10px;">
                <div class="form-group" style="margin-bottom:0; flex:1;">
                    <label class="form-label" style="font-size:12px;">वीडियो शीर्षक / भाग (उदा. भाग ${count + 1}: विषय)</label>
                    <input type="text" name="videos[${newIndex}][title]" class="form-control-custom" placeholder="उदा. भाग ${count + 1}: सम्यक दर्शन">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size:12px;">वीडियो लिंक / पाथ (URL / File Path)</label>
                <div style="display:flex; gap:8px;">
                    <input type="text" name="videos[${newIndex}][url]" id="video_url_${newIndex}" class="form-control-custom video-url-input" placeholder="https://www.youtube.com/watch?v=...">
                    <button type="button" onclick="openMediaSelector('video_url_${newIndex}', 'video')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                        <i class="fa-solid fa-folder-open"></i> ब्राउज़
                    </button>
                </div>
            </div>
        </div>`;
        list.insertAdjacentHTML('beforeend', html);
    }

    // 3. Audio Repeater Functions
    function addAudioRow() {
        var list = document.getElementById('audiosRepeaterList');
        var count = list.children.length;
        var newIndex = Date.now();

        var html = `
        <div class="repeater-box" data-index="${newIndex}">
            <div class="repeater-header">
                <div class="repeater-title">
                    <i class="fa-solid fa-music text-success"></i> ऑडियो #${count + 1}
                </div>
                <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>

            <div class="row-flex" style="margin-bottom: 10px;">
                <div class="form-group" style="margin-bottom:0; flex:1;">
                    <label class="form-label" style="font-size:12px;">ऑडियो शीर्षक / ट्रैक नाम</label>
                    <input type="text" name="audios[${newIndex}][title]" class="form-control-custom" placeholder="उदा. ऑडियो ट्रैक ${count + 1}">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size:12px;">ऑडियो फ़ाइल पथ / लिंक (Audio Path / URL)</label>
                <div style="display:flex; gap:8px;">
                    <input type="text" name="audios[${newIndex}][url]" id="audio_url_${newIndex}" class="form-control-custom" placeholder="/storage/media/audio.mp3">
                    <button type="button" onclick="openMediaSelector('audio_url_${newIndex}', 'audio')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                        <i class="fa-solid fa-folder-open"></i> ब्राउज़
                    </button>
                </div>
            </div>
        </div>`;
        list.insertAdjacentHTML('beforeend', html);
    }

    // 4. PDF Repeater Functions
    function addPdfRow() {
        var list = document.getElementById('pdfsRepeaterList');
        var count = list.children.length;
        var newIndex = Date.now();

        var html = `
        <div class="repeater-box" data-index="${newIndex}">
            <div class="repeater-header">
                <div class="repeater-title">
                    <i class="fa-solid fa-file-lines text-danger"></i> पीडीएफ #${count + 1}
                </div>
                <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>

            <div class="row-flex" style="margin-bottom: 10px;">
                <div class="form-group" style="margin-bottom:0; flex:1;">
                    <label class="form-label" style="font-size:12px;">दस्तावेज़ / पुस्तक का शीर्षक</label>
                    <input type="text" name="pdfs[${newIndex}][title]" class="form-control-custom" placeholder="उदा. ग्रंथ भाग ${count + 1}.pdf">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size:12px;">पीडीएफ फ़ाइल पथ / लिंक (PDF Path / URL)</label>
                <div style="display:flex; gap:8px;">
                    <input type="text" name="pdfs[${newIndex}][url]" id="pdf_url_${newIndex}" class="form-control-custom" placeholder="/storage/media/granth.pdf">
                    <button type="button" onclick="openMediaSelector('pdf_url_${newIndex}', 'document')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                        <i class="fa-solid fa-folder-open"></i> ब्राउज़
                    </button>
                </div>
            </div>
        </div>`;
        list.insertAdjacentHTML('beforeend', html);
    }

    // 5. Gallery Images Repeater Functions
    function addGalleryRow() {
        var list = document.getElementById('galleryRepeaterList');
        var count = list.children.length;
        var newIndex = Date.now();

        var html = `
        <div class="repeater-box" data-index="${newIndex}">
            <div class="repeater-header">
                <div class="repeater-title">
                    <i class="fa-solid fa-image text-warning"></i> चित्र #${count + 1}
                </div>
                <button type="button" class="btn-remove-row" onclick="removeRepeaterRow(this)" title="हटाएं">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>

            <div style="display:flex; gap:16px; align-items:flex-start;">
                <div style="width:75px; height:65px; border-radius:8px; border:1px solid #e2e8f0; overflow:hidden; background:#f1f5f9; flex-shrink:0;">
                    <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'><rect fill='%23f1f5f9' width='100' height='100'/><text fill='%2394a3b8' x='50%' y='50%' text-anchor='middle' dominant-baseline='middle' font-size='12'>Preview</text></svg>" id="preview_gallery_url_${newIndex}" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <div style="flex-grow:1;">
                    <div class="form-group" style="margin-bottom:8px;">
                        <div style="display:flex; gap:8px;">
                            <input type="text" name="media_gallery[${newIndex}][url]" id="gallery_url_${newIndex}" class="form-control-custom" placeholder="/storage/media/photo.jpg" oninput="document.getElementById('preview_gallery_url_${newIndex}').src = this.value">
                            <button type="button" onclick="openMediaSelector('gallery_url_${newIndex}', 'image', 'preview_gallery_url_${newIndex}')" class="btn-secondary" style="white-space:nowrap; padding:0 14px; font-size:12px;">
                                <i class="fa-solid fa-folder-open"></i> ब्राउज़
                            </button>
                        </div>
                    </div>
                    <input type="text" name="media_gallery[${newIndex}][caption]" class="form-control-custom" placeholder="चित्र कैप्शन / विवरण">
                </div>
            </div>
        </div>`;
        list.insertAdjacentHTML('beforeend', html);
    }

    function removeRepeaterRow(btn) {
        var box = btn.closest('.repeater-box');
        if (box) {
            box.remove();
        }
    }

    // 6. Universal Tabbed Media Selector Modal Logic
    var currentTargetInputId = null;
    var currentTargetPreviewId = null;

    function openMediaSelector(targetInputId, filterType, targetPreviewId) {
        currentTargetInputId = targetInputId;
        currentTargetPreviewId = targetPreviewId || null;

        document.getElementById('mediaModal').style.display = 'flex';
        
        // Switch to appropriate tab
        var tabs = document.querySelectorAll('.modal-tab-btn');
        tabs.forEach(t => t.classList.remove('active'));

        var tabToActivate = filterType || 'all';
        var matchingBtn = Array.from(tabs).find(b => b.getAttribute('onclick') && b.getAttribute('onclick').includes("'" + tabToActivate + "'"));
        if (matchingBtn) {
            matchingBtn.classList.add('active');
        } else if (tabs.length > 0) {
            tabs[0].classList.add('active');
        }

        filterModalMedia(tabToActivate, matchingBtn);
    }

    function closeMediaSelector() {
        document.getElementById('mediaModal').style.display = 'none';
        currentTargetInputId = null;
        currentTargetPreviewId = null;
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeMediaSelector();
        }
    });

    function filterModalMedia(type, btn) {
        document.getElementById('modalGalleryView').style.display = 'block';
        document.getElementById('modalUploadView').style.display = 'none';

        if (btn) {
            document.querySelectorAll('.modal-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        var items = document.querySelectorAll('.media-item-card');
        var visibleCount = 0;
        items.forEach(function(item) {
            var itemType = item.getAttribute('data-type');
            if (type === 'all' || itemType === type) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        document.getElementById('emptyMediaNotice').style.display = (visibleCount === 0) ? 'block' : 'none';
    }

    function showModalUploadTab(btn) {
        document.getElementById('modalGalleryView').style.display = 'none';
        document.getElementById('modalUploadView').style.display = 'flex';
        document.querySelectorAll('.modal-tab-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
    }

    function selectMediaItem(url, name) {
        if (currentTargetInputId) {
            var targetInput = document.getElementById(currentTargetInputId);
            if (targetInput) {
                targetInput.value = url;
            }
        }
        if (currentTargetPreviewId) {
            var targetPreview = document.getElementById(currentTargetPreviewId);
            if (targetPreview) {
                targetPreview.src = url;
            }
        }
        closeMediaSelector();
    }

    // 7. Instant AJAX Upload from Modal
    function handleModalAjaxUpload(input) {
        if (!input.files || input.files.length === 0) return;

        var file = input.files[0];
        var formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        var progressBox = document.getElementById('uploadProgressBar');
        var progressFill = document.getElementById('uploadProgressFill');
        var statusText = document.getElementById('uploadStatusText');

        progressBox.style.display = 'block';
        progressFill.style.width = '10%';
        statusText.innerText = 'अपलोड शुरू हो रहा है... (' + file.name + ')';

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ url("admin/media/upload") }}', true);

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                var percent = Math.round((e.loaded / e.total) * 100);
                progressFill.style.width = percent + '%';
                statusText.innerText = 'अपलोड हो रहा है: ' + percent + '%';
            }
        };

        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var resp = JSON.parse(xhr.responseText);
                    if (resp.success && resp.url) {
                        statusText.innerText = '✅ सफलतापूर्वक अपलोड किया गया!';
                        setTimeout(function() {
                            selectMediaItem(resp.url, file.name);
                            progressBox.style.display = 'none';
                            input.value = '';
                        }, 500);
                    } else {
                        alert('अपलोड त्रुटि: ' + (resp.message || 'अमान्य प्रतिक्रिया'));
                        progressBox.style.display = 'none';
                    }
                } catch(e) {
                    alert('अपलोड त्रुटि: ' + xhr.responseText);
                    progressBox.style.display = 'none';
                }
            } else {
                alert('अपलोड में त्रुटि हुई। स्टेटस कोड: ' + xhr.status);
                progressBox.style.display = 'none';
            }
        };

        xhr.onerror = function() {
            alert('नेटवर्क त्रुटि के कारण अपलोड विफल रहा।');
            progressBox.style.display = 'none';
        };

        xhr.send(formData);
    }
</script>
@endsection
