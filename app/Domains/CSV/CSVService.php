<?php

namespace App\Domains\CSV;

use App\Domains\CRUDService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class CSVService extends CRUDService
{
    protected $modelClass = CSV::class;

    public function create($data)
    {
        $data['name'] = $this->getNameFile($data['csv']);
        $data['path'] = $this->getPath();
        $this->moveFileForFolder($data['name'], $data['csv']);
        return parent::create($data);
    }

    /**
     * @param $file
     * @return string
     */
    private function getNameFile($file)
    {
        $name = Carbon::now()->format('Y-m-d-H-i-s-v-u');
        $extension = $file->getClientOriginalExtension();
        return sprintf('%s.%s', $name, $extension);
    }

    private function getPath()
    {
        return 'app/upload/csv/';
    }

    private function moveFileForFolder($fileName, $uploadedFile)
    {
        $storagePath = storage_path($this->getPath());
        Storage::makeDirectory($storagePath);
        $uploadedFile->move($storagePath, $fileName);
    }

    /**
     * @param CSV   $model
     * @param array $data
     */
    protected function fill(& $model, array $data)
    {
        $model->name = $data['name'];
        $model->path = $this->getPath();
    }
}
