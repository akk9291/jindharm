@extends('layouts.admin')

@section('title', 'भूमिका प्रबंधन (Role Management)')
@section('header_title', 'भूमिका एवं अनुमति प्रबंधन (Roles & Permissions)')

@section('content')
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 1.15fr 1.85fr;
        gap: 32px;
    }
    @media(max-width: 1200px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }
    .permissions-container {
        background: #fafaf9;
        border-radius: var(--border-radius-md);
        padding: 20px;
        border: 1.5px solid #cbd5e1;
        max-height: 550px;
        overflow-y: auto;
    }
    .permission-group {
        background: white;
        border-radius: var(--border-radius-sm);
        border: 1px solid #e2e8f0;
        padding: 16px;
        margin-bottom: 16px;
    }
    .permission-group-title {
        font-weight: 700;
        font-size: 13.5px;
        color: var(--text-dark);
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 8px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .permission-checkboxes {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 12px;
    }
    .badge-permission-count {
        background: var(--primary-light);
        color: var(--primary);
        border: 1px solid rgba(234, 88, 12, 0.2);
        font-size: 12px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 12px;
    }
    /* Modal styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
    }
    .modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }
    .modal-card {
        background: white;
        width: 100%;
        max-width: 450px;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(226, 232, 240, 0.8);
        overflow: hidden;
        transform: translateY(20px);
        transition: all 0.3s ease;
    }
    .modal-overlay.active .modal-card {
        transform: translateY(0);
    }
    .modal-header {
        background: #fafaf9;
        padding: 20px 28px;
        border-bottom: 1.5px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
    }
    .modal-close {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-light);
        font-size: 18px;
    }
    .modal-body {
        padding: 28px;
    }
</style>

<div class="grid-container">
    <!-- Roles List -->
    <div class="panel-card">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-user-shield"></i> सक्रिय भूमिकाएं (Active Roles)</div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>नाम (Role)</th>
                        <th>अनुमतियां (Perms)</th>
                        <th>क्रिया (Actions)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>
                                <strong>{{ $role->name }}</strong>
                            </td>
                            <td>
                                <span class="badge-permission-count">{{ $role->permissions->count() }} अनुमतियां</span>
                            </td>
                            <td>
                                <button onclick="editRole({{ json_encode($role) }}, {{ json_encode($role->permissions->pluck('name')) }})" class="btn-action btn-action-edit" title="संपादित करें"><i class="fa-solid fa-pen"></i></button>
                                <button onclick="openCloneModal({{ $role->id }}, '{{ $role->name }}')" class="btn-action" style="color: #0d9488" title="भूमिका क्लोन करें"><i class="fa-regular fa-copy"></i></button>
                                
                                @if(!in_array($role->name, ['Super Admin', 'Admin', 'Viewer', 'Content Manager', 'Media Manager', 'Location Manager', 'SEO Manager']))
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('क्या आप वाकई इस भूमिका को हटाना चाहते हैं?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="मिटाएं"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Role Form -->
    <div class="form-section">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-shield-halved"></i> <span id="form-title">नयी भूमिका बनाएं</span></div>
            <button type="button" id="reset-form-btn" onclick="resetForm()" class="btn-secondary" style="padding: 6px 12px; font-size: 12px; display: none;">नयी बनाएं</button>
        </div>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="role_id">

            <div class="form-group">
                <label class="form-label">भूमिका नाम (Role Name - English)</label>
                <input type="text" name="name" id="role_name" class="form-control-custom" placeholder="उदा: Saints Editor" required>
                <span style="font-size: 11px; color: var(--text-light);">अक्षरों के बीच स्पेस हो सकता है (उदा: Content Manager)</span>
            </div>

            <div class="form-group">
                <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>अनुमतियां आवंटित करें (Assign Permissions)</span>
                    <span style="font-size: 12px;">
                        <a href="javascript:void(0)" onclick="toggleAllPermissions(true)" style="color: var(--primary); text-decoration: none; font-weight: 600; margin-right: 12px;">सभी चुनें</a>
                        <a href="javascript:void(0)" onclick="toggleAllPermissions(false)" style="color: var(--text-light); text-decoration: none; font-weight: 600;">सभी हटाएं</a>
                    </span>
                </label>

                <div class="permissions-container">
                    @foreach($modules as $moduleName => $permissions)
                        <div class="permission-group">
                            <div class="permission-group-title">
                                <span>{{ $moduleName }}</span>
                                <span style="font-size: 11px;">
                                    <a href="javascript:void(0)" onclick="toggleModulePermissions(this, true)" style="color: var(--primary); text-decoration: none; font-weight: 500; margin-right: 8px;">चुनें</a>
                                    <a href="javascript:void(0)" onclick="toggleModulePermissions(this, false)" style="color: var(--text-light); text-decoration: none; font-weight: 500;">हटाएं</a>
                                </span>
                            </div>
                            <div class="permission-checkboxes">
                                @foreach($permissions as $perm)
                                    @php
                                        // Get action name e.g. "view", "create", etc.
                                        $action = explode('.', $perm)[1] ?? $perm;
                                        $friendlyAction = [
                                            'view' => 'देखें (View)',
                                            'create' => 'बनाएं (Create)',
                                            'upload' => 'अपलोड (Upload)',
                                            'edit' => 'बदलें (Edit)',
                                            'delete' => 'मिटाएं (Delete)',
                                            'publish' => 'प्रकाशित (Publish)',
                                            'manage' => 'प्रबंधन (Manage)',
                                        ][$action] ?? ucfirst($action);
                                    @endphp
                                    <label class="checkbox-label" style="font-size: 12px;">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm }}" class="perm-checkbox">
                                        <span>{{ $friendlyAction }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="text-align: right; margin-top: 30px;">
                <button type="submit" class="btn-primary" style="width: 100%"><i class="fa-solid fa-circle-check"></i> भूमिका सहेजें (Save Role)</button>
            </div>
        </form>
    </div>
</div>

<!-- Clone Role Modal -->
<div class="modal-overlay" id="clone-modal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-regular fa-copy"></i> भूमिका का क्लोन बनाएं</h3>
            <button type="button" class="modal-close" onclick="closeCloneModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="clone-form" action="" method="POST">
                @csrf
                <div class="form-group">
                    <p style="font-size: 13.5px; margin-bottom: 16px; color: var(--text-medium)">
                        भूमिका '<strong id="clone-source-name"></strong>' की सभी अनुमतियों के साथ एक नयी भूमिका बनाई जाएगी।
                    </p>
                    <label class="form-label">नयी भूमिका का नाम (New Role Name)</label>
                    <input type="text" name="new_name" class="form-control-custom" placeholder="उदा: Assistant Content Manager" required>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="button" class="btn-secondary" onclick="closeCloneModal()" style="margin-right: 8px;">रद्द करें</button>
                    <button type="submit" class="btn-primary">क्लोन करें (Clone Role)</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editRole(role, permissions) {
        document.getElementById('form-title').innerText = 'भूमिका संपादित करें';
        document.getElementById('reset-form-btn').style.display = 'inline-block';

        document.getElementById('role_id').value = role.id;
        document.getElementById('role_name').value = role.name;

        // Reset checkboxes first
        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            cb.checked = false;
        });

        // Set role permissions
        if (permissions && permissions.length > 0) {
            permissions.forEach(perm => {
                const cb = document.querySelector(`.perm-checkbox[value="${perm}"]`);
                if (cb) cb.checked = true;
            });
        }
    }

    function resetForm() {
        document.getElementById('form-title').innerText = 'नयी भूमिका बनाएं';
        document.getElementById('reset-form-btn').style.display = 'none';

        document.getElementById('role_id').value = '';
        document.getElementById('role_name').value = '';

        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            cb.checked = false;
        });
    }

    // Toggle permission helper functions
    function toggleAllPermissions(checked) {
        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            cb.checked = checked;
        });
    }

    function toggleModulePermissions(btnLink, checked) {
        // Find parent .permission-group and select checkboxes within it
        const group = btnLink.closest('.permission-group');
        if (group) {
            group.querySelectorAll('.perm-checkbox').forEach(cb => {
                cb.checked = checked;
            });
        }
    }

    // Modal helpers
    function openCloneModal(roleId, roleName) {
        document.getElementById('clone-source-name').innerText = roleName;
        document.getElementById('clone-form').action = '{{ asset("admin/roles/clone") }}/' + roleId;
        document.getElementById('clone-modal').classList.add('active');
    }

    function closeCloneModal() {
        document.getElementById('clone-modal').classList.remove('active');
    }
</script>
@endsection
