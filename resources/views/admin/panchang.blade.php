@extends('layouts.admin')

@section('title', 'दैनिक पंचांग (Daily Panchang)')
@section('header_title', 'दैनिक जैन पंचांग प्रबंधन (Panchang Configuration)')

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
    <!-- Left Column: Panchang history list -->
    <div class="panel-card">
        <div class="panel-header">
            <div class="panel-title">
                <i class="fa-solid fa-clock"></i>
                <span>हालिया पंचांग प्रविष्टियाँ (Recent Panchang List)</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>दिनांक (Date)</th>
                        <th>तिथि (Tithi)</th>
                        <th>पक्ष (Paksha)</th>
                        <th>मास (Maas)</th>
                        <th>नक्षत्र (Nakshatra)</th>
                        <th>सूर्योदय/सूर्यास्त</th>
                        <th>क्रिया</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($panchangs as $p)
                        <tr>
                            <td><strong>{{ $p->date->format('d-M-Y') }}</strong></td>
                            <td><strong>{{ $p->tithi }}</strong></td>
                            <td>{{ $p->paksha }}</td>
                            <td>{{ $p->maas }}</td>
                            <td>{{ $p->nakshatra ?? '-' }}</td>
                            <td>{{ $p->sunrise }} / {{ $p->sunset }}</td>
                            <td>
                                <button onclick="editPanchang({{ json_encode($p) }})" class="btn-action btn-action-edit" title="संपादित करें"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; color:var(--text-light)">कोई पंचांग विवरण उपलब्ध नहीं है।</td>
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
                <i class="fa-solid fa-calendar-plus"></i>
                <span id="panchang-title">पंचांग डेटा दर्ज करें (Add/Edit Panchang)</span>
            </div>
        </div>
        <form action="{{ url('admin/panchang/store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">दिनांक (Date)</label>
                <input type="date" name="date" id="p_date" class="form-control-custom" value="{{ now()->format('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">तिथि (Tithi - उदा: प्रथमा, एकादशी)</label>
                <input type="text" name="tithi" id="p_tithi" class="form-control-custom" placeholder="उदा: एकादशी" required>
            </div>

            <div class="row-flex">
                <div>
                    <label class="form-label">पक्ष (Paksha)</label>
                    <select name="paksha" id="p_paksha" class="form-control-custom" required>
                        <option value="शुक्ल पक्ष">शुक्ल पक्ष (Shukla Paksha)</option>
                        <option value="कृष्ण पक्ष">कृष्ण पक्ष (Krishna Paksha)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">मास (Maas - उदा: आषाढ़, श्रावण)</label>
                    <input type="text" name="maas" id="p_maas" class="form-control-custom" placeholder="उदा: आषाढ़" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">नक्षत्र (Nakshatra - वैकल्पिक)</label>
                <input type="text" name="nakshatra" id="p_nakshatra" class="form-control-custom" placeholder="उदा: हस्त">
            </div>

            <div class="row-flex">
                <div>
                    <label class="form-label">सूर्योदय (Sunrise - उदा: 05:40 AM)</label>
                    <input type="text" name="sunrise" id="p_sunrise" class="form-control-custom" placeholder="उदा: 05:40 AM">
                </div>
                <div>
                    <label class="form-label">सूर्यास्त (Sunset - उदा: 07:15 PM)</label>
                    <input type="text" name="sunset" id="p_sunset" class="form-control-custom" placeholder="उदा: 07:15 PM">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">विशेष नोट्स / मांगलिक संदेश (Notes - Hindi)</label>
                <textarea name="notes" id="p_notes" class="form-control-custom" style="height:100px;" placeholder="आज के मांगलिक नियम, व्रत आदि..."></textarea>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> पंचांग सहेजें (Save Panchang)</button>
                <button type="button" class="btn-secondary" onclick="resetPanchangForm()">रद्द करें</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editPanchang(p) {
        document.getElementById('p_date').value = p.date.split('T')[0];
        document.getElementById('p_tithi').value = p.tithi;
        document.getElementById('p_paksha').value = p.paksha;
        document.getElementById('p_maas').value = p.maas;
        document.getElementById('p_nakshatra').value = p.nakshatra || '';
        document.getElementById('p_sunrise').value = p.sunrise || '';
        document.getElementById('p_sunset').value = p.sunset || '';
        document.getElementById('p_notes').value = p.notes || '';
        
        document.getElementById('panchang-title').innerText = 'पंचांग डेटा बदलें (Edit Panchang)';
    }

    function resetPanchangForm() {
        document.getElementById('p_date').value = "{{ now()->format('Y-m-d') }}";
        document.getElementById('p_tithi').value = '';
        document.getElementById('p_paksha').value = 'शुक्ल पक्ष';
        document.getElementById('p_maas').value = '';
        document.getElementById('p_nakshatra').value = '';
        document.getElementById('p_sunrise').value = '';
        document.getElementById('p_sunset').value = '';
        document.getElementById('p_notes').value = '';
        document.getElementById('panchang-title').innerText = 'पंचांग डेटा दर्ज करें (Add/Edit Panchang)';
    }
</script>
@endsection
