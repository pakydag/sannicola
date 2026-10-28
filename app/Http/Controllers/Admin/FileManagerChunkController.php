<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Alexusmai\LaravelFileManager\Services\ConfigService\ConfigRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerChunkController extends Controller
{
    /**
     * Handle progressive chunked file upload.
     */
    public function uploadChunk(Request $request): JsonResponse
    {
        $request->validate([
            'chunk' => 'required|file',
            'chunk_index' => 'required|integer|min:0',
            'total_chunks' => 'required|integer|min:1',
            'file_id' => 'required|string',
            'filename' => 'required|string',
            'disk' => 'nullable|string',
            'path' => 'nullable|string',
        ]);

        $fileId = preg_replace('/[^a-zA-Z0-9_\-]/', '', $request->input('file_id'));
        if (empty($fileId)) {
            $fileId = 'upload_' . uniqid();
        }

        $filename = basename($request->input('filename'));

        // Handle slugify if configured in Laravel File Manager
        try {
            $configRepo = resolve(ConfigRepository::class);
            if ($configRepo && method_exists($configRepo, 'getSlugifyNames') && $configRepo->getSlugifyNames()) {
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
                $filename = Str::slug($nameWithoutExt) . ($ext ? ('.' . $ext) : '');
            }
        } catch (\Throwable $e) {
            // Keep original filename if resolver fails
        }

        $disk = $request->input('disk') ?: 'public';
        $path = trim((string) $request->input('path', ''), '/');
        $overwrite = filter_var($request->input('overwrite'), FILTER_VALIDATE_BOOLEAN) || $request->input('overwrite') === '1';

        $chunkIndex = (int) $request->input('chunk_index');
        $totalChunks = (int) $request->input('total_chunks');

        $chunksBaseDir = storage_path('app/chunks');
        $chunkDir = $chunksBaseDir . '/' . $fileId;

        if (!is_dir($chunkDir)) {
            @mkdir($chunkDir, 0777, true);
        }

        // Save incoming chunk part
        $partFilename = 'part_' . str_pad($chunkIndex, 6, '0', STR_PAD_LEFT);
        $request->file('chunk')->move($chunkDir, $partFilename);

        $uploadedParts = glob($chunkDir . '/part_*');
        $uploadedCount = is_array($uploadedParts) ? count($uploadedParts) : 0;

        // If all parts are uploaded (or this is the final chunk and all parts exist)
        if ($uploadedCount >= $totalChunks) {
            $mergedFile = $chunkDir . '/merged_' . $filename;
            $out = fopen($mergedFile, 'wb');

            if (!$out) {
                return response()->json([
                    'result' => [
                        'status' => 'error',
                        'message' => 'Impossibile creare il file assemblato.'
                    ]
                ], 500);
            }

            for ($i = 0; $i < $totalChunks; $i++) {
                $partPath = $chunkDir . '/part_' . str_pad($i, 6, '0', STR_PAD_LEFT);
                if (!file_exists($partPath)) {
                    fclose($out);
                    return response()->json([
                        'result' => [
                            'status' => 'error',
                            'message' => "Pacchetto {$i} mancante durante l'assemblaggio."
                        ]
                    ], 400);
                }

                $in = fopen($partPath, 'rb');
                if ($in) {
                    while ($buffer = fread($in, 1048576)) {
                        fwrite($out, $buffer);
                    }
                    fclose($in);
                }
                @unlink($partPath);
            }
            fclose($out);

            // Determine final destination path in target disk
            $targetFilePath = empty($path) ? $filename : ($path . '/' . $filename);

            // Check if file already exists and overwrite is false
            if (!$overwrite && Storage::disk($disk)->exists($targetFilePath)) {
                @unlink($mergedFile);
                @rmdir($chunkDir);

                return response()->json([
                    'result' => [
                        'status' => 'warning',
                        'message' => 'fileAlreadyExists'
                    ]
                ]);
            }

            // Stream assembled file into Storage disk
            $stream = fopen($mergedFile, 'rb');
            Storage::disk($disk)->put($targetFilePath, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }

            @unlink($mergedFile);
            @rmdir($chunkDir);

            // Clean older abandoned chunks in background
            $this->cleanOldChunks($chunksBaseDir);

            return response()->json([
                'result' => [
                    'status' => 'success',
                    'message' => 'uploaded'
                ]
            ]);
        }

        return response()->json([
            'result' => [
                'status' => 'chunk_received',
                'chunk_index' => $chunkIndex,
                'total_chunks' => $totalChunks,
                'uploaded_parts' => $uploadedCount
            ]
        ]);
    }

    /**
     * Clean chunk directories older than 24 hours.
     */
    private function cleanOldChunks(string $baseDir): void
    {
        try {
            if (!is_dir($baseDir)) {
                return;
            }
            $dirs = glob($baseDir . '/*', GLOB_ONLYDIR);
            if (!$dirs) {
                return;
            }
            $expireTime = time() - 86400;
            foreach ($dirs as $dir) {
                if (filemtime($dir) < $expireTime) {
                    $files = glob($dir . '/*');
                    if ($files) {
                        foreach ($files as $f) {
                            @unlink($f);
                        }
                    }
                    @rmdir($dir);
                }
            }
        } catch (\Throwable $e) {
            // Silently ignore cleanup errors
        }
    }
}
