<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Models\Site;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use PhpParser\Node\Stmt\Label;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationLabel(): string
    {
        return __('attributes.sites');
    }

    public static function getModelLabel(): string
    {
        return __('attributes.site');
    }

    public static function getPluralModelLabel(): string
    {
        return __('attributes.sites');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('attributes.site_name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('address')
                            ->label(__('attributes.address'))
                            ->required()
                            ->maxLength(255),

                    ]),
                Section::make(__('attributes.add periods to site'))
                    ->schema([
                        Repeater::make('periods')
                            ->label(__('attributes.periods'))
                            ->relationship('periods')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('attributes.period_name'))
                                    ->nullable()
                                    ->maxLength(255),
                                TimePicker::make('from')
                                    ->label(__('attributes.from'))
                                    ->required()
                                    ->seconds(false),
                                TimePicker::make('to')
                                    ->label(__('attributes.to'))
                                    ->required()
                                    ->seconds(false),
                            ])
                            ->columns(1)
                            ->defaultItems(3)
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('attributes.name'))
                    ->searchable(isIndividual: true),
                TextColumn::make('address')
                    ->label(__('attributes.address'))
                    ->searchable(isIndividual: true),
                TextColumn::make('created_at')
                    ->label(__('attributes.created_at'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('attributes.updated_at'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }
}
