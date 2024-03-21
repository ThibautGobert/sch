<?php

namespace App\Filament\Resources\ChapterItemResource\Pages;

use App\Filament\Resources\ChapterItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChapterItem extends EditRecord
{
    protected static string $resource = ChapterItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
