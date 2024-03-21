<?php

namespace App\Filament\Resources\ChapterItemResource\Pages;

use App\Filament\Resources\ChapterItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChapterItems extends ListRecords
{
    protected static string $resource = ChapterItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
