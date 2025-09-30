<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Request #{{ $request->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, h3 { border-bottom: 1px solid #ddd; padding-bottom: 5px; }
    </style>
</head>
<body>
    <h2>Detail Permintaan Kunjungan #{{ $request->id }}</h2>
    <h3>Informasi Pemohon</h3>
    <table>
        <tr><th>Nama</th><td>{{ $request->user->name ?? '-' }}</td></tr>
        <tr><th>Departemen</th><td>{{ $request->user->profile->department->name ?? '-' }}</td></tr>
    </table>
    <h3>Detail Perjalanan</h3>
    <table>
        <tr><th>Tujuan</th><td>{{ $request->destination ?? '-' }}</td></tr>
        <tr><th>Keperluan</th><td>{{ $request->purpose ?? '-' }}</td></tr>
        <tr><th>Waktu</th><td>{{ $request->from_date->format('d M Y, H:i') }} - {{ $request->to_date->format('d M Y, H:i') }}</td></tr>
    </table>
    <h3>Status Approval</h3>
    <table>
        <tr><th>Status Akhir</th><td>{{ $request->status->name }}</td></tr>
        @foreach($request->approvalLogs as $log)
        <tr>
            <th>Langkah {{ $log->step }} ({{ $log->status->name }})</th>
            <td>
                <b>{{ $log->approver->name }}</b> pada {{ $log->created_at->format('d M Y, H:i') }} <br>
                @if($log->notes)
                <i>Catatan: {{ $log->notes }}</i>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>