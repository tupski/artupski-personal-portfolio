<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        if (isset($data['status']) && in_array($data['status'], ['scheduled', 'published'])) {
            if (empty($data['published_at'])) {
                $data['published_at'] = now();
            }
        }

        return $data;
    }
}
