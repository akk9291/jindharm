@extends('layouts.admin')

@section('title', 'मीडिया लाइब्रेरी (Media Library)')
@section('header_title', 'मीडिया लाइब्रेरी प्रबंधन (Media Library)')

@section('content')
<style>
    .media-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 32px;
    }
    @media(max-width: 992px) {
        .media-layout {
            grid-template-columns: 1fr;
        }
    }
    .folder-sidebar {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 24px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(226, 232, 240, 0.8);
        height: fit-content;
    }
    .folder-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text-medium);
        padding: 10px 14px;
        border-radius: var(--border-radius-md);
        text-decoration: none;
        margin-bottom: 6px;
        transition: var(--transition);
    }
    .folder-item i {
        font-size: 16px;
        color: var(--text-light);
        transition: var(--transition);
    }
    .folder-item:hover, .folder-item.active {
        background: var(--primary-light);
        color: var(--primary);
    }
    .folder-item:hover i, .folder-item.active i {
        color: var(--primary);
    }
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 20px;
    }
    .media-card {
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--border-radius-lg);
        padding: 12px;
        text-align: center;
        position: relative;
        transition: var(--transition);
        cursor: pointer;
    }
    .media-card:hover {
        border-color: var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(234, 88, 12, 0.05);
    }
    .media-preview {
        height: 110px;
        border-radius: var(--border-radius-md);
        overflow: hidden;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #94a3b8;
        margin-bottom: 12px;
        border: 1px solid #f1f5f9;
    }
    .media-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .media-info h4 {
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--text-dark);
        font-weight: 700;
    }
    .media-info p {
        font-size: 10px;
        color: var(--text-light);
        margin-top: 4px;
        font-weight: 500;
    }
</style>

<div class="media-layout">
    <!-- Sidebar: Folders -->
    <div class="folder-sidebar">
        <h3 style="font-size:14px; font-weight:600; margin-bottom:12px; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">फ़ोल्डर्स (Folders)</h3>
        <a href="#" class="folder-item active"><i class="fa-solid fa-folder-open"></i> मुख्य डायरेक्टरी (Root)</a>
        
        @foreach($folders as $folder)
            <a href="?folder={{ $folder->id }}" class="folder-item"><i class="fa-solid fa-folder"></i> {{ $folder->name }}</a>
        @endforeach

        <form action="{{ url('admin/media/folder') }}" method="POST" style="margin-top:20px; border-top:1px solid #f1f5f9; padding-top:16px;">
            @csrf
            <label style="font-size:11px; font-weight:600; color:var(--text-light); display:block; margin-bottom:6px;">नया फ़ोल्डर (New Folder)</label>
            <input type="text" name="name" class="form-control-custom" style="padding:6px 10px; font-size:12px; margin-bottom:8px;" placeholder="उदा: ग्रन्थ चित्र" required>
            <button type="submit" class="btn-primary" style="font-size:11px; padding:6px 12px; width:100%; justify-content:center;"><i class="fa-solid fa-plus"></i> फ़ोल्डर बनाएं</button>
        </form>
    </div>

    <!-- Main Content: Upload & Files Grid -->
    <div>
        <!-- Uploader card -->
        <div class="panel-card" style="margin-bottom: 24px;">
            <div class="panel-header" style="padding-bottom: 8px; margin-bottom: 12px;">
                <div class="panel-title"><i class="fa-solid fa-cloud-arrow-up"></i> फ़ाइलें अपलोड करें (Upload Files)</div>
            </div>
            <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 24px; text-align: center; background: #fafafa;">
                <i class="fa-solid fa-cloud-arrow-up" style="font-size:32px; color:var(--primary); margin-bottom:12px;"></i>
                <h4 style="font-size:14px; margin-bottom:4px;">अपलोड करने के लिए फ़ाइल चुनें</h4>
                <p style="font-size:11px; color:var(--text-light); margin-bottom:16px;">अधिकतम फ़ाइल आकार: 20MB (Images, Videos, Audio, PDFs)</p>
                <input type="file" id="mediaFileInput" style="display:none;" onchange="uploadSelectedFile()">
                <button type="button" class="btn-primary" onclick="document.getElementById('mediaFileInput').click()"><i class="fa-solid fa-file-import"></i> फ़ाइल चुनें (Select File)</button>
            </div>
        </div>

        <!-- Media Grid -->
        <div class="panel-card">
            <div class="panel-header">
                <div class="panel-title"><i class="fa-solid fa-images"></i> आपकी मीडिया फ़ाइलें (Library Files)</div>
            </div>
            @if($files->isEmpty())
                <p style="text-align: center; color: var(--text-light); padding: 40px 0;">कोई फ़ाइल उपलब्ध नहीं है। कृपया फ़ाइलें अपलोड करें।</p>
            @else
                <div class="media-grid">
                    @foreach($files as $file)
                        <div class="media-card">
                            <div class="media-preview" title="{{ $file->file_name }}">
                                @if($file->file_type === 'image')
                                    <img src="{{ asset($file->file_path) }}" alt="{{ $file->name }}">
                                @elseif($file->file_type === 'audio')
                                    <i class="fa-solid fa-file-audio" style="color:#10b981;"></i>
                                @elseif($file->file_type === 'video')
                                    <i class="fa-solid fa-file-video" style="color:#ef4444;"></i>
                                @elseif($file->file_type === 'pdf')
                                    <i class="fa-solid fa-file-pdf" style="color:#ea580c;"></i>
                                @else
                                    <i class="fa-solid fa-file-lines"></i>
                                @endif
                            </div>
                            <div class="media-info">
                                <h4>{{ $file->name }}</h4>
                                <p>{{ strtoupper($file->file_type) }} | {{ number_format($file->file_size / 1024, 1) }} KB</p>
                            </div>
                            
                            <!-- Actions overlay -->
                            <div style="margin-top:8px; display:flex; justify-content:center; gap:8px; border-top:1px solid #f1f5f9; padding-top:6px;">
                                <button onclick="copyPath('{{ $file->file_path }}')" style="background:none; border:none; color:var(--primary); cursor:pointer; font-size:11px;" title="कॉपी करें"><i class="fa-solid fa-copy"></i> कॉपी</button>
                                <a href="{{ url('admin/media/delete/'.$file->id) }}" onclick="return confirm('क्या आप इस फ़ाइल को स्थायी रूप से मिटाना चाहते हैं?')" style="color:#ef4444; font-size:11px;" title="मिटाएं"><i class="fa-solid fa-trash"></i> मिटाएं</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function uploadSelectedFile() {
        const fileInput = document.getElementById('mediaFileInput');
        if (fileInput.files.length === 0) return;

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        // Add folder context if any
        const urlParams = new URLSearchParams(window.location.search);
        const folderId = urlParams.get('folder');
        if (folderId) {
            formData.append('folder_id', folderId);
        }

        // Upload files via fetch
        fetch('{{ url("admin/media/upload") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('अपलोड करने में त्रुटि हुई!');
            }
        })
        .catch(err => {
            console.error(err);
            alert('सर्वर से संपर्क करने में समस्या हुई।');
        });
    }

    function copyPath(path) {
        navigator.clipboard.writeText(path).then(() => {
            alert('फ़ाइल पथ कॉपी कर लिया गया है:\n' + path);
        }).catch(err => {
            console.error('Copy failed:', err);
        });
    }
</script>
@endsection
