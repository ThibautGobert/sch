<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Filament\Resources\LessonResource\RelationManagers;
use App\Forms\Components\CustomRichText;
use App\Models\Lesson;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Actions\Action;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Leçons';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
                Forms\Components\Toggle::make('active')
                    ->columnSpanFull()
                    ->required()
                    ->default(0),
                Select::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'name')
                    ->preload()
                    ->searchable(true)
                    ->native(false),
                Forms\Components\TextInput::make('title')
                    ->columnSpanFull()
                    //->columns(4)
                    ->maxLength(255),
                FileUpload::make('image')
                    ->columnSpanFull()
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorViewportWidth('1920')
                    ->imageEditorViewportHeight('1080')
                    ->imageEditorAspectRatios([
                        '16:9',
                    ]),
                CustomRichText::make('intro')->columnSpanFull(),
                Repeater::make('chapters')
                    ->label('Chapitres')
                    ->columnSpanFull()
                    ->relationship('chapters')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255),
                    ])
                    ->orderColumn('order')
                    ->extraItemActions([
                        Action::make('editChapter')
                            ->icon('heroicon-o-pencil') // Utilisez une icône d'édition appropriée
                            ->action(function (array $arguments, Repeater $component): void {
                                $state = $component->getState();
                                $itemData = $state[$arguments['item']];
                                redirect()->to(route('filament.admin.resources.chapters.edit', ['record' => $itemData['id']]));
                            }),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->label('Actif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),
                /*
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                */
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de création')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Date de mise à jour')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
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
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
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
