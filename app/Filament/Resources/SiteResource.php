<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Models\Site;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ])->columnSpan(2),
                Section::make(__('attributes.add permissions to users to see this site'))
                    ->schema([
                        Select::make('allowedUser')
                            ->label(__('attributes.choose users'))
                            ->relationship('allowedUsers', 'email')
                            ->options(
                                User::whereNot('role_id', 1)->get()->mapWithKeys(function ($user) {
                                    return [$user->id => $user->name . ' - ' . $user->email];
                                })
                            )
                            ->multiple()
                            ->preload()
                            ->live()
                    ])->columnSpan(1),
                Section::make(__('attributes.add periods to site'))
                    ->collapsible()
                    ->schema([
                        Repeater::make('periods')
                            ->label(__('attributes.periods'))
                            ->relationship('periods')
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('attributes.period_name'))
                                    ->nullable()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                TimePicker::make('from')
                                    ->label(__('attributes.from'))
                                    ->required()
                                    ->seconds(false),
                                TimePicker::make('to')
                                    ->label(__('attributes.to'))
                                    ->required()
                                    ->seconds(false),
                            ])
                            ->columns(2)
                            ->defaultItems(3)
                            ->collapsed()
                            ->addActionLabel(__('attributes.add period'))
                    ])->columns(1)
                    ->columnSpan(2),

            ])->columns(3);
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
            ->query(function (Site $site) {
                if (!auth()->user()->hasPermission('sites.viewAll')) {
                    return $site->whereRelation('allowedUsers', 'user_id', auth()->user()->id);
                } else {
                    return $site;
                }
            })
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
