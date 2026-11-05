<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait HandlesUploads
{
    protected function storeImage(Request $request, string $field = 'image', ?string $current = null): ?string
    {
        if (! $request->hasFile($field)) {
            return $current;
        }

        /** @var UploadedFile $file */
        $file = $request->file($field);
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $name = Str::limit($name, 60, '').'-'.Str::lower(Str::random(6)).'.'.$file->getClientOriginalExtension();

        $file->move(public_path('uploads'), $name);

        return 'uploads/'.$name;
    }

    protected function galleryFromText(?string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $text))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
