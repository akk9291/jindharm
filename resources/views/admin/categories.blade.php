@extends('layouts.admin')

@section('title', 'श्रेणी प्रबंधन (Categories)')
@section('header_title', 'कंटेंट श्रेणी प्रबंधन (Category Management)')

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
            <div class="panel-title"><i class="fa-solid fa-tags"></i> सक्रिय श्रेणियां (Active Categories)</div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>कंटेंट प्रकार</th>
                        <th>नाम (Name)</th>
                        <th>स्लग (Slug)</th>
                        <th>मूल श्रेणी (Parent)</th>
                        <th>क्रम</th>
                        <th>क्रिया</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td><strong>{{ $cat->contentType->getLocalized('name') }}</strong></td>
                            <td><strong>{{ $cat->getLocalized('name') }}</strong></td>
                            <td><code>{{ $cat->slug }}</code></td>
                            <td>
                                @if($cat->parent)
                                    <span class="badge badge-secondary">{{ $cat->parent->getLocalized('name') }}</span>
                                @else
                                    <span class="badge badge-primary">मुख्य श्रेणी</span>
                                @endif
                            </td>
                            <td>{{ $cat->display_order }}</td>
                            <td>
                                <button onclick="editCategory({{ json_encode($cat) }})" class="btn-action btn-action-edit" title="संपादित करें"><i class="fa-solid fa-pen"></i></button>
                                <a href="{{ url('admin/categories/delete/'.$cat->id) }}" onclick="return confirm('क्या आप इसे मिटाना चाहते हैं?')" class="btn-action btn-action-delete" title="मिटाएं"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; color:var(--text-light)">कोई श्रेणी उपलब्ध नहीं है।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-section">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-tag"></i> <span id="form-title">नयी श्रेणी जोड़ें</span></div>
        </div>
        <form action="{{ url('admin/categories/store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="cat_id">
            
            <div class="form-group">
                <label class="form-label">कंटेंट प्रकार (Content Type)</label>
                <select name="content_type_id" id="cat_type" class="form-control-custom" required>
                    <option value="">प्रकार चुनें</option>
                    @foreach($types as $t)
                        <option value="{{ $t->id }}">{{ $t->getLocalized('name') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">मूल श्रेणी (Parent Category - optional)</label>
                <select name="parent_id" id="cat_parent" class="form-control-custom">
                    <option value="">कोई नहीं (मुख्य श्रेणी)</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->contentType->getLocalized('name') }} -> {{ $c->getLocalized('name') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">श्रेणी नाम (Name - Hindi)</label>
                <input type="text" name="name_hi" id="cat_name_hi" class="form-control-custom" placeholder="उदा: जिन भक्ति" required>
            </div>

            <div class="form-group">
                <label class="form-label">Name (English)</label>
                <input type="text" name="name_en" id="cat_name_en" class="form-control-custom" placeholder="Example: Jin Bhakti">
            </div>

            <div class="form-group">
                <label class="form-label">स्लग (Slug - English lowercase)</label>
                <input type="text" name="slug" id="cat_slug" class="form-control-custom" placeholder="उदा: jin-bhakti" required>
            </div>

            <div class="form-group">
                <label class="form-label">क्रम (Display Order)</label>
                <input type="number" name="display_order" id="cat_order" class="form-control-custom" value="0">
            </div>

            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> सहेजें (Save Category)</button>
                <button type="button" class="btn-secondary" onclick="resetCategoryForm()">रद्द करें</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editCategory(cat) {
        document.getElementById('cat_id').value = cat.id;
        document.getElementById('cat_type').value = cat.content_type_id;
        document.getElementById('cat_parent').value = cat.parent_id || '';
        document.getElementById('cat_name_hi').value = cat.name.hi || '';
        document.getElementById('cat_name_en').value = cat.name.en || '';
        document.getElementById('cat_slug').value = cat.slug;
        document.getElementById('cat_order').value = cat.display_order || 0;
        document.getElementById('form-title').innerText = 'श्रेणी विवरण संपादित करें';
    }

    function resetCategoryForm() {
        document.getElementById('cat_id').value = '';
        document.getElementById('cat_type').value = '';
        document.getElementById('cat_parent').value = '';
        document.getElementById('cat_name_hi').value = '';
        document.getElementById('cat_name_en').value = '';
        document.getElementById('cat_slug').value = '';
        document.getElementById('cat_order').value = 0;
        document.getElementById('form-title').innerText = 'नयी श्रेणी जोड़ें';
    }
</script>
@endsection
