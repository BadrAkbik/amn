<?php

namespace App\Filament\Resources\PeriodResource\Pages;

use App\Filament\Resources\PeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePeriod extends CreateRecord
{
    protected static string $resource = PeriodResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
