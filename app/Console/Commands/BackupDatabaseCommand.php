<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'naka:backup-db {--type=backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup current database to file';

    /**
     * The number of days to keep backups before cleanup.
     *
     * @var int
     */
    protected $cleanupThreshold = 30;

    /**
     * The path to the database backup directory.
     *
     * @var string
     */
    protected $backupPath;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->backupPath = storage_path('backups');
    }

    /**
     * Create the backup directory if it does not exist.
     */
    protected function createBackupDirectory(): void
    {
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    /**
     * Create a backup file with the current timestamp.
     *
     * @param string $filename
     */
    protected function createBackupFile(string $filename): void
    {
        $this->info('Backing up database...');

        $filePath = $this->backupPath . '/' . $filename;

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            env('DB_DATABASE'),
            escapeshellarg($filePath)
        );

        system($command, $result);

        if ($result === 0) {
            $this->info('Database backup created successfully: ' . $filePath);
        } else {
            $this->error('Failed to create database backup.');
        }
    }

    /**
     * Delete old backup files based on the cleanup threshold.
     */
    protected function deleteOldBackups(): void
    {
        $this->info('Cleaning up old backups...');

        $totalFiles = 0;
        $files = glob($this->backupPath . '/*.sql');
        $threshold = now()->subDays($this->cleanupThreshold);

        foreach ($files as $file) {
            if (filemtime($file) < $threshold->getTimestamp()) {
                unlink($file);
                $totalFiles++;
                $this->info('Deleted old backup: ' . $file);
            }
        }

        if ($totalFiles > 0) {
            $this->info('Total old backups deleted: ' . $totalFiles);
        } else {
            $this->info('No old backups to delete.');
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        if (!in_array($type, ['backup', 'cleanup'])) {
            $this->error('Invalid type option. Use "backup" or "cleanup".');
            return;
        }

        $this->createBackupDirectory();

        switch ($type) {
            case 'backup':
                $this->createBackupFile('backup-' . date('Y_m_d_His') . '.sql');
                break;
            case 'cleanup':
                $this->deleteOldBackups();
                break;
        }
    }
}
