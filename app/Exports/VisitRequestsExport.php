<?php

namespace App\Exports;

use App\Models\VisitRequest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VisitRequestsExport implements FromQuery, WithHeadings, WithMapping
{
    // Properti untuk menampung nilai filter dari controller
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
    * Mendefinisikan query untuk mengambil data dari database.
    */
    public function query()
    {
        $query = VisitRequest::query()->with(['user.profile.department', 'user.profile.subsidiary', 'status', 'user.profile.level']);

        // Terapkan filter yang sama persis seperti di komponen Livewire
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

        return $query->latest('id');
    }

    /**
    * Mendefinisikan judul untuk setiap kolom di file Excel.
    */
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
            'Disetujui Oleh',
            'Tanggal Disetujui',
        ];
    }

    /**
    * Memetakan setiap baris data ke kolom yang sesuai.
    */
    public function map($request): array
    {
        return [
            $request->id,
            $request->user->name,
            $request->user->profile->level->name,
            $request->user->profile->department->name,
            $request->user->profile->subsidiary->name,
            $request->destination,
            $request->purpose,
            $request->from_date,
            $request->to_date,
            $request->status->name,
            $request->approver?->name, // Menggunakan optional helper jika approver null
            $request->approved_at,
        ];
    }
}