@extends('layouts.admin')

@section('title', 'कंटेंट प्रकार (Content Types)')
@section('header_title', 'कंटेंट प्रकार प्रबंधन (Content Types)')

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
            <div class="panel-title"><i class="fa-solid fa-sliders"></i> सक्रिय कंटेंट प्रकार (Active Content Types)</div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>नाम (Name)</th>
                        <th>स्लग (Slug)</th>
                        <th>आइकन (Icon)</th>
                        <th>क्रम</th>
                        <th>क्रिया</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($types as $type)
                        <tr>
                            <td><strong>{{ $type->getLocalized('name') }}</strong></td>
                            <td><code>{{ $type->slug }}</code></td>
                            <td><i class="fa-solid fa-{{ $type->icon }}" style="color: var(--primary);"></i></td>
                            <td>{{ $type->display_order }}</td>
                            <td>
                                <button onclick="editType({{ json_encode($type) }})" class="btn-action btn-action-edit" title="संपादित करें"><i class="fa-solid fa-pen"></i></button>
                                <a href="{{ url('admin/content-types/delete/'.$type->id) }}" onclick="return confirm('क्या आप इसे मिटाना चाहते हैं?')" class="btn-action btn-action-delete" title="मिटाएं"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-section">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-square-plus"></i> <span id="form-title">नया प्रकार जोड़ें</span></div>
        </div>
        <form action="{{ url('admin/content-types/store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="type_id">
            
            <div class="form-group">
                <label class="form-label">प्रकार नाम (Name - Hindi)</label>
                <input type="text" name="name_hi" id="type_name_hi" class="form-control-custom" placeholder="उदा: ग्रन्थ" required>
            </div>
            <div class="form-group">
                <label class="form-label">Name (English)</label>
                <input type="text" name="name_en" id="type_name_en" class="form-control-custom" placeholder="Example: Granth">
            </div>
            <div class="form-group">
                <label class="form-label">स्लग (Slug - English lowercase)</label>
                <input type="text" name="slug" id="type_slug" class="form-control-custom" placeholder="उदा: granth" required>
            </div>
            <div class="form-group">
                <label class="form-label">आइकन क्लास (Font Awesome Icon Name)</label>
                <input type="text" name="icon" id="type_icon" class="form-control-custom" placeholder="उदा: book-open" value="file-lines">
            </div>
            <div class="form-group">
                <label class="form-label">क्रम (Display Order)</label>
                <input type="number" name="display_order" id="type_order" class="form-control-custom" value="0">
            </div>
            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> सहेजें (Save Content Type)</button>
                <button type="button" class="btn-secondary" onclick="resetTypeForm()">रद्द करें</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editType(type) {
        document.getElementById('type_id').value = type.id;
        document.getElementById('type_name_hi').value = type.name.hi || '';
        document.getElementById('type_name_en').value = type.name.en || '';
        document.getElementById('type_slug').value = type.slug;
        document.getElementById('type_icon').value = type.icon || 'file-lines';
        document.getElementById('type_order').value = type.display_order || 0;
        document.getElementById('form-title').innerText = 'कंटेंट प्रकार संपादित करें';
    }

    function resetTypeForm() {
        document.getElementById('type_id').value = '';
        document.getElementById('type_name_hi').value = '';
        document.getElementById('type_name_en').value = '';
        document.getElementById('type_slug').value = '';
        document.getElementById('type_icon').value = 'file-lines';
        document.getElementById('type_order').value = 0;
        document.getElementById('form-title').innerText = 'नया प्रकार जोड़ें';
    }
</script>
@endsection
