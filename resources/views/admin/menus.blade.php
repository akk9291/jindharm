@extends('layouts.admin')

@section('title', 'मेनू बिल्डर (WordPress Style Menu Builder)')
@section('header_title', 'नेविगेशन मेनू बिल्डर (Menu Builder)')

@section('content')
<style>
    /* WordPress Style Menu Builder Layout & Theme */
    .wp-menu-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Top Selector Bar */
    .menu-selector-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--border-radius-md);
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: var(--shadow-sm);
    }
    .menu-selector-bar .selector-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .menu-selector-bar select {
        min-width: 220px;
        padding: 7px 12px;
        font-size: 14px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #f8fafc;
        font-weight: 500;
    }

    /* Two-Column Grid */
    .menu-builder-grid {
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 24px;
        align-items: start;
    }
    @media(max-width: 1024px) {
        .menu-builder-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Left Accordion Column */
    .accordion-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--border-radius-md);
        overflow: hidden;
        margin-bottom: 16px;
        box-shadow: var(--shadow-sm);
    }
    .accordion-header-btn {
        width: 100%;
        background: #f8fafc;
        border: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 16px;
        text-align: left;
        font-size: 14.5px;
        font-weight: 600;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: var(--transition);
    }
    .accordion-header-btn:hover {
        background: #f1f5f9;
        color: var(--primary);
    }
    .accordion-header-btn .fa-chevron-down {
        font-size: 12px;
        transition: transform 0.2s ease;
    }
    .accordion-header-btn[aria-expanded="true"] .fa-chevron-down {
        transform: rotate(180deg);
    }
    .accordion-content {
        padding: 14px 16px;
    }
    .checkbox-list-scroll {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #f1f5f9;
        background: #fafaf9;
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 12px;
    }
    .checkbox-item-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 0;
        font-size: 13.5px;
        border-bottom: 1px dashed #f1f5f9;
    }
    .checkbox-item-row:last-child {
        border-bottom: none;
    }
    .accordion-footer-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
    }
    .accordion-footer-actions a {
        font-size: 12.5px;
        color: var(--text-light);
        text-decoration: underline;
        cursor: pointer;
    }
    .btn-add-to-menu {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        color: var(--text-dark);
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-add-to-menu:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: #fff7ed;
    }

    /* Right Management Column */
    .menu-management-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .menu-management-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .menu-name-field {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-grow: 1;
        max-width: 420px;
    }
    .menu-name-field label {
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
        margin: 0;
    }
    .menu-name-field input {
        width: 100%;
        padding: 8px 14px;
        font-size: 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
    }

    .menu-structure-body {
        padding: 20px;
    }
    .menu-instruction-alert {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        color: #92400e;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13.5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Menu Items Tree (Sortable List) */
    .menu-items-tree {
        list-style: none;
        padding: 0;
        margin: 0;
        min-height: 100px;
    }
    .menu-item-card {
        margin-bottom: 10px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .menu-item-card.is-sub-item {
        margin-left: 36px;
        border-color: #93c5fd;
        background: #f8fafc;
    }
    .menu-item-handle {
        padding: 12px 16px;
        background: #ffffff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: grab;
        user-select: none;
    }
    .menu-item-card.is-sub-item .menu-item-handle {
        background: #f8fafc;
    }
    .menu-item-handle:active {
        cursor: grabbing;
    }
    .item-title-group {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14.5px;
        color: var(--text-charcoal);
    }
    .item-title-group .fa-grip-vertical {
        color: #94a3b8;
        cursor: grab;
    }
    .sub-item-badge {
        font-size: 11px;
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
    }
    .item-type-badge {
        font-size: 12px;
        color: #64748b;
        font-weight: normal;
        margin-right: 12px;
    }
    .btn-toggle-item {
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        transition: var(--transition);
    }
    .btn-toggle-item:hover {
        background: #e2e8f0;
        color: var(--primary);
    }

    /* Expandable Settings Drawer inside Card */
    .menu-item-settings {
        display: none;
        padding: 16px 20px;
        background: #fafaf9;
        border-top: 1px solid #e2e8f0;
        border-radius: 0 0 8px 8px;
    }
    .menu-item-settings.show {
        display: block;
    }
    .item-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 12px;
    }
    @media(max-width: 768px) {
        .item-form-grid {
            grid-template-columns: 1fr;
        }
    }
    .item-form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .item-form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin: 0;
    }
    .item-form-group input, .item-form-group select {
        padding: 7px 12px;
        font-size: 13.5px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #ffffff;
    }
    .item-actions-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 10px;
    }
    .move-buttons-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-move-action {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        padding: 4px 8px;
        font-size: 11.5px;
        border-radius: 4px;
        cursor: pointer;
        color: #475569;
        transition: var(--transition);
    }
    .btn-move-action:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
    .btn-remove-item {
        color: #dc2626;
        font-size: 12.5px;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        text-decoration: underline;
    }
    .btn-remove-item:hover {
        color: #991b1b;
    }

    /* Menu Locations / Settings Section */
    .menu-locations-box {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1.5px solid #e2e8f0;
    }
    .menu-locations-box h4 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--text-dark);
    }
    .location-checkbox-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        font-size: 14px;
    }

    /* Management Footer */
    .menu-management-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .btn-delete-menu {
        color: #dc2626;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: underline;
        background: none;
        border: none;
        cursor: pointer;
    }
    .btn-delete-menu:hover {
        color: #991b1b;
    }
