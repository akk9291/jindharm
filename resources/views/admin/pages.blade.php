@extends('layouts.admin')

@section('title', 'डायनामिक पेज (Dynamic Pages)')
@section('header_title', 'डायनामिक वेब पेज प्रबंधन (Dynamic Pages)')

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
    <div class="panel-card">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-file-lines"></i> निर्मित वेब पेज (Pages List)</div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>पेज शीर्षक (Title)</th>
                        <th>स्लग लिंक (Slug URL)</th>
                        <th>स्थिति</th>
                        <th>क्रिया</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td><strong>{{ $page->getLocalized('title') }}</strong></td>
                            <td><code>/page/{{ $page->slug }}</code></td>
                            <td>
                                <span class="badge {{ $page->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $page->is_active ? 'सक्रिय' : 'निष्क्रिय' }}
                                </span>
                            </td>
                            <td>
                                <button onclick="editPage({{ json_encode($page) }})" class="btn-action btn-action-edit" title="संपादित करें"><i class="fa-solid fa-pen"></i></button>
                                <a href="{{ url('admin/pages/delete/'.$page->id) }}" onclick="return confirm('क्या आप इस पेज को मिटाना चाहते हैं?')" class="btn-action btn-action-delete" title="मिटाएं"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:var(--text-light)">कोई पेज उपलब्ध नहीं है।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-section">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-square-plus"></i> <span id="form-title">नया पेज जोड़ें</span></div>
        </div>
        <form action="{{ url('admin/pages/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="page_id">
            
            <div class="form-group">
                <label class="form-label">पेज का शीर्षक (Page Title - Hindi)</label>
                <input type="text" name="title_hi" id="page_title_hi" class="form-control-custom" placeholder="उदा: हमारे बारे में (About Us)" required>
            </div>
            <div class="form-group">
                <label class="form-label">Title (English)</label>
                <input type="text" name="title_en" id="page_title_en" class="form-control-custom" placeholder="Example: About Us">
            </div>
            <div class="form-group">
                <label class="form-label">स्लग (Slug - URL friendly lowercase)</label>
                <input type="text" name="slug" id="page_slug" class="form-control-custom" placeholder="उदा: about-us" required>
            </div>
            <div class="form-group">
                <label class="form-label">मुख्य चित्र (Featured Image)</label>
                <input type="file" name="featured_image" class="form-control-custom" accept="image/*">
            </div>
            <div class="form-group">
                <label class="form-label">पेज की सामग्री (Page Content - HTML/Text)</label>
                <textarea name="content_hi" id="page_content" class="form-control-custom" style="height:150px;" placeholder="यहाँ पेज की संपूर्ण जानकारी लिखें..." required></textarea>
            </div>
            <div class="checkbox-group" style="margin-top: -10px; margin-bottom: 24px;">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" id="page_is_active" checked>
                    <span>सक्रिय (Active)</span>
                </label>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> सहेजें (Save Page)</button>
                <button type="button" class="btn-secondary" onclick="resetPageForm()">रद्द करें</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editPage(page) {
        document.getElementById('page_id').value = page.id;
        document.getElementById('page_title_hi').value = page.title.hi || '';
        document.getElementById('page_title_en').value = page.title.en || '';
        document.getElementById('page_slug').value = page.slug;
        document.getElementById('page_content').value = page.content.hi || '';
        document.getElementById('page_is_active').checked = page.is_active;

        document.getElementById('form-title').innerText = 'पेज संपादित करें';
    }

    function resetPageForm() {
        document.getElementById('page_id').value = '';
        document.getElementById('page_title_hi').value = '';
        document.getElementById('page_title_en').value = '';
        document.getElementById('page_slug').value = '';
        document.getElementById('page_content').value = '';
        document.getElementById('page_is_active').checked = true;
        document.getElementById('form-title').innerText = 'नया पेज जोड़ें';
    }
</script>
@endsection
