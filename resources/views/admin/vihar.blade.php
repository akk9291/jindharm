@extends('layouts.admin')

@section('title', 'विहार प्रबंधन (Vihar Management)')
@section('header_title', 'विहार ट्रैकिंग एवं स्थान प्रबंधन (Saint Vihar Tracking)')

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
    <!-- Left Column: Vihar List -->
    <div class="panel-card">
        <div class="panel-header">
            <div class="panel-title">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>विहार स्थान इतिहास (Vihar History)</span>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>साधु (Saint)</th>
                        <th>विहार स्थान (Location)</th>
                        <th>शहर/राज्य</th>
                        <th>समय सीमा (Dates)</th>
                        <th>स्थिति (Type)</th>
                        <th>क्रिया</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vihars as $vihar)
                        <tr>
                            <td>
                                <strong>{{ $vihar->saint->getLocalized('title') }} {{ $vihar->saint->getLocalized('name') }}</strong>
                            </td>
                            <td>
                                <strong>{{ $vihar->getLocalized('location_title') }}</strong>
                                @if($vihar->latitude && $vihar->longitude)
                                    <br><span style="font-size:11.5px; color:var(--text-light); font-family: monospace;">{{ $vihar->latitude }}, {{ $vihar->longitude }}</span>
                                @endif
                            </td>
                            <td>{{ $vihar->city }}, {{ $vihar->state }}</td>
                            <td>
                                <span style="font-weight: 500;">{{ $vihar->start_date->format('d-M-Y') }}</span>
                                @if($vihar->end_date)
                                    <br><span style="font-size:12px; color:var(--text-light)">से {{ $vihar->end_date->format('d-M-Y') }}</span>
                                @else
                                    <br><span class="badge badge-success" style="padding:2px 6px; font-size:10px;">चल रहा है</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $vihar->type === 'current' ? 'badge-success' : ($vihar->type === 'upcoming' ? 'badge-primary' : 'badge-secondary') }}">
                                    {{ $vihar->type === 'current' ? 'वर्तमान विहार' : ($vihar->type === 'upcoming' ? 'आगामी विहार' : 'पूर्व विहार') }}
                                </span>
                            </td>
                            <td>
                                <button onclick="editVihar({{ json_encode($vihar) }})" class="btn-action btn-action-edit" title="संपादित करें">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ url('admin/vihar/delete/'.$vihar->id) }}" onclick="return confirm('क्या आप इस विहार विवरण को मिटाना चाहते हैं?')" class="btn-action btn-action-delete" title="मिटाएं">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; color:var(--text-light)">कोई विहार विवरण दर्ज नहीं है।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Add/Edit Form -->
    <div class="form-section">
        <div class="panel-header">
            <div class="panel-title">
                <i class="fa-solid fa-location-dot"></i>
                <span id="vihar-form-title">नया विहार दर्ज करें (Add Vihar)</span>
            </div>
        </div>
        <form action="{{ url('admin/vihar/store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="vihar_id">

            <div class="form-group">
                <label class="form-label">साधु संघ चुनें (Select Saint)</label>
                <select name="saint_id" id="saint_id" class="form-control-custom" required>
                    <option value="">साधु चुनें</option>
                    @foreach($saints as $s)
                        <option value="{{ $s->id }}">{{ $s->getLocalized('title') }} {{ $s->getLocalized('name') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">विहार स्थान का नाम (Location Title - Hindi)</label>
                <input type="text" name="location_title_hi" id="location_title_hi" class="form-control-custom" placeholder="उदा: अतिशय क्षेत्र तिजारा जी" required>
            </div>

            <div class="row-flex">
                <div>
                    <label class="form-label">शहर (City)</label>
                    <input type="text" name="city" id="city" class="form-control-custom" placeholder="उदा: अलवर" required>
                </div>
                <div>
                    <label class="form-label">राज्य (State)</label>
                    <input type="text" name="state" id="state" class="form-control-custom" placeholder="उदा: राजस्थान" required>
                </div>
            </div>

            <div class="row-flex">
                <div>
                    <label class="form-label">अक्षांश (Latitude)</label>
                    <input type="text" name="latitude" id="latitude" class="form-control-custom" placeholder="उदा: 27.8931">
                </div>
                <div>
                    <label class="form-label">देशांतर (Longitude)</label>
                    <input type="text" name="longitude" id="longitude" class="form-control-custom" placeholder="उदा: 76.7198">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">गूगल मैप्स लिंक (Google Map Link)</label>
                <input type="text" name="google_map_link" id="google_map_link" class="form-control-custom" placeholder="https://maps.google.com/...">
            </div>

            <div class="row-flex">
                <div>
                    <label class="form-label">प्रारंभ तिथि (Start Date)</label>
                    <input type="date" name="start_date" id="start_date" class="form-control-custom" required>
                </div>
                <div>
                    <label class="form-label">समाप्ति तिथि (End Date)</label>
                    <input type="date" name="end_date" id="end_date" class="form-control-custom">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">सम्पर्क सूत्र व्यक्ति (Contact Person)</label>
                <input type="text" name="contact_person" id="contact_person" class="form-control-custom" placeholder="उदा: मन्त्री श्री रमेश जैन">
            </div>

            <div class="form-group">
                <label class="form-label">सम्पर्क मोबाइल (Contact Number)</label>
                <input type="text" name="contact_number" id="contact_number" class="form-control-custom" placeholder="उदा: +91-9876543210">
            </div>

            <div class="form-group">
                <label class="form-label">विहार प्रकार (Vihar Status)</label>
                <select name="type" id="vihar_type" class="form-control-custom">
                    <option value="current">वर्तमान विहार (Current)</option>
                    <option value="upcoming">आगामी विहार (Upcoming)</option>
                    <option value="previous">पूर्व विहार (Previous)</option>
                </select>
            </div>

            <div class="checkbox-group" style="margin-top: -10px; margin-bottom: 24px;">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" id="vihar_is_active" checked>
                    <span>सक्रिय (Active)</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> सहेजें (Save Location)</button>
                <button type="button" class="btn-secondary" onclick="resetViharForm()">रद्द करें</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editVihar(vihar) {
        document.getElementById('vihar_id').value = vihar.id;
        document.getElementById('saint_id').value = vihar.saint_id;
        document.getElementById('location_title_hi').value = vihar.location_title.hi || '';
        document.getElementById('city').value = vihar.city || '';
        document.getElementById('state').value = vihar.state || '';
        document.getElementById('latitude').value = vihar.latitude || '';
        document.getElementById('longitude').value = vihar.longitude || '';
        document.getElementById('google_map_link').value = vihar.google_map_link || '';
        document.getElementById('contact_person').value = vihar.contact_person || '';
        document.getElementById('contact_number').value = vihar.contact_number || '';
        
        document.getElementById('vihar_type').value = vihar.type;
        document.getElementById('vihar_is_active').checked = vihar.is_active;

        if (vihar.start_date) {
            document.getElementById('start_date').value = vihar.start_date.split('T')[0];
        }
        if (vihar.end_date) {
            document.getElementById('end_date').value = vihar.end_date.split('T')[0];
        } else {
            document.getElementById('end_date').value = '';
        }

        document.getElementById('vihar-form-title').innerText = 'विहार विवरण बदलें (Edit Vihar)';
    }

    function resetViharForm() {
        document.getElementById('vihar_id').value = '';
        document.getElementById('saint_id').value = '';
        document.getElementById('location_title_hi').value = '';
        document.getElementById('city').value = '';
        document.getElementById('state').value = '';
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('google_map_link').value = '';
        document.getElementById('contact_person').value = '';
        document.getElementById('contact_number').value = '';
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
        
        document.getElementById('vihar_type').value = 'current';
        document.getElementById('vihar_is_active').checked = true;

        document.getElementById('vihar-form-title').innerText = 'नया विहार दर्ज करें (Add Vihar)';
    }
</script>
@endsection
