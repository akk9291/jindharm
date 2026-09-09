@extends('layouts.admin')

@section('title', 'उपयोगकर्ता प्रबंधन (User Management)')
@section('header_title', 'प्रणाली उपयोगकर्ता प्रबंधन (System Users)')

@section('content')
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 1.6fr 1.4fr;
        gap: 32px;
    }
    @media(max-width: 1200px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }
    .profile-preview {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary);
        margin-bottom: 12px;
        display: none;
    }
    .user-avatar-list {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        font-weight: 700;
        color: var(--primary);
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
        max-width: 480px;
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
    .modal-close:hover {
        color: var(--text-dark);
    }
    .modal-body {
        padding: 28px;
    }
</style>

<div class="grid-container">
    <!-- Users List Table -->
    <div class="panel-card">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-users"></i> सक्रिय उपयोगकर्ता (Active Users)</div>
            
            <form action="{{ route('users.index') }}" method="GET" style="display: flex; gap: 8px; width: 50%; max-width: 250px;">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control-custom" placeholder="खोजें (Search)..." style="padding: 8px 12px; font-size: 13px;">
                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 13px; border-radius: var(--border-radius-sm)"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>अवतार</th>
                        <th>विवरण (User Info)</th>
                        <th>भूमिका (Role)</th>
                        <th>स्थिति (Status)</th>
                        <th>क्रिया (Actions)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                @if($user->profile_photo)
                                    <img src="{{ asset($user->profile_photo) }}" class="user-avatar-list" alt="{{ $user->name }}">
                                @else
                                    <div class="user-avatar-list">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div><strong>{{ $user->name }}</strong></div>
                                <div style="font-size: 12px; color: var(--text-light);"><i class="fa-regular fa-envelope"></i> {{ $user->email }}</div>
                                @if($user->mobile)
                                    <div style="font-size: 12px; color: var(--text-light);"><i class="fa-solid fa-phone"></i> {{ $user->mobile }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $user->roles->first()->name ?? 'No Role' }}</span>
                            </td>
                            <td>
                                @if($user->status)
                                    <span class="badge badge-success">सक्रिय</span>
                                @else
                                    <span class="badge badge-danger">निष्क्रिय</span>
                                @endif
                            </td>
                            <td>
                                <button onclick="editUser({{ json_encode($user) }}, '{{ $user->roles->first()->name ?? '' }}')" class="btn-action btn-action-edit" title="संपादित करें"><i class="fa-solid fa-pen"></i></button>
                                <button onclick="openResetModal({{ $user->id }}, '{{ $user->name }}')" class="btn-action" style="color: #ea580c" title="पासवर्ड बदलें"><i class="fa-solid fa-key"></i></button>
                                @if($user->id !== 1 && !$user->hasRole('Super Admin'))
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('क्या आप वाकई इस उपयोगकर्ता को हटाना चाहते हैं?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="मिटाएं"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:var(--text-light)">कोई उपयोगकर्ता नहीं मिला।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 20px;">
            {{ $users->appends(request()->input())->links() }}
        </div>
    </div>

    <!-- User Form -->
    <div class="form-section">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-user-plus"></i> <span id="form-title">नया उपयोगकर्ता बनाएं</span></div>
            <button type="button" id="reset-form-btn" onclick="resetForm()" class="btn-secondary" style="padding: 6px 12px; font-size: 12px; display: none;">नया बनाएं</button>
        </div>

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="user_id">

            <div class="form-group">
                <label class="form-label">नाम (Name - Unicode)</label>
                <input type="text" name="name" id="user_name" class="form-control-custom" placeholder="उदा: मुनि संघ सेवक" required>
            </div>

            <div class="form-group">
                <label class="form-label">ईमेल (Email Address)</label>
                <input type="email" name="email" id="user_email" class="form-control-custom" placeholder="उदा: helper@jindharm.com" required>
            </div>

            <div class="form-group">
                <label class="form-label">मोबाइल नंबर (Mobile - Optional)</label>
                <input type="text" name="mobile" id="user_mobile" class="form-control-custom" placeholder="उदा: +91 9876543210">
            </div>

            <div class="form-group" id="pass-field-group">
                <label class="form-label">पासवर्ड (Password)</label>
                <input type="password" name="password" id="user_password" class="form-control-custom" placeholder="उदा: ******">
                <span style="font-size: 11px; color: var(--text-light);" id="pass-hint">न्यूनतम 6 अक्षर।</span>
            </div>

            <div class="row-flex">
                <div>
                    <label class="form-label">भूमिका (Assign Role)</label>
                    <select name="role" id="user_role" class="form-control-custom" required>
                        <option value="">भूमिका चुनें (Select Role)</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">स्थिति (Status)</label>
                    <select name="status" id="user_status" class="form-control-custom" required>
                        <option value="1">सक्रिय (Active)</option>
                        <option value="0">निष्क्रिय (Inactive)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">प्रोफ़ाइल फ़ोटो (Profile Photo)</label>
                <input type="file" name="profile_photo" id="user_photo" class="form-control-custom" onchange="previewPhoto(this)">
                <img id="photo-preview" class="profile-preview" src="#" alt="Preview">
            </div>

            <div style="text-align: right; margin-top: 30px;">
                <button type="submit" class="btn-primary" style="width: 100%"><i class="fa-solid fa-circle-check"></i> सहेजें (Save User)</button>
            </div>
        </form>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal-overlay" id="reset-modal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-key"></i> पासवर्ड बदलें (<span id="reset-name"></span>)</h3>
            <button type="button" class="modal-close" onclick="closeResetModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('users.reset-password') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="reset_user_id">
                
                <div class="form-group">
                    <label class="form-label">नया पासवर्ड (New Password)</label>
                    <input type="password" name="new_password" class="form-control-custom" placeholder="उदा: ******" required minlength="6">
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="button" class="btn-secondary" onclick="closeResetModal()" style="margin-right: 8px;">रद्द करें</button>
                    <button type="submit" class="btn-primary">अपडेट करें (Reset Password)</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewPhoto(input) {
        const preview = document.getElementById('photo-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    }

    function editUser(user, roleName) {
        document.getElementById('form-title').innerText = 'उपयोगकर्ता संपादित करें';
        document.getElementById('reset-form-btn').style.display = 'inline-block';

        document.getElementById('user_id').value = user.id;
        document.getElementById('user_name').value = user.name;
        document.getElementById('user_email').value = user.email;
        document.getElementById('user_mobile').value = user.mobile || '';
        document.getElementById('user_role').value = roleName;
        document.getElementById('user_status').value = user.status;

        // Password not required for editing
        document.getElementById('user_password').required = false;
        document.getElementById('pass-hint').innerText = 'खाली छोड़ें यदि आप बदलना नहीं चाहते।';

        // Set photo preview
        const preview = document.getElementById('photo-preview');
        if (user.profile_photo) {
            preview.src = '{{ asset("") }}' + user.profile_photo;
            preview.style.display = 'block';
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    }

    function resetForm() {
        document.getElementById('form-title').innerText = 'नया उपयोगकर्ता बनाएं';
        document.getElementById('reset-form-btn').style.display = 'none';

        document.getElementById('user_id').value = '';
        document.getElementById('user_name').value = '';
        document.getElementById('user_email').value = '';
        document.getElementById('user_mobile').value = '';
        document.getElementById('user_role').value = '';
        document.getElementById('user_status').value = '1';
        document.getElementById('user_password').value = '';
        document.getElementById('user_password').required = true;
        document.getElementById('pass-hint').innerText = 'न्यूनतम 6 अक्षर।';
        document.getElementById('photo-preview').style.display = 'none';
        document.getElementById('user_photo').value = '';
    }

    // Modal helpers
    function openResetModal(userId, userName) {
        document.getElementById('reset_user_id').value = userId;
        document.getElementById('reset-name').innerText = userName;
        document.getElementById('reset-modal').classList.add('active');
    }

    function closeResetModal() {
        document.getElementById('reset-modal').classList.remove('active');
    }
</script>
@endsection
