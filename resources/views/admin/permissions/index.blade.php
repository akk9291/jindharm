@extends('layouts.admin')

@section('title', 'अनुमति प्रबंधन (Permission Management)')
@section('header_title', 'प्रणाली अनुमतियां (System Permissions)')

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
    .module-group-card {
        background: #fafaf9;
        border-radius: var(--border-radius-md);
        border: 1px solid #cbd5e1;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .module-group-header {
        background: #f1f5f9;
        padding: 14px 20px;
        font-weight: 700;
        font-size: 14px;
        color: var(--text-dark);
        border-bottom: 1.5px solid #cbd5e1;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .module-group-header i {
        color: var(--primary);
    }
    .module-group-body {
        padding: 18px 20px;
    }
    .permission-badge-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .permission-pill {
        background: white;
        border: 1px solid #cbd5e1;
        padding: 6px 14px;
        border-radius: var(--border-radius-md);
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: var(--transition);
    }
    .permission-pill:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
    }
    .permission-name {
        font-family: monospace;
        font-weight: 600;
        color: var(--text-medium);
    }
    .permission-delete-link {
        color: #ef4444;
        text-decoration: none;
        font-size: 11px;
    }
    .permission-delete-link:hover {
        color: #b91c1c;
        transform: scale(1.2);
    }
</style>

<div class="grid-container">
    <!-- Permissions List -->
    <div>
        <div class="panel-card" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; border-bottom: none;">
            <div class="panel-header" style="margin-bottom: 0; padding-bottom: 0; border-bottom: none;">
                <div class="panel-title"><i class="fa-solid fa-key"></i> सक्रिय अनुमतियां (Active Permissions)</div>
            </div>
        </div>

        <div style="max-height: 700px; overflow-y: auto; padding-right: 4px;">
            @forelse($groupedPermissions as $moduleName => $perms)
                <div class="module-group-card">
                    <div class="module-group-header">
                        <i class="fa-solid fa-cubes"></i>
                        <span>{{ $moduleName }}</span>
                    </div>
                    <div class="module-group-body">
                        <div class="permission-badge-wrapper">
                            @foreach($perms as $p)
                                <div class="permission-pill">
                                    <span class="permission-name">{{ $p->name }}</span>
                                    
                                    @if(!in_array($p->name, [
                                        'saint.view', 'saint.create', 'saint.edit', 'saint.delete',
                                        'guru.view', 'guru.create', 'guru.edit', 'guru.delete',
                                        'vihar.view', 'vihar.create', 'vihar.edit', 'vihar.delete',
                                        'content_type.view', 'content_type.create', 'content_type.edit', 'content_type.delete',
                                        'category.view', 'category.create', 'category.edit', 'category.delete',
                                        'content.view', 'content.create', 'content.edit', 'content.delete', 'content.publish',
                                        'media.view', 'media.upload', 'media.edit', 'media.delete',
                                        'gallery.view', 'gallery.create', 'gallery.edit', 'gallery.delete',
                                        'video.view', 'video.create', 'video.edit', 'video.delete',
                                        'audio.view', 'audio.create', 'audio.edit', 'audio.delete',
                                        'pdf.view', 'pdf.create', 'pdf.edit', 'pdf.delete',
                                        'event.view', 'event.create', 'event.edit', 'event.delete',
                                        'panchang.view', 'panchang.create', 'panchang.edit', 'panchang.delete',
                                        'festival.view', 'festival.create', 'festival.edit', 'festival.delete',
                                        'page.view', 'page.create', 'page.edit', 'page.delete',
                                        'menu.view', 'menu.create', 'menu.edit', 'menu.delete',
                                        'homepage.view', 'homepage.edit',
                                        'seo.view', 'seo.manage',
                                        'settings.view', 'settings.manage',
                                        'user.view', 'user.create', 'user.edit', 'user.delete',
                                        'role.view', 'role.create', 'role.edit', 'role.delete',
                                        'permission.view', 'permission.create', 'permission.edit', 'permission.delete',
                                        'log.view'
                                    ]))
                                        <a href="{{ route('permissions.destroy', $p->id) }}" onclick="return confirm('क्या आप वाकई इस अनुमति को हटाना चाहते हैं?')" class="permission-delete-link" title="हटाएं"><i class="fa-solid fa-circle-xmark"></i></a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="panel-card" style="text-align: center; color: var(--text-light)">कोई अनुमति उपलब्ध नहीं है।</div>
            @endforelse
        </div>
    </div>

    <!-- Permission Form -->
    <div class="form-section" style="align-self: start;">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-square-plus"></i> <span id="form-title">नयी अनुमति जोड़ें</span></div>
        </div>

        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="perm_id">

            <div class="form-group">
                <label class="form-label">अनुमति नाम (Permission Name)</label>
                <input type="text" name="name" id="perm_name" class="form-control-custom" placeholder="उदा: user.ban" required>
                <span style="font-size: 11px; color: var(--text-light);">प्रारूप: <code>module.action</code> (उदा: <code>saint.approve</code>, <code>vihar.close</code>)</span>
            </div>

            <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: var(--border-radius-md); padding: 16px; margin-bottom: 24px;">
                <p style="font-size: 12.5px; color: #b45309; line-height: 1.5;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <strong>चेतावनी:</strong> नयी अनुमतियां केवल उन्नत उपयोग के लिए हैं। सुनिश्चित करें कि आप संबंधित कोड और रूट सुरक्षा में भी इसे लागू करते हैं।
                </p>
            </div>

            <div style="text-align: right; margin-top: 30px;">
                <button type="submit" class="btn-primary" style="width: 100%"><i class="fa-solid fa-circle-check"></i> अनुमति सहेजें (Save Permission)</button>
            </div>
        </form>
    </div>
</div>
@endsection
