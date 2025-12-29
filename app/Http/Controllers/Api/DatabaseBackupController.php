<?php
namespace App\Http\Controllers\Api;
//use App\Models\DatabaseBackup;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupController extends MyController
{
    private string $backupPath = 'database_backups';

    /**
     * Take database backup
     */
    public function backup(): JsonResponse
    {
        try {
            $connection = config('database.default');
            $dbConfig   = config("database.connections.$connection");

            $dbName = $dbConfig['database'];
            $dbUser = $dbConfig['username'];
            $dbPass = $dbConfig['password'];
            $dbHost = $dbConfig['host'];
            $dbPort = $dbConfig['port'];

            $timestamp = now()->format('Ymd_His');
            $filename  = "backup_{$dbName}_{$timestamp}.sql";
            $fullPath  = storage_path("app/{$this->backupPath}");

            if (!File::exists($fullPath)) {
                File::makeDirectory($fullPath, 0755, true);
            }

            $filePath = "{$fullPath}/{$filename}";

            if ($connection === 'pgsql') {
                $command = "PGPASSWORD=\"{$dbPass}\" pg_dump -h {$dbHost} -p {$dbPort} -U {$dbUser} {$dbName} > {$filePath}";
            } else {
                // MySQL
                $command = "mysqldump -h {$dbHost} -P {$dbPort} -u {$dbUser} -p\"{$dbPass}\" {$dbName} > {$filePath}";
            }

            exec($command, $output, $result);

            if ($result !== 0) {
                throw new \Exception('Database backup command failed.');
            }

            <!-- $backup = DatabaseBackup::create([
                'bku_filename' => $filename,
                'bku_path'     => $filePath,
                'bku_size'     => filesize($filePath),
                'bku_status'   => 'success',
                'bku_message'  => 'Backup completed successfully',
            ]); -->

            return response()->json([
                'success' => true,
                'message' => 'Database backup created successfully',
                'data'    => $backup
            ], 201);

        } catch (\Throwable $e) {

            $backup = DatabaseBackup::create([
                'bku_filename' => null,
                'bku_path'     => null,
                'bku_size'     => null,
                'bku_status'   => 'failed',
                'bku_message'  => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database backup failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * List backups
     */
    public function index(): JsonResponse
    {
        $backups = DatabaseBackup::orderByDesc('bku_created_at')->get();

        return response()->json([
            'success' => true,
            'data'    => $backups
        ]);
    }

    /**
     * Restore database from backup
     */
    public function restore(int $id): JsonResponse
    {
        try {
            $backup = DatabaseBackup::findOrFail($id);

            if (!File::exists($backup->bku_path)) {
                throw new \Exception('Backup file not found.');
            }

            $connection = config('database.default');
            $dbConfig   = config("database.connections.$connection");

            $dbName = $dbConfig['database'];
            $dbUser = $dbConfig['username'];
            $dbPass = $dbConfig['password'];
            $dbHost = $dbConfig['host'];
            $dbPort = $dbConfig['port'];

            if ($connection === 'pgsql') {
                $command = "PGPASSWORD=\"{$dbPass}\" psql -h {$dbHost} -p {$dbPort} -U {$dbUser} {$dbName} < {$backup->bku_path}";
            } else {
                $command = "mysql -h {$dbHost} -P {$dbPort} -u {$dbUser} -p\"{$dbPass}\" {$dbName} < {$backup->bku_path}";
            }

            exec($command, $output, $result);

            if ($result !== 0) {
                throw new \Exception('Database restore failed.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Database restored successfully'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database restore failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
