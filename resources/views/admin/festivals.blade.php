@extends('layouts.admin')

@section('title', 'त्योहार कैलेंडर (Festivals)')
@section('header_title', 'जैन त्योहार एवं कल्याणक पर्व कैलेंडर (Jain Festival Calendar)')

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
            <div class="panel-title"><i class="fa-solid fa-award"></i> व्रत एवं त्योहार सूची (Festival List)</div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>चित्र</th>
                        <th>त्योहार नाम</th>
                        <th>तारीख (Date)</th>
                        <th>विवरण</th>
                        <th>क्रिया</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($festivals as $fest)
                        <tr>
                            <td>
                                @if($fest->image)
                                    <img src="{{ asset($fest->image) }}" style="width:40px; height:40px; object-fit:cover; border-radius:6px; border: 1px solid #e2e8f0;">
                                @else
                                    <div style="width:40px; height:40px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; border-radius:6px; border: 1px solid #e2e8f0;"><i class="fa-solid fa-award" style="color:#cbd5e1;"></i></div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $fest->getLocalized('festival_name') }}</strong>
                            </td>
                            <td><span style="font-weight: 500;">{{ $fest->festival_date->format('d-M-Y') }}</span></td>
                            <td>{{ Str::limit($fest->getLocalized('description'), 50) }}</td>
                            <td>
                                <button onclick="editFestival({{ json_encode($fest) }})" class="btn-action btn-action-edit" title="संपादित करें"><i class="fa-solid fa-pen"></i></button>
                                <a href="{{ url('admin/festivals/delete/'.$fest->id) }}" onclick="return confirm('क्या आप इस त्योहार को मिटाना चाहते हैं?')" class="btn-action btn-action-delete" title="मिटाएं"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:var(--text-light)">कोई त्योहार दर्ज नहीं है।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-section">
        <div class="panel-header">
            <div class="panel-title"><i class="fa-solid fa-circle-plus"></i> <span id="form-title">त्योहार/कल्याणक जोड़ें</span></div>
        </div>
        <form action="{{ url('admin/festivals/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="fest_id">
            
            <div class="form-group">
                <label class="form-label">त्योहार का नाम (Festival Name - Hindi)</label>
                <input type="text" name="festival_name_hi" id="fest_name_hi" class="form-control-custom" placeholder="उदा: महावीर जयंती" required>
            </div>
            <div class="form-group">
                <label class="form-label">Festival Name (English)</label>
                <input type="text" name="festival_name_en" id="fest_name_en" class="form-control-custom" placeholder="Example: Mahavir Jayanti">
            </div>
            <div class="form-group">
                <label class="form-label">तारीख (Festival Date)</label>
                <input type="date" name="festival_date" id="fest_date" class="form-control-custom" required>
            </div>
            <div class="form-group">
                <label class="form-label">त्योहार चित्र (Image File)</label>
                <input type="file" name="image" class="form-control-custom" accept="image/*">
            </div>
            <div class="form-group">
                <label class="form-label">विवरण (Description)</label>
                <textarea name="description_hi" id="fest_desc" class="form-control-custom" style="height:80px;"></textarea>
            </div>
            <div class="checkbox-group" style="margin-top: -10px; margin-bottom: 24px;">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" id="fest_is_active" checked>
                    <span>सक्रिय (Active)</span>
                </label>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> सहेजें (Save Festival)</button>
                <button type="button" class="btn-secondary" onclick="resetFestivalForm()">रद्द करें</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editFestival(fest) {
        document.getElementById('fest_id').value = fest.id;
        document.getElementById('fest_name_hi').value = fest.festival_name.hi || '';
        document.getElementById('fest_name_en').value = fest.festival_name.en || '';
        document.getElementById('fest_desc').value = (fest.description && fest.description.hi) || '';
        document.getElementById('fest_is_active').checked = fest.is_active;

        if (fest.festival_date) {
            document.getElementById('fest_date').value = fest.festival_date.split('T')[0];
        }

        document.getElementById('form-title').innerText = 'त्योहार विवरण संपादित करें';
    }

    function resetFestivalForm() {
        document.getElementById('fest_id').value = '';
        document.getElementById('fest_name_hi').value = '';
        document.getElementById('fest_name_en').value = '';
        document.getElementById('fest_desc').value = '';
        document.getElementById('fest_date').value = '';
        document.getElementById('fest_is_active').checked = true;
        document.getElementById('form-title').innerText = 'त्योहार/कल्याणक जोड़ें';
    }
</script>
@endsection
