<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use App\Models\Logistic;
use Carbon\Carbon;

class LogisticsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
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
        $departmentId = $user->department_id;

        // 1. Fetch Alat Kesehatan (All time, no date filter)
        $alatKesehatanItems = Logistic::where('department_id', $departmentId)
            ->where('category', 'Alat Kesehatan')
            ->orderBy('item_name')
            ->get();

        // 2. Fetch Barang Habis Pakai (All time, no date filter)
        $barangHabisPakaiItems = Logistic::where('department_id', $departmentId)
            ->where('category', 'Barang Habis Pakai')
            ->orderBy('item_name')
            ->get();

        // 3. Fetch Consumption / Used Items (Filtered by date)
        $consumptionItemsQuery = Logistic::where('department_id', $departmentId)
            ->where('used', '>', 0); // Only items that have been 'used'

        if ($this->startDate && $this->endDate) {
            $consumptionItemsQuery->whereBetween('updated_at', [$this->startDate, Carbon::parse($this->endDate)->endOfDay()]);
        } elseif ($this->startDate) {
            $consumptionItemsQuery->where('updated_at', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $consumptionItemsQuery->where('updated_at', '<=', Carbon::parse($this->endDate)->endOfDay());
        }
        $consumptionItems = $consumptionItemsQuery->orderBy('item_name')->get();


        // Combine all data into a single collection with separators
        $combinedData = collect();

        // Section 1: Alat Kesehatan
        $combinedData->push(['']); // Blank row for spacing
        $combinedData->push(['--- Inventaris: Alat Kesehatan ---']); // Section Title
        $combinedData->push($this->getAlatKesehatanHeadings()); // Headings for this section
        if ($alatKesehatanItems->isEmpty()) {
            // Adjust number of empty strings to match the number of headings
            $combinedData->push(['Tidak ada data Alat Kesehatan.', '', '', '', '', '', '', '', '', '', '', '', '', '']);
        } else {
            foreach ($alatKesehatanItems as $item) {
                $combinedData->push($this->mapAlatKesehatanItem($item));
            }
        }

        // Section 2: Barang Habis Pakai
        $combinedData->push(['']); // Blank row for spacing
        $combinedData->push(['--- Inventaris: Barang Habis Pakai ---']); // Section Title
        $combinedData->push($this->getBarangHabisPakaiHeadings()); // Headings for this section
        if ($barangHabisPakaiItems->isEmpty()) {
            // Adjust number of empty strings to match the number of headings
            $combinedData->push(['Tidak ada data Barang Habis Pakai.', '', '', '', '', '', '', '', '', '', '', '', '', '']);
        } else {
            foreach ($barangHabisPakaiItems as $item) {
                $combinedData->push($this->mapBarangHabisPakaiItem($item));
            }
        }

        // Section 3: Laporan Konsumsi Barang
        $combinedData->push(['']); // Blank row for spacing
        $combinedData->push(['--- Laporan Konsumsi Barang (Periode: ' . ($this->startDate ? Carbon::parse($this->startDate)->format('d-m-Y') : 'Mulai') . ' s/d ' . ($this->endDate ? Carbon::parse($this->endDate)->format('d-m-Y') : 'Sekarang') . ') ---']); // Section Title with date range
        $combinedData->push($this->getConsumptionHeadings()); // Headings for this section
        if ($consumptionItems->isEmpty()) {
            // Adjust number of empty strings to match the number of headings
            $combinedData->push(['Tidak ada data konsumsi barang dalam rentang tanggal ini.', '', '', '', '', '', '', '', '', '', '', '']);
        } else {
            foreach ($consumptionItems as $item) {
                $combinedData->push($this->mapConsumptionItem($item));
            }
        }

        return $combinedData;
    }

    public function headings(): array
    {
        // This top-level headings method will return a single, empty cell
        // because specific section headings are embedded within the collection.
        return [''];
    }

    public function map($row): array
    {
        // This acts as a passthrough for rows that are already prepared
        // (either as data rows, blank lines, or section titles/headings).
        return $row;
    }

    // --- Helper methods for Headings and Item Mapping for each section ---

    private function getAlatKesehatanHeadings(): array
    {
        return [
            'ID', 'Nama Barang', 'Kategori', 'Merk', 'Stok', 'Satuan', 'Status',
            'Kode Barang', 'Jadwal Maintenance', 'Tgl Kalibrasi', 'Kadaluarsa Kalibrasi',
            'Catatan', 'Terakhir Diperbarui', 'Ruangan',
        ];
    }

    private function mapAlatKesehatanItem($item): array
    {
        return [
            $item->id,
            $item->item_name,
            $item->category,
            $item->brand ?? '-',
            $item->stock,
            $item->unit_of_measure ?? '-',
            $item->status,
            $item->item_code ?? '-',
            $item->maintenance_schedule ?? '-',
            $item->calibration_date ? Carbon::parse($item->calibration_date)->format('Y-m-d') : '-',
            $item->calibration_expiry_date ? Carbon::parse($item->calibration_expiry_date)->format('Y-m-d') : '-',
            $item->notes ?? '-',
            $item->updated_at ? Carbon::parse($item->updated_at)->format('Y-m-d H:i:s') : '-',
            $item->department->name ?? 'N/A',
        ];
    }

    private function getBarangHabisPakaiHeadings(): array
    {
        // These headings are identical to Alat Kesehatan
        return $this->getAlatKesehatanHeadings();
    }

    private function mapBarangHabisPakaiItem($item): array
    {
        // This mapping is identical to Alat Kesehatan
        return $this->mapAlatKesehatanItem($item);
    }

    private function getConsumptionHeadings(): array
    {
        return [
            'ID', 'Nama Barang', 'Kategori', 'Merk', 'Digunakan', 'Stok Tersisa', 'Satuan',
            'Status', 'Kode Barang', 'Terakhir Diperbarui', 'Catatan', 'Ruangan',
        ];
    }

    private function mapConsumptionItem($item): array
    {
        return [
            $item->id,
            $item->item_name,
            $item->category,
            $item->brand ?? '-',
            $item->used, // Specific for consumption
            $item->stock, // Remaining stock
            $item->unit_of_measure ?? '-',
            $item->status,
            $item->item_code ?? '-',
            $item->updated_at ? Carbon::parse($item->updated_at)->format('Y-m-d H:i:s') : '-',
            $item->notes ?? '-',
            $item->department->name ?? 'N/A',
        ];
    }
}