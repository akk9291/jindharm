@extends('layouts.admin')

@section('title', 'सामग्री सूची (Content List)')
@section('header_title', 'सामग्री प्रबंधन (Content Management)')

@section('content')
<div class="panel-card">
    <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">
        <div class="panel-title">
            <i class="fa-solid fa-file-pen"></i>
            <span>सामग्री सूची (Content List)</span>
        </div>
        <a href="{{ url('admin/contents/create') }}" class="btn-primary" style="font-size:13px; padding:8px 16px;"><i class="fa-solid fa-plus"></i> सामग्री जोड़ें (Add Content)</a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>शीर्षक (Title)</th>
                    <th>कंटेंट प्रकार (Type)</th>
                    <th>श्रेणी (Category)</th>
                    <th>प्रकाशित तिथि</th>
                    <th>स्थिति (Status)</th>
                    <th>दृश्यता</th>
                    <th>क्रिया (Actions)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contents as $c)
                    <tr>
                        <td>
                            <strong>{{ $c->getLocalized('title') }}</strong>
                            @if($c->getLocalized('author'))
                                <br><span style="font-size:11.5px; color:var(--text-light)">लेखक: {{ $c->getLocalized('author') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-secondary">
                                {{ $c->contentType->getLocalized('name') }}
                            </span>
                        </td>
                        <td>{{ $c->category ? $c->category->getLocalized('name') : '-' }}</td>
                        <td><span style="font-weight: 500;">{{ $c->publish_date->format('d-M-Y') }}</span></td>
                        <td>
                            <span class="badge {{ $c->status === 'published' ? 'badge-success' : 'badge-primary' }}">
                                {{ $c->status === 'published' ? 'प्रकाशित' : 'प्रारूप' }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size:13px; display:flex; gap:10px;">
                                <span title="Web" style="color:{{ $c->show_on_website ? 'var(--primary)' : '#cbd5e1' }}"><i class="fa-solid fa-globe"></i></span>
                                <span title="App" style="color:{{ $c->show_on_app ? 'var(--primary)' : '#cbd5e1' }}"><i class="fa-solid fa-mobile-screen"></i></span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ url('admin/contents/edit/'.$c->id) }}" class="btn-action btn-action-edit" title="संपादित करें"><i class="fa-solid fa-pen"></i></a>
                            <a href="{{ url('admin/contents/delete/'.$c->id) }}" onclick="return confirm('क्या आप इस सामग्री को मिटाना चाहते हैं?')" class="btn-action btn-action-delete" title="मिटाएं"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:var(--text-light)">कोई सामग्री उपलब्ध नहीं है।</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
