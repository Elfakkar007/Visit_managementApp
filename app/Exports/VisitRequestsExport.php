<?php

namespace App\Exports;

use App\Models\VisitRequest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisitRequestsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        // Query tidak berubah, tetap dinamis sesuai filter
        $query = VisitRequest::query()->with(['user.profile.department', 'user.profile.subsidiary', 'status', 'user.profile.level', 'approver']);

        if (!empty($this->filters['filterUser'])) {
            $query->where('user_id', $this->filters['filterUser']);
        }
        if (!empty($this->filters['filterDepartment'])) {
            $query->whereHas('user.profile', function ($q) {
                $q->where('department_id', $this->filters['filterDepartment']);
            });
        }
        if (!empty($this->filters['filterSubsidiary'])) {
            $query->whereHas('user.profile', function ($q) {
                $q->where('subsidiary_id', $this->filters['filterSubsidiary']);
            });
        }
        if (!empty($this->filters['filterStatus'])) {
            $query->where('status_id', $this->filters['filterStatus']);
        }
        if (!empty($this->filters['filterDate'])) {
            $query->whereDate('from_date', $this->filters['filterDate']);
        }
        if (!empty($this->filters['filterMonth'])) {
            $query->whereMonth('from_date', $this->filters['filterMonth']);
        }
        if (!empty($this->filters['filterYear'])) {
            $query->whereYear('from_date', $this->filters['filterYear']);
        }

        return $query->latest('id');
    }

    public function headings(): array
    {
        return [
            'ID Request',
            'Nama Pemohon',
            'Level',
            'Departemen',
            'Subsidiary',
            'Tujuan',
            'Keperluan',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
            'Diproses Oleh',
            'Tanggal Diproses',
        ];
    }

    public function map($request): array
    {
        // --- INI BAGIAN YANG DIPERBAIKI AGAR AMAN DARI NULL ---
        return [
            $request->id,
            $request->user?->name ?? 'N/A',
            $request->user?->profile?->level?->name ?? 'N/A',
            $request->user?->profile?->department?->name ?? 'N/A',
            $request->user?->profile?->subsidiary?->name ?? 'N/A',
            $request->destination,
            $request->purpose,
            $request->from_date->isoFormat('D MMM YYYY, HH:mm'),
            $request->to_date->isoFormat('D MMM YYYY, HH:mm'),
            $request->status?->name ?? 'N/A',
            $request->approver?->name ?? '-',
            $request->approved_at ? $request->approved_at->isoFormat('D MMM YYYY, HH:mm') : '-',
        ];
    }

    // --- BAGIAN TAMBAHAN UNTUK MERAPIKAN TAMPILAN EXCEL ---
    public function columnWidths(): array
    {
        return [
            'A' => 12, 'B' => 30, 'C' => 15, 'D' => 20, 'E' => 20,
            'F' => 40, 'G' => 50, 'H' => 25, 'I' => 25, 'J' => 15,
            'K' => 30, 'L' => 25,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $sheet->getHighestColumn();
                $lastRow = $sheet->getHighestRow();

                // Terapkan border ke semua sel
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Atur wrap text untuk kolom yang panjang
                $sheet->getStyle('F2:G' . $lastRow)->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            },
        ];
    }
}