</style>

<div class="wp-menu-wrapper">
    <!-- Top Menu Selector -->
    <div class="menu-selector-bar">
        <form action="{{ url('admin/menus') }}" method="GET" class="selector-group">
            <label for="select-menu-to-edit" style="font-weight:600; font-size:14px;">संपादन के लिए मेनू चुनें (Select Menu):</label>
            <select name="menu" id="select-menu-to-edit" onchange="this.form.submit()">
                @forelse($menus as $m)
                    <option value="{{ $m->id }}" {{ ($currentMenu && $currentMenu->id == $m->id) ? 'selected' : '' }}>
                        {{ $m->name }} @if($m->location) ({{ $m->location }}) @endif
                    </option>
                @empty
                    <option value="">-- कोई मेनू उपलब्ध नहीं --</option>
                @endforelse
            </select>
            <button type="submit" class="btn-secondary" style="padding:7px 14px; font-size:13px;">चुनें (Select)</button>
        </form>

        <div>
            <span style="color:#94a3b8; margin-right:8px;">या</span>
            <a href="{{ url('admin/menus?action=new') }}" class="btn-primary" style="padding:7px 14px; font-size:13px; text-decoration:none;">
                <i class="fa-solid fa-plus me-1"></i> नया मेनू बनाएं (Create New Menu)
            </a>
        </div>
    </div>

    <!-- Main Menu Builder Grid -->
    <div class="menu-builder-grid">
        <!-- LEFT COLUMN: Accordion panels to add items -->
        <div class="menu-accordions-column">
            <div style="font-weight: 700; font-size: 15px; margin-bottom: 12px; color: var(--text-dark);">
                <i class="fa-solid fa-layer-group me-1 text-primary"></i> मेनू में जोड़ने हेतु सामग्री (Add Menu Items)
            </div>

            <!-- Accordion 1: Pages -->
            <div class="accordion-box">
                <button type="button" class="accordion-header-btn" onclick="toggleAccordion(this)" aria-expanded="true">
                    <span><i class="fa-solid fa-file-lines me-2 text-primary"></i> पेज (Pages)</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="accordion-content">
                    <div class="checkbox-list-scroll">
                        @forelse($pages as $page)
                            <label class="checkbox-item-row">
                                <input type="checkbox" class="chk-page" data-title="{{ $page->getLocalized('title') }}" data-url="/page/{{ $page->slug }}" data-type="page">
                                <span>{{ $page->getLocalized('title') }}</span>
                            </label>
                        @empty
                            <div class="text-muted small">कोई पेज उपलब्ध नहीं है।</div>
                        @endforelse
                    </div>
                    <div class="accordion-footer-actions">
                        <a onclick="selectAllInAccordion(this, '.chk-page')">सभी चुनें</a>
                        <button type="button" class="btn-add-to-menu" onclick="addCheckedItems('.chk-page')">मेनू में जोड़ें</button>
                    </div>
                </div>
            </div>

            <!-- Accordion 2: Content Types & Sections -->
            <div class="accordion-box">
                <button type="button" class="accordion-header-btn" onclick="toggleAccordion(this)" aria-expanded="true">
                    <span><i class="fa-solid fa-shapes me-2 text-warning"></i> मॉड्यूल व अनुभाग (Modules)</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="accordion-content">
                    <div class="checkbox-list-scroll" style="max-height: 240px;">
                        @foreach($contentTypes as $cType)
                            <label class="checkbox-item-row">
                                <input type="checkbox" class="chk-content" data-title="{{ $cType['title'] }}" data-url="{{ $cType['url'] }}" data-icon="{{ $cType['icon'] }}" data-type="content_type">
                                <span><i class="{{ $cType['icon'] }} text-muted me-1" style="font-size:12px;"></i> {{ $cType['title'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="accordion-footer-actions">
                        <a onclick="selectAllInAccordion(this, '.chk-content')">सभी चुनें</a>
                        <button type="button" class="btn-add-to-menu" onclick="addCheckedItems('.chk-content')">मेनू में जोड़ें</button>
                    </div>
                </div>
            </div>

            <!-- Accordion 3: Categories -->
            <div class="accordion-box">
                <button type="button" class="accordion-header-btn" onclick="toggleAccordion(this)" aria-expanded="false">
                    <span><i class="fa-solid fa-tags me-2 text-info"></i> श्रेणियाँ (Categories)</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="accordion-content" style="display:none;">
                    <div class="checkbox-list-scroll">
                        @forelse($categories as $cat)
                            <label class="checkbox-item-row">
                                <input type="checkbox" class="chk-cat" data-title="{{ $cat->getLocalized('name') }}" data-url="/category/{{ $cat->slug }}" data-type="category">
                                <span>{{ $cat->getLocalized('name') }}</span>
                            </label>
                        @empty
                            <div class="text-muted small">कोई श्रेणी उपलब्ध नहीं है।</div>
                        @endforelse
                    </div>
                    <div class="accordion-footer-actions">
                        <a onclick="selectAllInAccordion(this, '.chk-cat')">सभी चुनें</a>
                        <button type="button" class="btn-add-to-menu" onclick="addCheckedItems('.chk-cat')">मेनू में जोड़ें</button>
                    </div>
                </div>
            </div>

            <!-- Accordion 4: Featured Saints -->
            <div class="accordion-box">
                <button type="button" class="accordion-header-btn" onclick="toggleAccordion(this)" aria-expanded="false">
                    <span><i class="fa-solid fa-user-tie me-2 text-success"></i> पूज्य साधु-संत (Saints)</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="accordion-content" style="display:none;">
                    <div class="checkbox-list-scroll">
                        @forelse($saints as $saint)
                            <label class="checkbox-item-row">
                                <input type="checkbox" class="chk-saint" data-title="{{ $saint->getLocalized('name') }}" data-url="/sants/{{ $saint->slug }}" data-type="saint">
                                <span>{{ $saint->getLocalized('name') }}</span>
                            </label>
                        @empty
                            <div class="text-muted small">कोई संत रिकॉर्ड उपलब्ध नहीं है।</div>
                        @endforelse
                    </div>
                    <div class="accordion-footer-actions">
                        <a onclick="selectAllInAccordion(this, '.chk-saint')">सभी चुनें</a>
                        <button type="button" class="btn-add-to-menu" onclick="addCheckedItems('.chk-saint')">मेनू में जोड़ें</button>
                    </div>
                </div>
            </div>

            <!-- Accordion 5: Custom Links -->
            <div class="accordion-box">
                <button type="button" class="accordion-header-btn" onclick="toggleAccordion(this)" aria-expanded="false">
                    <span><i class="fa-solid fa-link me-2 text-danger"></i> कस्टम लिंक (Custom Links)</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="accordion-content" style="display:none;">
                    <div class="form-group mb-2">
                        <label class="form-label" style="font-size:12px; margin-bottom:4px;">URL (कड़ी यूआरएल)</label>
                        <input type="text" id="custom_link_url" class="form-control-custom" placeholder="https:// या /custom" value="https://">
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" style="font-size:12px; margin-bottom:4px;">लिंक का नाम (Hindi Text)</label>
                        <input type="text" id="custom_link_text_hi" class="form-control-custom" placeholder="उदा: विशेष सूचना">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-size:12px; margin-bottom:4px;">Link Text (English - Optional)</label>
                        <input type="text" id="custom_link_text_en" class="form-control-custom" placeholder="Example: Special Notice">
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn-add-to-menu" onclick="addCustomLinkItem()">मेनू में जोड़ें</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Menu Structure & Nested Tree -->
        <div class="menu-management-column">
            <form id="menuStructureForm" action="{{ url('admin/menus/save-structure') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $currentMenu ? $currentMenu->id : '' }}">
                <input type="hidden" name="items_data" id="items_data">

                <div class="menu-management-card">
                    <!-- Top Management Header -->
                    <div class="menu-management-header">
                        <div class="menu-name-field">
                            <label for="menu_name">मेनू का नाम:</label>
                            <input type="text" name="menu_name" id="menu_name" value="{{ $currentMenu ? $currentMenu->name : 'मुख्य हेडर मेनू' }}" required placeholder="मेनू का नाम दर्ज करें">
                        </div>
                        <button type="button" class="btn-primary" onclick="submitMenuForm()">
                            <i class="fa-solid fa-floppy-disk me-1"></i> मेनू सहेजें (Save Menu)
                        </button>
                    </div>

                    <!-- Structure Body -->
                    <div class="menu-structure-body">
                        <div class="menu-instruction-alert">
                            <i class="fa-solid fa-circle-info fa-lg"></i>
                            <div>
                                <strong>निर्देश:</strong> बाईं ओर से आइटम चुनें और "मेनू में जोड़ें" दबाएं। आइटमों को अपनी इच्छानुसार क्रमबद्ध करने के लिए ड्रैग करें। सब-मेनू (उप-मेनू) बनाने के लिए आइटम को थोड़ा दाईं ओर खिसकाएं (Indent करें)।
                            </div>
                        </div>

                        <!-- Sortable Menu Items Container -->
                        <div style="font-weight:700; font-size:15px; margin-bottom:12px; color:var(--text-dark);">
                            <i class="fa-solid fa-bars-staggered me-1 text-primary"></i> मेनू संरचना (Menu Structure)
                        </div>

                        <ul id="menuItemsTree" class="menu-items-tree">
                            @if($currentMenu && $currentMenu->allItems->count() > 0)
                                @foreach($currentMenu->allItems as $item)
                                    <li class="menu-item-card {{ $item->parent_id ? 'is-sub-item' : '' }}" 
                                        data-id="{{ $item->id }}"
                                        data-parent-id="{{ $item->parent_id ?: '' }}"
                                        data-label-hi="{{ $item->getLocalized('label', 'hi') }}"
                                        data-label-en="{{ $item->getLocalized('label', 'en') }}"
                                        data-url="{{ $item->url }}"
                                        data-target="{{ $item->target ?: '_self' }}"
                                        data-icon="{{ $item->icon }}"
                                        data-classes="{{ $item->classes }}"
                                        data-type="{{ $item->type ?: 'custom' }}">
                                        
                                        <div class="menu-item-handle">
                                            <div class="item-title-group">
                                                <i class="fa-solid fa-grip-vertical"></i>
                                                <span class="item-title-display">{{ $item->getLocalized('label') }}</span>
                                                <span class="sub-item-badge" style="{{ $item->parent_id ? '' : 'display:none;' }}">उप-मेनू (sub item)</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="item-type-badge">{{ ucfirst($item->type ?: 'Link') }}</span>
                                                <button type="button" class="btn-toggle-item" onclick="toggleItemDrawer(this)" aria-label="विकल्प देखें">
                                                    <i class="fa-solid fa-chevron-down"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="menu-item-settings">
                                            <div class="item-form-grid">
                                                <div class="item-form-group">
                                                    <label>नेविगेशन लेबल (Hindi Title):</label>
                                                    <input type="text" class="field-label-hi" value="{{ $item->getLocalized('label', 'hi') }}" oninput="updateItemCardTitle(this)">
                                                </div>
                                                <div class="item-form-group">
                                                    <label>Navigation Label (English):</label>
                                                    <input type="text" class="field-label-en" value="{{ $item->getLocalized('label', 'en') }}">
                                                </div>
                                                <div class="item-form-group">
                                                    <label>URL (लिंक पता):</label>
                                                    <input type="text" class="field-url" value="{{ $item->url }}">
                                                </div>
                                                <div class="item-form-group">
                                                    <label>आइकन क्लास (FontAwesome Icon Class):</label>
                                                    <input type="text" class="field-icon" value="{{ $item->icon }}" placeholder="उदा: fa-solid fa-book">
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <label class="d-flex align-items-center gap-2 small" style="cursor:pointer;">
                                                    <input type="checkbox" class="field-target" {{ ($item->target === '_blank') ? 'checked' : '' }}>
                                                    <span>नए टैब में खोलें (Open in a new tab)</span>
                                                </label>
                                            </div>

                                            <div class="item-actions-bar">
                                                <div class="move-buttons-group">
                                                    <span style="font-size:11.5px; color:#64748b; margin-right:4px;">स्थानांतरित करें:</span>
                                                    <button type="button" class="btn-move-action" onclick="moveItemCard(this, 'up')" title="ऊपर करें"><i class="fa-solid fa-arrow-up"></i> ऊपर</button>
                                                    <button type="button" class="btn-move-action" onclick="moveItemCard(this, 'down')" title="नीचे करें"><i class="fa-solid fa-arrow-down"></i> नीचे</button>
                                                    <button type="button" class="btn-move-action" onclick="moveItemCard(this, 'indent')" title="उप-मेनू बनाएं"><i class="fa-solid fa-arrow-right"></i> उप-मेनू (Indent)</button>
                                                    <button type="button" class="btn-move-action" onclick="moveItemCard(this, 'outdent')" title="मुख्य मेनू बनाएं"><i class="fa-solid fa-arrow-left"></i> मुख्य स्तर (Outdent)</button>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn-remove-item" onclick="removeItemCard(this)">मेनू से हटाएं (Remove)</button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        <!-- Menu Display Locations -->
                        <div class="menu-locations-box">
                            <h4><i class="fa-solid fa-map-pin me-1 text-primary"></i> मेनू सेटिंग्स / प्रदर्शन स्थान (Menu Settings)</h4>
                            <div class="location-checkbox-row">
                                <input type="checkbox" id="loc_header" name="locations[]" value="header" {{ ($currentMenu && $currentMenu->location === 'header') ? 'checked' : '' }}>
                                <label for="loc_header"><strong>शीर्ष मुख्य नेविगेशन (Header Primary Navigation)</strong> - वेबसाइट के सबसे ऊपर मुख्य मेनू बार</label>
                            </div>
                            <div class="location-checkbox-row">
                                <input type="checkbox" id="loc_footer" name="locations[]" value="footer" {{ ($currentMenu && $currentMenu->location === 'footer') ? 'checked' : '' }}>
                                <label for="loc_footer"><strong>फुटर त्वरित लिंक (Footer Quick Links)</strong></label>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Management Footer -->
                    <div class="menu-management-footer">
                        @if($currentMenu)
                            <a href="{{ url('admin/menus/delete/' . $currentMenu->id) }}" class="btn-delete-menu" onclick="return confirm('क्या आप वाकई यह मेनू मिटाना चाहते हैं?');">
                                <i class="fa-solid fa-trash me-1"></i> मेनू मिटाएं (Delete Menu)
                            </a>
                        @else
                            <div></div>
                        @endif

                        <button type="button" class="btn-primary" onclick="submitMenuForm()">
                            <i class="fa-solid fa-floppy-disk me-1"></i> मेनू सहेजें (Save Menu)
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include SortableJS for Fluid Drag-and-Drop Structure -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
    // Client-side unique ID counter for new items
    let tempIdCounter = 1000;

    // Toggle Left Accordion Panels
    function toggleAccordion(btn) {
        const content = btn.nextElementSibling;
        const isExpanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', !isExpanded);
        content.style.display = isExpanded ? 'none' : 'block';
    }

    // Select all checkboxes within an accordion
    function selectAllInAccordion(link, selector) {
        const container = link.closest('.accordion-content');
        const checkboxes = container.querySelectorAll(selector);
        const allChecked = Array.from(checkboxes).every(c => c.checked);
        checkboxes.forEach(c => c.checked = !allChecked);
        link.textContent = allChecked ? 'सभी चुनें' : 'सभी अचयनित करें';
    }

    // Toggle item settings drawer inside menu card
    function toggleItemDrawer(btn) {
        const card = btn.closest('.menu-item-card');
        const drawer = card.querySelector('.menu-item-settings');
        drawer.classList.toggle('show');
        const icon = btn.querySelector('i');
        if (drawer.classList.contains('show')) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }

    // Live update item card title from input
    function updateItemCardTitle(input) {
        const card = input.closest('.menu-item-card');
        const titleSpan = card.querySelector('.item-title-display');
        titleSpan.textContent = input.value || 'मेनू लिंक';
    }

    // Move Card Actions (Up, Down, Indent, Outdent)
    function moveItemCard(btn, direction) {
        const card = btn.closest('.menu-item-card');
        const tree = document.getElementById('menuItemsTree');

        if (direction === 'up') {
            const prev = card.previousElementSibling;
            if (prev) tree.insertBefore(card, prev);
        } else if (direction === 'down') {
            const next = card.nextElementSibling;
            if (next) tree.insertBefore(next, card);
        } else if (direction === 'indent') {
            // Indent as sub-item
            const prev = card.previousElementSibling;
            if (prev) {
                card.classList.add('is-sub-item');
                card.querySelector('.sub-item-badge').style.display = 'inline-block';
            } else {
                alert('पहला आइटम सब-मेनू नहीं बन सकता।');
            }
        } else if (direction === 'outdent') {
            // Outdent to top-level
            card.classList.remove('is-sub-item');
            card.querySelector('.sub-item-badge').style.display = 'none';
        }
    }

    // Remove Card
    function removeItemCard(btn) {
        if (confirm('क्या आप इस आइटम को मेनू से हटाना चाहते हैं?')) {
            const card = btn.closest('.menu-item-card');
            card.remove();
        }
    }

    // Add Checked Items from Accordion to Menu Tree
    function addCheckedItems(selector) {
        const checkedBoxes = document.querySelectorAll(selector + ':checked');
        if (checkedBoxes.length === 0) {
            alert('कृपया मेनू में जोड़ने के लिए कम से कम एक आइटम चुनें।');
            return;
        }

        checkedBoxes.forEach(chk => {
            const title = chk.getAttribute('data-title');
            const url = chk.getAttribute('data-url');
            const type = chk.getAttribute('data-type') || 'custom';
            const icon = chk.getAttribute('data-icon') || '';
            
            appendMenuItem({
                label_hi: title,
                label_en: title,
                url: url,
                type: type,
                icon: icon,
                target: '_self'
            });

            chk.checked = false; // uncheck after adding
        });
    }

    // Add Custom Link to Menu Tree
    function addCustomLinkItem() {
        const urlInput = document.getElementById('custom_link_url');
        const textHiInput = document.getElementById('custom_link_text_hi');
        const textEnInput = document.getElementById('custom_link_text_en');

        const url = urlInput.value.trim();
        const textHi = textHiInput.value.trim();
        const textEn = textEnInput.value.trim() || textHi;

        if (!url || !textHi) {
            alert('कृपया यूआरएल और लिंक का नाम दोनों भरें।');
            return;
        }

        appendMenuItem({
            label_hi: textHi,
            label_en: textEn,
            url: url,
            type: 'custom',
            icon: 'fa-solid fa-link',
            target: '_self'
        });

        // Reset inputs
        urlInput.value = 'https://';
        textHiInput.value = '';
        textEnInput.value = '';
    }

    // Helper to generate & append item HTML
    function appendMenuItem(item) {
        tempIdCounter++;
        const tree = document.getElementById('menuItemsTree');
        const li = document.createElement('li');
        li.className = 'menu-item-card';
        li.setAttribute('data-id', 'temp_' + tempIdCounter);
        li.setAttribute('data-type', item.type);

        li.innerHTML = `
            <div class="menu-item-handle">
                <div class="item-title-group">
                    <i class="fa-solid fa-grip-vertical"></i>
                    <span class="item-title-display">${item.label_hi}</span>
                    <span class="sub-item-badge" style="display:none;">उप-मेनू (sub item)</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="item-type-badge">${item.type ? item.type.toUpperCase() : 'LINK'}</span>
                    <button type="button" class="btn-toggle-item" onclick="toggleItemDrawer(this)" aria-label="विकल्प देखें">
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>

            <div class="menu-item-settings">
                <div class="item-form-grid">
                    <div class="item-form-group">
                        <label>नेविगेशन लेबल (Hindi Title):</label>
                        <input type="text" class="field-label-hi" value="${item.label_hi}" oninput="updateItemCardTitle(this)">
                    </div>
                    <div class="item-form-group">
                        <label>Navigation Label (English):</label>
                        <input type="text" class="field-label-en" value="${item.label_en}">
                    </div>
                    <div class="item-form-group">
                        <label>URL (लिंक पता):</label>
                        <input type="text" class="field-url" value="${item.url}">
                    </div>
                    <div class="item-form-group">
                        <label>आइकन क्लास (FontAwesome Icon Class):</label>
                        <input type="text" class="field-icon" value="${item.icon || ''}" placeholder="उदा: fa-solid fa-book">
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <label class="d-flex align-items-center gap-2 small" style="cursor:pointer;">
                        <input type="checkbox" class="field-target" ${item.target === '_blank' ? 'checked' : ''}>
                        <span>नए टैब में खोलें (Open in a new tab)</span>
                    </label>
                </div>

                <div class="item-actions-bar">
                    <div class="move-buttons-group">
                        <span style="font-size:11.5px; color:#64748b; margin-right:4px;">स्थानांतरित करें:</span>
                        <button type="button" class="btn-move-action" onclick="moveItemCard(this, 'up')" title="ऊपर करें"><i class="fa-solid fa-arrow-up"></i> ऊपर</button>
                        <button type="button" class="btn-move-action" onclick="moveItemCard(this, 'down')" title="नीचे करें"><i class="fa-solid fa-arrow-down"></i> नीचे</button>
                        <button type="button" class="btn-move-action" onclick="moveItemCard(this, 'indent')" title="उप-मेनू बनाएं"><i class="fa-solid fa-arrow-right"></i> उप-मेनू (Indent)</button>
                        <button type="button" class="btn-move-action" onclick="moveItemCard(this, 'outdent')" title="मुख्य मेनू बनाएं"><i class="fa-solid fa-arrow-left"></i> मुख्य स्तर (Outdent)</button>
                    </div>
                    <div>
                        <button type="button" class="btn-remove-item" onclick="removeItemCard(this)">मेनू से हटाएं (Remove)</button>
                    </div>
                </div>
            </div>
        `;

        tree.appendChild(li);
    }

    // Initialize SortableJS
    document.addEventListener('DOMContentLoaded', function() {
        const tree = document.getElementById('menuItemsTree');
        if (tree) {
            new Sortable(tree, {
                animation: 150,
                handle: '.menu-item-handle',
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    // Item reordered
                }
            });
        }
    });

    // Serialize and Submit Menu Form
    function submitMenuForm() {
        const menuName = document.getElementById('menu_name').value.trim();
        if (!menuName) {
            alert('कृपया मेनू का नाम दर्ज करें।');
            document.getElementById('menu_name').focus();
            return;
        }

        const cards = document.querySelectorAll('#menuItemsTree .menu-item-card');
        const items = [];
        let currentParentClientId = null;

        cards.forEach((card, index) => {
            const isSub = card.classList.contains('is-sub-item');
            const clientId = card.getAttribute('data-id') || ('item_' + (index + 1));
            
            const labelHi = card.querySelector('.field-label-hi') ? card.querySelector('.field-label-hi').value : (card.getAttribute('data-label-hi') || '');
            const labelEn = card.querySelector('.field-label-en') ? card.querySelector('.field-label-en').value : (card.getAttribute('data-label-en') || labelHi);
            const url = card.querySelector('.field-url') ? card.querySelector('.field-url').value : (card.getAttribute('data-url') || '#');
            const icon = card.querySelector('.field-icon') ? card.querySelector('.field-icon').value : (card.getAttribute('data-icon') || '');
            const isBlank = card.querySelector('.field-target') ? card.querySelector('.field-target').checked : (card.getAttribute('data-target') === '_blank');
            const type = card.getAttribute('data-type') || 'custom';

            let parentClientId = null;
            if (isSub) {
                parentClientId = currentParentClientId;
            } else {
                currentParentClientId = clientId;
            }

            items.push({
                client_id: clientId,
                parent_client_id: parentClientId,
                label_hi: labelHi,
                label_en: labelEn,
                url: url,
                icon: icon,
                target: isBlank ? '_blank' : '_self',
                type: type,
                display_order: index + 1
            });
        });

        document.getElementById('items_data').value = JSON.stringify(items);
        document.getElementById('menuStructureForm').submit();
    }
</script>
@endsection
