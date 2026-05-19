<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-results', function () {
    $path = base_path('coverage/index.html');

    if (!File::exists($path)) {
        abort(404);
    }

    return new Response(File::get($path), 200, ['Content-Type' => 'text/html']);
});

Route::get('/test-results/{path}', function ($path) {
    $file = base_path('coverage/' . $path);

    if (!File::exists($file) || !File::isFile($file)) {
        abort(404);
    }

    $mime = File::mimeType($file) ?: 'application/octet-stream';
    return response(File::get($file), 200, ['Content-Type' => $mime]);
})->where('path', '.*');

Route::get('/tests', function () {
    $testRoot = base_path('tests');
    $files = File::allFiles($testRoot);

    $tests = collect($files)
        ->map(function ($file) use ($testRoot) {
            return [
                'path' => str_replace($testRoot . DIRECTORY_SEPARATOR, '', $file->getRealPath()),
                'relative' => str_replace('\\', '/', $file->getRelativePathname()),
                'name' => $file->getBasename('.php'),
                'type' => $file->getPath() === $testRoot ? 'Root' : basename($file->getPath()),
            ];
        })
        ->sortBy('relative')
        ->values()
        ->all();

    return view('tests', compact('tests'));
});
