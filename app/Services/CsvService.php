<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

/**
 * Class CsvService
 * @package App\Services
 */
class CsvService
{
    /**
     * Collect data from csv.
     *
     * @param string $path
     * @return Collection
     */
    public function toCollection(string $path): Collection
    {
        /** @var UploadedFile $file */
        $data = array_map('str_getcsv', file($path));

        return Collection::make($data);
    }
}
