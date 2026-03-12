<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SyncFromServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:from-server {--path=resources/views : The directory to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync changed files from the remote server to local (Designer Reverse Sync)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->option('path');
        $this->info("Connecting to remote server to sync: $path...");

        try {
            $remoteFiles = Storage::disk('remote')->allFiles($path);
            
            $bar = $this->output->createProgressBar(count($remoteFiles));
            $bar->start();

            foreach ($remoteFiles as $file) {
                $remoteLastModified = Storage::disk('remote')->lastModified($file);
                $localPath = base_path($file);
                
                $shouldDownload = false;
                
                if (!File::exists($localPath)) {
                    $shouldDownload = true;
                } else {
                    $localLastModified = File::lastModified($localPath);
                    // Se il file remoto è più recente di più di 2 secondi (margine di errore FS)
                    if ($remoteLastModified > ($localLastModified + 2)) {
                        $shouldDownload = true;
                    }
                }

                if ($shouldDownload) {
                    $content = Storage::disk('remote')->get($file);
                    File::ensureDirectoryExists(dirname($localPath));
                    File::put($localPath, $content);
                    $this->line("\n[UPDATED] $file");
                }

                $bar->advance();
            }

            $bar->finish();
            $this->info("\nSync completed!");

        } catch (\Exception $e) {
            $this->error("Error during sync: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
