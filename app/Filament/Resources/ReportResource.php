<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Period;
use App\Models\Report;
use App\Models\Site;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;

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

    public static function canEdit($record): bool
    {
        return ($record->reporter_id == auth()->user()->id && auth()->user()->hasPermission('report.own_update')) || auth()->user()->hasPermission('report.update');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('attributes.name'))
                    ->string()
                    ->maxLength(255)
                    ->default(null),
                DatePicker::make('date')
                    ->label(__('attributes.date'))
                    ->date()
                    ->required(),
                TimePicker::make('time')
                    ->label(__('attributes.time'))
                    ->time()
                    ->seconds(false)
                    ->required(),
                Select::make('period_id')
                    ->label(__('attributes.period'))
                    ->required()
                    ->relationship('period', 'id')
                    ->exists('periods', 'id')
                    ->getOptionLabelFromRecordUsing(fn(Period $record) => "{$record->name}, {$record->from}, {$record->to}"),
                Select::make('site_id')
                    ->label(__('attributes.site'))
                    ->required()
                    ->relationship('site')
                    ->exists('sites', 'id')
                    ->options(function () {
                        if (!auth()->user()->hasPermission('reports.create_to_all_sites')) {
                            return Site::whereRelation('WriteReportsPermissions', 'user_id', auth()->user()->id)->pluck('name', 'id');
                        } else {
                            return Site::all()->pluck('name', 'id');
                        }
                    })
                    ->getOptionLabelFromRecordUsing(fn(Site $record) => "{$record->name}"),
                MarkdownEditor::make('state_description')
                    ->string()
                    ->label(__('attributes.state_description'))
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('attachments')
                    ->label(__('attributes.attachments'))
                    ->multiple()
                    ->disk('public')
                    ->directory('attachments')
                    ->maxFiles(7)
                    ->acceptedFileTypes([
                        'image/*',
                        'video/mp4',
                        'video/avi',
                        'video/mpeg',
                        'video/quicktime',
                    ])
                    ->openable()
                    ->previewable()
                    ->downloadable()
                    ->reorderable(),
                Hidden::make('reporter_id')->default(auth()->user()->id)
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
            ->query(function (Report $report) {
                if (!auth()->user()->hasPermission('reports.viewAll')) {
                    return $report->where('reporter_id', auth()->user()->id);
                } else {
                    return $report;
                }
            })
            ->actions([
                Tables\Actions\Action::make('print')
                    ->label(__('attributes.print'))
                    ->color('success')
                    ->icon('heroicon-o-printer')
                    ->url(fn(Report $report) => route('print', $report))
                    ->visible(auth()->user()->hasPermission('report.print'))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Action::make('viewAttachments')
                    ->label(__('attributes.attachments_view'))
                    ->icon('heroicon-o-paper-clip')
                    ->color('gray')
                    ->modalHeading(__('attributes.attachments'))
                    ->modalWidth(MaxWidth::FourExtraLarge)
                    ->modalSubmitAction(false)
                    ->modalContent(function ($record) {
                        $attachments = $record->attachments;
                        if (!empty($attachments)) {
                            return view('components.attachment-viewer', compact('attachments'));
                        }
                    }),
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
