@extends('layouts.admin')

@section('title', 'चित्र दीर्घा (Photo Gallery)')
@section('header_title', 'चित्र दीर्घा एवं एल्बम प्रबंधन (Albums & Photo Gallery)')

@section('content')
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 1.7fr 1.3fr;
        gap: 32px;
    }
    @media(max-width: 1200px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="grid-container">
    <!-- Left Column: Album List -->
    <div>
        <div class="panel-card" style="margin-bottom:24px;">
            <div class="panel-header">
                <div class="panel-title"><i class="fa-solid fa-images"></i> एल्बम सूची (Albums List)</div>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>कवर</th>
                            <th>नाम (Name)</th>
                            <th>श्रेणी</th>
                            <th>फ़ोटो संख्या</th>
                            <th>क्रिया</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($albums as $a)
                            <tr>
                                <td>
                                    @if($a->cover_image)
                                        <img src="{{ asset($a->cover_image) }}" style="width:40px; height:40px; object-fit:cover; border-radius:6px; border: 1px solid #e2e8f0;">
                                    @else
                                        <div style="width:40px; height:40px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; border-radius:6px; border: 1px solid #e2e8f0;"><i class="fa-solid fa-image" style="color:#cbd5e1;"></i></div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $a->getLocalized('name') }}</strong>
                                    @if($a->getLocalized('description'))
                                        <br><span style="font-size:11.5px; color:var(--text-light)">{{ $a->getLocalized('description') }}</span>
                                    @endif
                                </td>
                                <td>{{ $a->getLocalized('category') ?? '-' }}</td>
                                <td><span class="badge badge-primary">{{ count($a->photos) }} फ़ोटो</span></td>
                                <td>
                                    <button onclick="editAlbum({{ json_encode($a) }})" class="btn-action btn-action-edit" title="संपादित करें"><i class="fa-solid fa-pen"></i></button>
                                    <a href="{{ url('admin/albums/delete/'.$a->id) }}" onclick="return confirm('क्या आप इस एल्बम और इसकी सभी फ़ोटो मिटाना चाहते हैं?')" class="btn-action btn-action-delete" title="मिटाएं"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; color:var(--text-light)">कोई एल्बम उपलब्ध नहीं है।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Photo list inside albums -->
        @foreach($albums as $a)
            @if(count($a->photos) > 0)
                <div class="panel-card" style="margin-bottom:24px;">
                    <div class="panel-header">
                        <div class="panel-title"><i class="fa-solid fa-image"></i> एल्बम फोटो: {{ $a->getLocalized('name') }}</div>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(110px, 1fr)); gap:16px;">
                        @foreach($a->photos as $p)
                            <div style="position:relative; border:1.5px solid #e2e8f0; border-radius:var(--border-radius-md); padding:6px; text-align:center; background: #fafafa; transition: var(--transition);" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='#e2e8f0'">
                                <img src="{{ asset($p->photo_path) }}" style="width:100%; height:75px; object-fit:cover; border-radius:var(--border-radius-sm); border: 1px solid #e2e8f0;">
                                <p style="font-size:10px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; margin-top:6px; font-weight: 600; color: var(--text-medium);">{{ $p->getLocalized('caption') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Right Column: Add Album & Upload Photo Forms -->
    <div>
        <!-- Add Album -->
        <div class="form-section" style="margin-bottom:24px;">
            <div class="panel-header">
                <div class="panel-title"><i class="fa-solid fa-folder-plus"></i> <span id="form-title">नया एल्बम बनाएं</span></div>
            </div>
            <form action="{{ url('admin/albums/store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="album_id">
                
                <div class="form-group">
                    <label class="form-label">एल्बम नाम (Name - Hindi)</label>
                    <input type="text" name="name_hi" id="album_name_hi" class="form-control-custom" placeholder="उदा: पंचकल्याणक महोत्सव इंदौर" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Name (English)</label>
                    <input type="text" name="name_en" id="album_name_en" class="form-control-custom" placeholder="Example: Indore Panchkalyanak">
                </div>
                <div class="form-group">
                    <label class="form-label">श्रेणी (Category)</label>
                    <input type="text" name="category_hi" id="album_category" class="form-control-custom" placeholder="उदा: महोत्सव, प्रवचन">
                </div>
                <div class="form-group">
                    <label class="form-label">कवर चित्र पथ (Cover Image URL)</label>
                    <input type="text" name="cover_image" id="album_cover" class="form-control-custom" placeholder="/storage/media/cover.jpg">
                </div>
                <div class="form-group">
                    <label class="form-label">संक्षिप्त विवरण (Description)</label>
                    <textarea name="description_hi" id="album_desc" class="form-control-custom" style="height:60px;"></textarea>
                </div>
                <div class="checkbox-group" style="margin-top: -10px; margin-bottom: 24px;">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" id="album_is_active" checked>
                        <span>सक्रिय (Active)</span>
                    </label>
                </div>
                <div style="display: flex; gap: 12px; margin-top: 10px;">
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> सहेजें (Save Album)</button>
                    <button type="button" class="btn-secondary" onclick="resetAlbumForm()">रद्द करें</button>
                </div>
            </form>
        </div>

        <!-- Upload Photos to Album -->
        @if(!$albums->isEmpty())
            <div class="form-section">
                <div class="panel-header">
                    <div class="panel-title"><i class="fa-solid fa-cloud-arrow-up"></i> एल्बम में फ़ोटो जोड़ें</div>
                </div>
                <form action="{{ url('admin/albums/photos/upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">एल्बम चुनें (Select Album)</label>
                        <select name="album_id" class="form-control-custom" required>
                            @foreach($albums as $a)
                                <option value="{{ $a->id }}">{{ $a->getLocalized('name') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">चित्र चुनें (Choose Image File)</label>
                        <input type="file" name="file" class="form-control-custom" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">चित्र कैप्शन/शीर्षक (Caption - optional)</label>
                        <input type="text" name="caption_hi" class="form-control-custom" placeholder="उदा: पूज्य मुनि श्री का मंगल प्रवेश">
                    </div>
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-upload"></i> फ़ोटो अपलोड करें</button>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
    function editAlbum(album) {
        document.getElementById('album_id').value = album.id;
        document.getElementById('album_name_hi').value = album.name.hi || '';
        document.getElementById('album_name_en').value = album.name.en || '';
        document.getElementById('album_category').value = (album.category && album.category.hi) || '';
        document.getElementById('album_cover').value = album.cover_image || '';
        document.getElementById('album_desc').value = (album.description && album.description.hi) || '';
        document.getElementById('album_is_active').checked = album.is_active;
        document.getElementById('form-title').innerText = 'एल्बम विवरण संपादित करें';
    }

    function resetAlbumForm() {
        document.getElementById('album_id').value = '';
        document.getElementById('album_name_hi').value = '';
        document.getElementById('album_name_en').value = '';
        document.getElementById('album_category').value = '';
        document.getElementById('album_cover').value = '';
        document.getElementById('album_desc').value = '';
        document.getElementById('album_is_active').checked = true;
        document.getElementById('form-title').innerText = 'नया एल्बम बनाएं';
    }
</script>
@endsection
