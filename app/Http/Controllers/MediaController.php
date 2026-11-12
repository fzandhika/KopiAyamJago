<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    /**
     * Sajikan foto produk dari disk publik tanpa bergantung pada symlink /storage.
     * Berguna untuk hosting yang tidak mengizinkan `php artisan storage:link`.
     */
    public function __invoke(string $path): StreamedResponse
    {
        abort_if(str_contains($path, '..'), 404);

        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        return $disk->response($path, null, [
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}
