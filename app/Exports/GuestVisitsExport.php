<?php

namespace App\Exports;

use App\Models\GuestVisit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
// 1. Import class-class baru untuk styling
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// 2. Tambahkan interface baru ke dalam class
class GuestVisitsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $visits;

    public function __construct($visits)
    {
        $this->visits = $visits;
    }

    public function collection()
    {
        return $this->visits;
    }

    public function headings(): array
    {
        return [
            'Nama Tamu',
            'Perusahaan',
            'Nomor Telepon',
            'Tujuan Kunjungan',
            'Status',
            'Waktu Check-in',
            'Resepsionis Check-in',
            'Waktu Check-out',
            'Resepsionis Check-out',
        ];
    }

    public function map($visit): array
    {
        // 3. Buat pemetaan data lebih aman dari error 'null'
        return [
            $visit->guest?->name ?? 'N/A',
            $visit->guest?->company ?? 'N/A',
            $visit->guest?->phone ?? 'N/A',
            $visit->visit_destination ?? '-',
            ucwords(str_replace('_', ' ', $visit->status)),
            $visit->time_in ? $visit->time_in->format('d M Y, H:i') : '-',
            $visit->checkedInBy?->name ?? '-',
            $visit->time_out ? $visit->time_out->format('d M Y, H:i') : '-',
            $visit->checkedOutBy?->name ?? '-',
        ];
    }

    // --- BAGIAN TAMBAHAN UNTUK STYLING ---

    public function columnWidths(): array
    {
        return [
            'A' => 30, // Nama Tamu
            'B' => 30, // Perusahaan
            'C' => 20, // Nomor Telepon
            'D' => 40, // Tujuan Kunjungan
            'E' => 20, // Status
            'F' => 25, // Waktu Check-in
            'G' => 30, // Resepsionis Check-in
            'H' => 25, // Waktu Check-out
            'I' => 30, // Resepsionis Check-out
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Membuat baris pertama (header) menjadi tebal
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $sheet->getHighestColumn();
                $lastRow = $sheet->getHighestRow();

                // Menambahkan border ke seluruh tabel
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Mengaktifkan wrap text untuk kolom yang berpotensi panjang
                $sheet->getStyle('D2:D' . $lastRow)->getAlignment()->setWrapText(true);
                
                // Menengahkan semua sel secara vertikal
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            },
        ];
    }
}