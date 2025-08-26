<?php

namespace App\Exports;

use App\Models\GuestVisit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuestVisitsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $visits;

    public function __construct($visits)
    {
        $this->visits = $visits;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
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
        return [
            $visit->guest->name,
            $visit->guest->company,
            $visit->guest->phone,
            $visit->visit_destination,
            ucwords(str_replace('_', ' ', $visit->status)),
            $visit->time_in ? $visit->time_in->format('d M Y, H:i') : '-',
            $visit->checkedInBy->name ?? '-',
            $visit->time_out ? $visit->time_out->format('d M Y, H:i') : '-',
            $visit->checkedOutBy->name ?? '-',
        ];
    }
}