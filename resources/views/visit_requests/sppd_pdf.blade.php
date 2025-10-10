<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Perintah Perjalanan Dinas</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; line-height: 1.6; }
        .container { width: 90%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h3 { margin: 0; }
        .header p { margin: 0; }
        .content { margin-top: 20px; }
        .content table { width: 100%; border-collapse: collapse; }
        .content th, .content td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        .content th { background-color: #f2f2f2; text-align: left; width: 30%;}
        .footer { margin-top: 50px; }
        .signatures { width: 100%; margin-top: 40px; }
        .signature-box { width: 30%; float: left; text-align: center; margin-left: 5%;}
        .signature-box.right { float: right; margin-right: 5%;}
        .signature-box p { margin-bottom: 60px; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Ganti dengan logo perusahaan jika ada --}}
            {{-- <img src="{{ public_path('images/logo-satoria.png') }}" alt="Logo" width="150"/> --}}
            <h3>SURAT PERINTAH PERJALANAN DINAS</h3>
            <p>Nomor: SPPD/{{ $request->created_at->format('Y') }}/{{ $request->id }}</p>
        </div>

        <div class="content">
            <p>Yang bertanda tangan di bawah ini memberikan perintah perjalanan dinas kepada:</p>
            <table>
            <tr>
                <th>Nama</th>
                <td>{{ $request->user->name }}</td>
            </tr>
            <tr>
                <th>Jabatan / Level</th>
                <td>{{ optional($request->user->profile)->level?->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Departemen</th>
                <td>{{ optional($request->user->profile)->department?->name ?? 'N/A' }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">Untuk melaksanakan perjalanan dinas dengan ketentuan sebagai berikut:</p>
        <table>
            <tr>
                <th>Tujuan Perjalanan</th>
                <td>{{ is_string($request->destination) ? $request->destination : $request->destination->name }}</td>
            </tr>
            <tr>
                <th>Keperluan</th>
                <td>{{ $request->purpose }}</td>
            </tr>
            <tr>
                <th>Tanggal Berangkat</th>
                <td>{{ \Carbon\Carbon::parse($request->from_date)->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Tanggal Kembali</th>
                <td>{{ \Carbon\Carbon::parse($request->to_date)->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Durasi</th>
                <td>{{ \Carbon\Carbon::parse($request->from_date)->diffInDays(\Carbon\Carbon::parse($request->to_date)) + 1 }} Hari</td>
            </tr>
        </table>
        </div>

        <div class="footer clearfix">
            <p>Demikian Surat Perintah Perjalanan Dinas ini dibuat untuk dapat dilaksanakan dengan sebaik-baiknya.</p>

            <div class="signatures">
                <div class="signature-box right">
                    <p>Disetujui oleh,</p>
                    
                    {{-- Mengambil approver terakhir --}}
                    @php
                        $lastApprover = $request->approvalLogs->where('status', 'Approved')->last();
                    @endphp
                    <b><u>{{ $lastApprover?->approver?->name ?? 'N/A' }}</u></b><br>
                    <span>{{ $lastApprover?->approver?->profile?->level?->name ?? 'Approver' }}</span>
                </div>
                <div class="signature-box">
                     Surabaya, {{ $request->updated_at->format('d F Y') }}<br>
                    <p>Hormat kami,</p>
                   
                    <b><u>{{ $request->user->name }}</u></b><br>
                    <span>Pemohon</span>
                </div>
            </div>
        </div>

    </div>
</body>
</html>