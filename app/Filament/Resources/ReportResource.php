<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Period;
use App\Models\Report;
use App\Models\Site;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Spatie\LaravelPdf\Facades\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;

use function Spatie\LaravelPdf\Support\pdf;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function getNavigationLabel(): string
    {
        return __('attributes.reports');
    }

    public static function getModelLabel(): string
    {
        return __('attributes.report');
    }

    public static function getPluralModelLabel(): string
    {
        return __('attributes.reports');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('attributes.name'))
                    ->maxLength(255)
                    ->default(null),
                DatePicker::make('date')
                    ->label(__('attributes.date'))
                    ->required(),
                TimePicker::make('time')
                    ->label(__('attributes.time'))
                    ->seconds(false)
                    ->required(),
                Select::make('reporter_id')
                    ->label(__('attributes.reporter_name'))
                    ->required()
                    ->relationship('reporter', 'id')
                    ->exists('users', 'id')
                    ->live()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->name}"),
                    Select::make('period_id')
                        ->label(__('attributes.period'))
                        ->required()
                        ->relationship('period', 'id')
                        ->exists('periods', 'id')
                        ->live()
                        ->preload()
                        ->getOptionLabelFromRecordUsing(fn (Period $record) => "{$record->name}, {$record->from}, {$record->to}"),
                    Select::make('site_id')
                        ->label(__('attributes.site'))
                        ->required()
                        ->relationship('site')
                        ->exists('sites', 'id')
                        ->live()
                        ->preload()
                        ->getOptionLabelFromRecordUsing(fn (Site $record) => "{$record->name}"),
                Textarea::make('state_description')
                    ->label(__('attributes.state_description'))
                    ->required()
                    ->rows(20)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('attributes.name'))
                    ->searchable(),
                TextColumn::make('date')
                    ->label(__('attributes.date'))
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('time')
                    ->label(__('attributes.time')),
                TextColumn::make('reporter.name')
                    ->label(__('attributes.reporter_name'))
                    ->searchable(),
                TextColumn::make('site.name')
                    ->label(__('attributes.site_name'))
                    ->sortable(),
                TextColumn::make('period.from')
                    ->label(__('attributes.from'))
                    ->time('g:i A')
                    ->sortable(),
                TextColumn::make('period.to')
                    ->label(__('attributes.to'))
                    ->time('g:i A')
                    ->sortable(),
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
                Tables\Actions\Action::make('print')
                    ->label(__('attributes.print'))
                    ->color('success')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Report $report) => route('print', $report))
                    ->openUrlInNewTab(),
                /*                 Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->color('success')
                    ->icon('heroicon-s-arrow-down-tray')
                    ->action(function (Report $record) {
                        return response()->streamDownload(function () use ($record) {
                            return PDF::view('pdf', ['record' => $record])->save($record->site->name . '.pdf');
                        });
                    }), */
                /* Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->color('success')
                    ->icon('heroicon-s-arrow-down-tray')
                    ->action(function (Report $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo FacadePdf::loadHtml(
                                Blade::render('pdf', ['record' => $record])
                            )->stream();
                        }, $record->site->name . '.pdf');
                    }), */
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
