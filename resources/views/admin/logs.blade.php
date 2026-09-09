@extends('layouts.admin')

@section('title', 'गतिविधि लॉग (Activity Logs)')
@section('header_title', 'गतिविधि लॉग एवं ऑडिट ट्रेल (Activity Logs)')

@section('content')
<div class="panel-card">
    <div class="panel-header">
        <div class="panel-title">
            <i class="fa-solid fa-receipt"></i>
            <span>सुरक्षा एवं गतिविधि ऑडिट लॉग (Audit Trail Logs)</span>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>समय (Timestamp)</th>
                    <th>उपयोगकर्ता (User)</th>
                    <th>मॉड्यूल (Module)</th>
                    <th>क्रिया (Action)</th>
                    <th>विवरण (Description)</th>
                    <th>IP पता</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td style="font-family: monospace; font-size:12.5px;">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <strong>{{ $log->user->name ?? 'System' }}</strong>
                            <br><span style="font-size:10.5px; color:var(--text-light)">{{ $log->user->email ?? '' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-primary">
                                {{ $log->module }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $log->action === 'Create' ? 'badge-success' : ($log->action === 'Update' ? 'badge-primary' : 'badge-danger') }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td>{{ $log->description }}</td>
                        <td style="font-family: monospace; font-size:12.5px; color:var(--text-light);">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:var(--text-light)">कोई गतिविधि लॉग उपलब्ध नहीं है।</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div style="margin-top:20px; display:flex; justify-content:center;">
        {{ $logs->links() }}
    </div>
</div>
@endsection
