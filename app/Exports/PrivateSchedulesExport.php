<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle; // Import WithTitle
use Illuminate\Support\Facades\Auth;
use App\Models\PrivateSchedule;
use Carbon\Carbon;

class PrivateSchedulesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();
        $query = PrivateSchedule::where('user_id', $user->id);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('scheduled_at', [$this->startDate, Carbon::parse($this->endDate)->endOfDay()]);
        } elseif ($this->startDate) {
            $query->where('scheduled_at', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->where('scheduled_at', '<=', Carbon::parse($this->endDate)->endOfDay());
        }

        return $query->orderBy('scheduled_at', 'asc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tanggal & Waktu',
            'Briefing Dilakukan?',
            'Rapat Diadakan?',
            'Supervisi Dilakukan?',
            'Handover Dilakukan?',
            'Tugas Luar',
            'Catatan',
        ];
    }

    /**
     * @param mixed $log
     * @return array
     */
    public function map($log): array
    {
        return [
            Carbon::parse($log->scheduled_at)->format('d-m-Y H:i'),
            $log->briefing ? 'Ya' : 'Tidak',
            $log->meeting ? 'Ya' : 'Tidak',
            $log->supervision ? 'Ya' : 'Tidak',
            $log->handover ? 'Ya' : 'Tidak',
            $log->external_task ?: '-',
            $log->note ?: '-',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Catatan Kegiatan Pribadi';
    }
}