<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'hand_hygiene_forms',
        'apd_forms',
        'identifikasi_pasien_forms',
        'wtri_forms',
        'kritis_lab_forms',
        'fornas_forms',
        'visite_forms',
        'jatuh_forms',
        'cp_forms',
        'kepuasan_forms',
        'krk_forms',
        'poe_forms',
        'sc_forms',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            $oldIndexName = $tableName . '_week_start_date_unique';
            $newIndexName = $tableName . '_week_start_date_user_id_unique';

            // Hapus unique lama jika ada
            if ($this->indexExists($tableName, $oldIndexName)) {
                DB::statement("ALTER TABLE `$tableName` DROP INDEX `$oldIndexName`");
            }

            // Tambahkan unique gabungan jika belum ada
            if (!$this->indexExists($tableName, $newIndexName)) {
                DB::statement("ALTER TABLE `$tableName` ADD UNIQUE `$newIndexName` (`week_start_date`, `user_id`)");
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            $newIndexName = $tableName . '_week_start_date_user_id_unique';

            if ($this->indexExists($tableName, $newIndexName)) {
                DB::statement("ALTER TABLE `$tableName` DROP INDEX `$newIndexName`");
            }
        }
    }

    private function indexExists(string $tableName, string $indexName): bool
    {
        $result = DB::select("
            SELECT INDEX_NAME
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = ?
            AND INDEX_NAME = ?
            LIMIT 1
        ", [$tableName, $indexName]);

        return count($result) > 0;
    }
};