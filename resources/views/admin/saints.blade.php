@extends('layouts.admin')

@section('title', 'साधु प्रबंधन (Saint Management)')
@section('header_title', 'साधु प्रबंधन एवं गुरु परम्परा (Saints & Guru Parampara)')

@section('content')
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 32px;
    }
    @media(max-width: 1200px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }
    .parampara-tree {
        background: #0f172a;
        color: #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        font-family: monospace;
        line-height: 1.8;
    }
    .tree-node {
        margin-left: 20px;
        border-left: 2px dashed rgba(255,255,255,0.15);
        padding-left: 15px;
        position: relative;
    }
    .tree-node::before {
        content: "├── ";
        position: absolute;
        left: 0;
        color: var(--primary);
    }
    .tree-node:last-child {
        border-left: none;
    }
    .tree-node:last-child::before {
        content: "└── ";
    }
    .node-content {
        background: rgba(255,255,255,0.05);
        padding: 4px 10px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Inter', 'Noto Sans Devanagari', sans-serif;
        font-size: 13px;
        color: #fff;
    }
    .node-content i {
        color: #f97316;
    }
</style>

<div class="grid-container">
    <!-- Left Column: Saint List & Guru Parampara Tree -->
    <div>
        <!-- Visual Tree -->
        <div class="panel-card" style="margin-bottom: 24px;">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fa-solid fa-sitemap"></i>
                    <span>गुरु परम्परा वृक्ष (Guru Parampara Tree)</span>
                </div>
            </div>
            <div class="parampara-tree">
                @php
                    $roots = $saints->whereNull('parent_id');
                    
                    function renderNode($node, $allSaints) {
                        echo '<div class="tree-node">';
                        echo '<div class="node-content"><i class="fa-solid fa-om"></i> <strong>' . $node->getLocalized('title') . ' ' . $node->getLocalized('name') . '</strong></div>';
                        $children = $allSaints->where('parent_id', $node->id);
                        foreach ($children as $child) {
                            renderNode($child, $allSaints);
                        }
                        echo '</div>';
                    }
                @endphp
                @if($roots->isEmpty())
                    <p style="color: rgba(255,255,255,0.4); text-align: center;">वृक्ष दिखाने के लिए साधु संघ जोड़ें।</p>
                @else
                    @foreach($roots as $root)
                        <div style="margin-bottom: 14px;">
                            <div class="node-content" style="background: rgba(234,88,12,0.15); border: 1px solid rgba(234,88,12,0.3); font-size:14px; padding: 6px 12px;">
                                <i class="fa-solid fa-dharmachakra"></i> 
                                <strong>{{ $root->getLocalized('title') }} {{ $root->getLocalized('name') }} (मूल आचार्य)</strong>
                            </div>
                            @foreach($saints->where('parent_id', $root->id) as $child)
                                @php renderNode($child, $saints); @endphp
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Listing Table -->
        <div class="panel-card">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fa-solid fa-list"></i>
                    <span>साधु सूची (Saints List)</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>फ़ोटो</th>
                            <th>नाम (Name)</th>
                            <th>दीक्षा तिथि</th>
                            <th>क्रम</th>
                            <th>स्थिति</th>
                            <th>क्रिया</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($saints as $saint)
                            <tr>
                                <td>
                                    @if($saint->photo)
                                        <img src="{{ asset($saint->photo) }}" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border: 1.5px solid var(--primary);">
                                    @else
                                        <div style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg, #f97316, #ea580c); display:flex; align-items:center; justify-content:center; color:white; font-size:12px; font-weight: 600; box-shadow: 0 2px 6px rgba(234, 88, 12, 0.25);">सा</div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $saint->getLocalized('title') }} {{ $saint->getLocalized('name') }}</strong>
                                    @if($saint->parent)
                                        <br><span style="font-size:11.5px; color:var(--text-light)">शिष्य: {{ $saint->parent->getLocalized('name') }}</span>
                                    @endif
                                </td>
                                <td>{{ $saint->diksha_date ? $saint->diksha_date->format('d-M-Y') : '-' }}</td>
                                <td>{{ $saint->display_order }}</td>
                                <td>
                                    <span class="badge {{ $saint->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $saint->is_active ? 'सक्रिय' : 'निष्क्रिय' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-action btn-action-edit" onclick="editSaint({{ json_encode($saint) }})" title="संपादित करें"><i class="fa-solid fa-pen"></i></button>
                                    <a href="{{ url('admin/saints/delete/'.$saint->id) }}" onclick="return confirm('क्या आप इस साधु को मिटाना चाहते हैं?')" class="btn-action btn-action-delete" title="मिटाएं"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; color:var(--text-light)">कोई साधु दर्ज नहीं है।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Add/Edit Form -->
    <div class="form-section">
        <div class="panel-header">
            <div class="panel-title">
                <i class="fa-solid fa-user-plus"></i>
                <span id="form-action-title">नया साधु जोड़ें (Add New Saint)</span>
            </div>
        </div>
        <form action="{{ url('admin/saints/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="saint_id">

            <div class="row-flex">
                <div>
                    <label class="form-label">शीर्षक (Title - उदा: आचार्य, मुनि)</label>
                    <input type="text" name="title_hi" id="title_hi" class="form-control-custom" placeholder="उदा: आचार्य श्री" required>
                </div>
                <div>
                    <label class="form-label">नाम (Name in Hindi)</label>
                    <input type="text" name="name_hi" id="name_hi" class="form-control-custom" placeholder="उदा: विद्यासागर" required>
                </div>
            </div>

            <div class="row-flex">
                <div>
                    <label class="form-label">Name (English)</label>
                    <input type="text" name="name_en" id="name_en" class="form-control-custom" placeholder="Example: Vidyasagar">
                </div>
                <div>
                    <label class="form-label">गुरु नाम (Guru Name)</label>
                    <input type="text" name="guru_name_hi" id="guru_name_hi" class="form-control-custom" placeholder="उदा: आचार्य ज्ञानसागर">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">गुरु परम्परा में गुरु चुनें (Parent Guru in Tree)</label>
                <select name="parent_id" id="parent_id" class="form-control-custom">
                    <option value="">कोई नहीं (स्वतंत्र/मूल आचार्य)</option>
                    @foreach($saints as $s)
                        <option value="{{ $s->id }}">{{ $s->getLocalized('title') }} {{ $s->getLocalized('name') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row-flex">
                <div>
                    <label class="form-label">दीक्षा तिथि (Diksha Date)</label>
                    <input type="date" name="diksha_date" id="diksha_date" class="form-control-custom">
                </div>
                <div>
                    <label class="form-label">क्रम (Display Order)</label>
                    <input type="number" name="display_order" id="display_order" class="form-control-custom" value="0">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">साधु फ़ोटो (Photo)</label>
                <input type="file" name="photo" class="form-control-custom" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-label">संक्षिप्त परिचय (Introduction)</label>
                <textarea name="introduction_hi" id="introduction_hi" class="form-control-custom" style="height:80px;"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">जीवनी (Biography)</label>
                <textarea name="biography_hi" id="biography_hi" class="form-control-custom" style="height:120px;"></textarea>
            </div>

            <div class="checkbox-group" style="margin-top: -10px; margin-bottom: 24px;">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" id="is_active" checked>
                    <span>सक्रिय (Active)</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="is_featured" id="is_featured">
                    <span>मुख्य साधु (Featured)</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="show_on_website" id="show_on_website" checked>
                    <span>वेब पर दिखाएं</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="show_on_app" id="show_on_app" checked>
                    <span>ऐप पर दिखाएं</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> सहेजें (Save Saint)</button>
                <button type="button" class="btn-secondary" onclick="resetForm()">रद्द करें (Cancel)</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editSaint(saint) {
        document.getElementById('saint_id').value = saint.id;
        document.getElementById('title_hi').value = saint.title.hi || '';
        document.getElementById('name_hi').value = saint.name.hi || '';
        document.getElementById('name_en').value = saint.name.en || '';
        document.getElementById('guru_name_hi').value = (saint.guru_name && saint.guru_name.hi) || '';
        document.getElementById('parent_id').value = saint.parent_id || '';
        document.getElementById('display_order').value = saint.display_order || 0;
        document.getElementById('introduction_hi').value = (saint.introduction && saint.introduction.hi) || '';
        document.getElementById('biography_hi').value = (saint.biography && saint.biography.hi) || '';
        
        document.getElementById('is_active').checked = saint.is_active;
        document.getElementById('is_featured').checked = saint.is_featured;
        document.getElementById('show_on_website').checked = saint.show_on_website;
        document.getElementById('show_on_app').checked = saint.show_on_app;
        
        if(saint.diksha_date) {
            document.getElementById('diksha_date').value = saint.diksha_date.split('T')[0];
        } else {
            document.getElementById('diksha_date').value = '';
        }

        document.getElementById('form-action-title').innerText = 'साधु जानकारी बदलें (Edit Saint)';
    }

    function resetForm() {
        document.getElementById('saint_id').value = '';
        document.getElementById('title_hi').value = '';
        document.getElementById('name_hi').value = '';
        document.getElementById('name_en').value = '';
        document.getElementById('guru_name_hi').value = '';
        document.getElementById('parent_id').value = '';
        document.getElementById('display_order').value = 0;
        document.getElementById('introduction_hi').value = '';
        document.getElementById('biography_hi').value = '';
        document.getElementById('diksha_date').value = '';
        
        document.getElementById('is_active').checked = true;
        document.getElementById('is_featured').checked = false;
        document.getElementById('show_on_website').checked = true;
        document.getElementById('show_on_app').checked = true;

        document.getElementById('form-action-title').innerText = 'नया साधु जोड़ें (Add New Saint)';
    }
</script>
@endsection
