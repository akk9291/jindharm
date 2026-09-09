@extends('layouts.admin')

@section('title', 'होमपेज बिल्डर (Homepage Builder)')
@section('header_title', 'डायनामिक होमपेज बिल्डर (Homepage Layout Builder)')

@section('content')
<style>
    .section-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .section-row {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
    }
    .section-row:hover {
        border-color: var(--primary);
    }
    .section-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .section-drag-handle {
        cursor: grab;
        color: #94a3b8;
        font-size: 18px;
    }
    .section-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #fff7ed;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .section-controls {
        display: flex;
        align-items: center;
        gap: 24px;
    }
    .toggle-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #f1f5f9;
        padding: 6px 12px;
        border-radius: 20px;
        cursor: pointer;
        transition: var(--transition);
        border: 1px solid transparent;
    }
    .toggle-pill.active {
        background: #dcfce7;
        color: #15803d;
    }
    .toggle-pill.inactive {
        background: #fee2e2;
        color: #b91c1c;
    }
    .btn-move {
        background: none;
        border: none;
        color: var(--text-light);
        cursor: pointer;
        padding: 4px;
        font-size: 14px;
    }
    .btn-move:hover {
        color: var(--primary);
    }
</style>

<div class="panel-card">
    <div class="panel-header">
        <div class="panel-title">
            <i class="fa-solid fa-house-laptop"></i>
            <span>होमपेज अनुभाग प्रबंधन (Homepage Layout Builder)</span>
        </div>
        <p style="font-size:12px; color:var(--text-light)">यहाँ से आप वेबसाइट और मोबाइल ऐप के होमपेज अनुभागों का क्रम और दृश्यता (Visibility) नियंत्रित कर सकते हैं।</p>
    </div>

    <div class="section-list" id="sectionList">
        @foreach($sections as $index => $section)
            <div class="section-row" data-id="{{ $section->id }}">
                <div class="section-info">
                    <span class="section-drag-handle"><i class="fa-solid fa-grip-vertical"></i></span>
                    <div class="section-icon">
                        <i class="fa-solid fa-cubes"></i>
                    </div>
                    <div>
                        <strong style="font-size:15px; color:var(--text-dark)">{{ $section->getLocalized('label') }}</strong>
                        <br><span style="font-size:11px; color:var(--text-light)">Key: {{ $section->section_key }}</span>
                    </div>
                </div>

                <div class="section-controls">
                    <!-- Toggle Switch buttons -->
                    <div onclick="toggleField({{ $section->id }}, 'is_active', this)" class="toggle-pill {{ $section->is_active ? 'active' : 'inactive' }}">
                        <i class="fa-solid {{ $section->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                        <span>सक्रिय (Active)</span>
                    </div>
                    
                    <div onclick="toggleField({{ $section->id }}, 'show_on_website', this)" class="toggle-pill {{ $section->show_on_website ? 'active' : 'inactive' }}">
                        <i class="fa-solid fa-globe"></i>
                        <span>वेबसाइट</span>
                    </div>

                    <div onclick="toggleField({{ $section->id }}, 'show_on_app', this)" class="toggle-pill {{ $section->show_on_app ? 'active' : 'inactive' }}">
                        <i class="fa-solid fa-mobile-screen"></i>
                        <span>मोबाइल ऐप</span>
                    </div>

                    <!-- Move Controls -->
                    <div style="display:flex; flex-direction:column; gap:4px;">
                        @if($index > 0)
                            <button class="btn-move" onclick="moveUp(this)"><i class="fa-solid fa-chevron-up"></i></button>
                        @endif
                        @if($index < count($sections) - 1)
                            <button class="btn-move" onclick="moveDown(this)"><i class="fa-solid fa-chevron-down"></i></button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function toggleField(id, field, element) {
        fetch('{{ url("admin/homepage/toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id, field: field })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update CSS styling of clicked toggler dynamically
                if (element.classList.contains('active')) {
                    element.classList.remove('active');
                    element.classList.add('inactive');
                    if (field === 'is_active') {
                        element.querySelector('i').className = 'fa-solid fa-circle-xmark';
                    }
                } else {
                    element.classList.remove('inactive');
                    element.classList.add('active');
                    if (field === 'is_active') {
                        element.querySelector('i').className = 'fa-solid fa-circle-check';
                    }
                }
            }
        });
    }

    function moveUp(button) {
        const row = button.closest('.section-row');
        const previous = row.previousElementSibling;
        if (previous) {
            row.parentNode.insertBefore(row, previous);
            saveSortingOrder();
        }
    }

    function moveDown(button) {
        const row = button.closest('.section-row');
        const next = row.nextElementSibling;
        if (next) {
            row.parentNode.insertBefore(next, row);
            saveSortingOrder();
        }
    }

    function saveSortingOrder() {
        const rows = document.querySelectorAll('.section-row');
        const order = Array.from(rows).map(row => row.dataset.id);

        fetch('{{ url("admin/homepage/sort") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order: order })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Flash message reload or display
                console.log('Sorting saved successfully!');
            }
        });
    }
</script>
@endsection
