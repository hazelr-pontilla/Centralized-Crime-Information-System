<?php

namespace App\Filament\Resources\ChildrenResource\RelationManagers;

use App\Models\Entry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    protected static ?string $title = 'Minor';

    protected static ?string $icon = 'heroicon-o-user';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('BASIC INFORMATION')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('NAME OF GUARDIAN'),

                        Forms\Components\TextInput::make('address')
                            ->label('GUARDIAN ADDRESS'),

                        Forms\Components\TextInput::make('mobile_phone')
                            ->label('MOBILE PHONE'),

                    ])->columns(3),

                Forms\Components\Textarea::make('remarks')
                    ->label('Remarks')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('')
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('remarks')
                    ->label('Remarks')
                    ->weight(FontWeight::Medium),

                //CHILDREN IN CONFLICT TABLES
                Tables\Columns\TextColumn::make('name')
                    ->label('Name of Guardian')
                    ->sortable()
                    ->searchable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('address')
                    ->label('Guardian Address')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('mobile_phone')
                    ->label('Mobile Phone')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-square-2-stack')
                    ->copyMessage('Mobile Phone copied'),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->label('Created At')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Update')
                    ->since()
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\ViewAction::make()
                        ->color('primary'),

                    Tables\Actions\EditAction::make()
                        ->successNotificationTitle('Updated Entry Successfully')
                        ->color('warning'),

                ])->tooltip('Actions')->icon('heroicon-m-ellipsis-horizontal')
            ])

            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Minors from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Minors until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->persistFiltersInSession()
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->icon('heroicon-m-funnel')
                    ->color('warning')
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\ViewAction::make()
                        ->color('primary'),

                    Tables\Actions\EditAction::make()
                        ->successNotificationTitle('Updated Entry Successfully')
                        ->color('warning'),

                ])->tooltip('Actions')->icon('heroicon-m-ellipsis-horizontal')
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add New Record'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                ]),
            ]);
    }
}
