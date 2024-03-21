<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChapterResource\Pages;
use App\Filament\Resources\ChapterResource\RelationManagers;
use App\Forms\Components\CustomRichText;
use App\Models\Chapter;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChapterResource extends Resource
{
    protected static ?string $model = Chapter::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Chapitres';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                /*
                Forms\Components\TextInput::make('order')
                    ->numeric(),
                Forms\Components\TextInput::make('lesson_id')
                    ->required()
                    ->numeric(),
                */
                Forms\Components\TextInput::make('title')
                    ->label('Titre')
                    ->maxLength(255),
                Select::make('lesson_id')
                    ->label('Leçon')
                    ->relationship(name: 'lesson', titleAttribute: 'title')
                    ->preload()
                    ->searchable(true)
                    ->native(false),
                FileUpload::make('image')
                    ->columnSpanFull()
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorViewportWidth('1920')
                    ->imageEditorViewportHeight('1080')
                    ->imageEditorAspectRatios([
                        '16:9',
                    ]),
                CustomRichText::make('intro')
                    ->label('Introduction')
                    ->columnSpanFull(),
                Repeater::make('chapter_items')
                    /*
                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                        dd($data);
                        $data['user_id'] = auth()->id();

                        return $data;
                    })
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                        dd($data);
                        $data['user_id'] = auth()->id();

                        return $data;
                    })
                    */
                    ->columnSpanFull()
                    ->relationship('chapter_items')
                    ->schema([
                        Forms\Components\RichEditor::make('content')

                    ])
                    ->orderColumn('order')
                    ->extraItemActions([
                        Action::make('editChapter')
                            ->icon('heroicon-o-pencil') // Utilisez une icône d'édition appropriée
                            ->action(function (array $arguments, Repeater $component): void {
                                $state = $component->getState();
                                $itemData = $state[$arguments['item']];
                                redirect()->to(route('filament.admin.resources.chapter-items.edit', ['record' => $itemData['id']]));
                            }),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lesson_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChapters::route('/'),
            'create' => Pages\CreateChapter::route('/create'),
            'edit' => Pages\EditChapter::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
