@extends('layouts.admin')

@section('title', 'मांगलिक कार्यक्रम (Events)')
@section('header_title', 'मांगलिक कार्यक्रम एवं आयोजन प्रबंधन (Event Management)')

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
            <div class="panel-title"><i class="fa-solid fa-calendar-days"></i> आगामी एवं पूर्व आयोजन (Events List)</div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>बैनर</th>
                        <th>आयोजन नाम</th>
                        <th>स्थान (Venue)</th>
                        <th>प्रारंभ तिथि</th>
                        <th>स्थिति</th>
                        <th>क्रिया</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $e)
                        <tr>
                            <td>
                                @if($e->banner)
                                    <img src="{{ asset($e->banner) }}" style="width:40px; height:40px; object-fit:cover; border-radius:6px; border: 1px solid #e2e8f0;">
                                @else
                                    <div style="width:40px; height:40px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; border-radius:6px; border: 1px solid #e2e8f0;"><i class="fa-solid fa-calendar" style="color:#cbd5e1;"></i></div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $e->getLocalized('event_name') }}</strong>
                                @if($e->getLocalized('description'))
                                    <br><span style="font-size:11.5px; color:var(--text-light)">{{ Str::limit($e->getLocalized('description'), 40) }}</span>
                                @endif
                            </td>
                            <td>{{ $e->getLocalized('venue') }}</td>
                            <td><span style="font-weight: 500;">{{ $e->start_date->format('d-M-Y H:i') }}</span></td>
                            <td>
                                <span class="badge {{ $e->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $e->is_active ? 'सक्रिय' : 'निष्क्रिय' }}
                                </span>
                            </td>
                            <td>
                                <button onclick="editEvent({{ json_encode($e) }})" class="btn-action btn-action-edit" title="संपादित करें"><i class="fa-solid fa-pen"></i></button>
                                <a href="{{ url('admin/events/delete/'.$e->id) }}" onclick="return confirm('क्या आप इस कार्यक्रम को मिटाना चाहते हैं?')" class="btn-action btn-action-delete" title="मिटाएं"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; color:var(--text-light)">कोई कार्यक्रम उपलब्ध नहीं है।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-section">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-calendar-plus"></i> <span id="form-title">नया कार्यक्रम जोड़ें</span></div>
        </div>
        <form action="{{ url('admin/events/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="event_id">
            
            <div class="form-group">
                <label class="form-label">कार्यक्रम का नाम (Event Name - Hindi)</label>
                <input type="text" name="event_name_hi" id="event_name_hi" class="form-control-custom" placeholder="उदा: चातुर्मास कलश स्थापना महोत्सव" required>
            </div>
            <div class="form-group">
                <label class="form-label">Event Name (English)</label>
                <input type="text" name="event_name_en" id="event_name_en" class="form-control-custom" placeholder="Example: Chaturmas Kalash Sthapna">
            </div>
            <div class="form-group">
                <label class="form-label">आयोजन स्थल (Venue - Hindi)</label>
                <input type="text" name="venue_hi" id="event_venue" class="form-control-custom" placeholder="उदा: शांतिनाथ दिगम्बर जैन मन्दिर, दिल्ली" required>
            </div>
            <div class="row-flex">
                <div>
                    <label class="form-label">प्रारंभ तिथि व समय</label>
                    <input type="datetime-local" name="start_date" id="event_start" class="form-control-custom" required>
                </div>
                <div>
                    <label class="form-label">समाप्ति तिथि (वैकल्पिक)</label>
                    <input type="datetime-local" name="end_date" id="event_end" class="form-control-custom">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">बैनर/पोस्टर (Banner Image)</label>
                <input type="file" name="banner" class="form-control-custom" accept="image/*">
            </div>
            <div class="form-group">
                <label class="form-label">विवरण (Description)</label>
                <textarea name="description_hi" id="event_desc" class="form-control-custom" style="height:80px;"></textarea>
            </div>
            <div class="checkbox-group" style="margin-top: -10px; margin-bottom: 24px;">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" id="event_is_active" checked>
                    <span>सक्रिय (Active)</span>
                </label>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> सहेजें (Save Event)</button>
                <button type="button" class="btn-secondary" onclick="resetEventForm()">रद्द करें</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editEvent(event) {
        document.getElementById('event_id').value = event.id;
        document.getElementById('event_name_hi').value = event.event_name.hi || '';
        document.getElementById('event_name_en').value = event.event_name.en || '';
        document.getElementById('event_venue').value = event.venue.hi || '';
        document.getElementById('event_desc').value = (event.description && event.description.hi) || '';
        
        document.getElementById('event_is_active').checked = event.is_active;

        if (event.start_date) {
            document.getElementById('event_start').value = event.start_date.replace(' ', 'T').substring(0, 16);
        }
        if (event.end_date) {
            document.getElementById('event_end').value = event.end_date.replace(' ', 'T').substring(0, 16);
        } else {
            document.getElementById('event_end').value = '';
        }

        document.getElementById('form-title').innerText = 'कार्यक्रम विवरण संपादित करें';
    }

    function resetEventForm() {
        document.getElementById('event_id').value = '';
        document.getElementById('event_name_hi').value = '';
        document.getElementById('event_name_en').value = '';
        document.getElementById('event_venue').value = '';
        document.getElementById('event_desc').value = '';
        document.getElementById('event_start').value = '';
        document.getElementById('event_end').value = '';
        document.getElementById('event_is_active').checked = true;
        document.getElementById('form-title').innerText = 'नया कार्यक्रम जोड़ें';
    }
</script>
@endsection
