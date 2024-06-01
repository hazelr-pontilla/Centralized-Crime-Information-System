<?php

namespace App\Filament\Resources;

use App\Enums\EntryCities;
use App\Enums\EntryCitizenship;
use App\Enums\EntryCivilStatus;
use App\Enums\EntryEducational;
use App\Enums\EntryGender;
use App\Enums\EntryProvinces;
use App\Filament\Resources\VictimResource\Pages;
use App\Filament\Resources\VictimResource\RelationManagers;
use App\Models\Entry;
use App\Models\Victim;
use Complex\Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class VictimResource extends Resource
{
    protected static ?string $model = Victim::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'RECORD FORM';

    protected static ?string $navigationLabel = 'Victims Data';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?int $navigationSort = 3;

    protected static array $cases = [
        'Settled' => 'Settled',
        'Convicted' => 'Convicted',
        'Dismissed' => 'Dismissed',
    ];
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ITEM "A" - BASIC INFORMATION')
                    ->icon('heroicon-m-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([

                            Forms\Components\TextInput::make('v_fm')
                                ->label('FAMILY NAME'),

                            Forms\Components\TextInput::make('v_fn')
                                ->label('FIRST NAME'),

                            Forms\Components\TextInput::make('v_mn')
                                ->label('MIDDLE NAME'),

                            Forms\Components\TextInput::make('v_q')
                                ->label('QUALIFIER')
                                ->helperText('Examples: Jr./Sr./I/II/III'),

                            Forms\Components\TextInput::make('v_n')
                                ->label('NICKNAME')
                                ->helperText('Optional'),
                        ]),

                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\Select::make('v_citizen')
                                    ->label('CITIZENSHIP')
                                    ->searchable()
                                    ->options([
                                        'Filipino' => EntryCitizenship::FILIPINO->value,
                                        'American' => EntryCitizenship::AMERICAN->value,
                                        'Chinese' => EntryCitizenship::CHINESE->value,
                                        'British' => EntryCitizenship::BRITISH->value,
                                        'Korean' => EntryCitizenship::KOREAN->value,
                                    ]),

                                Forms\Components\Select::make('v_gender')
                                    ->label('SEX / GENDER')
                                    ->searchable()
                                    ->options([
                                        'Male' => EntryGender::MALE->value,
                                        'Female' => EntryGender::FEMALE->value,
                                    ]),

                                Forms\Components\Select::make('v_civil')
                                    ->label('CIVIL STATUS')
                                    ->searchable()
                                    ->options([
                                        'Single' =>EntryCivilStatus::SINGLE->value,
                                        'Married' => EntryCivilStatus::MARRIED->value,
                                        'Widowed' => EntryCivilStatus::WIDOWED->value,
                                        'Separated' => EntryCivilStatus::SEPARATED->value,
                                    ]),

                                Forms\Components\DatePicker::make('v_dob')
                                    ->label('DATE OF BIRTH'),

                                Forms\Components\TextInput::make('v_age')
                                    ->label('AGE')
                                    ->numeric(),

                                Forms\Components\TextInput::make('v_pob')
                                    ->label('PLACE OF BIRTH'),

                                Forms\Components\TextInput::make('v_mp')
                                    ->label('MOBILE PHONE'),

                            ]),
                    ])->collapsible(),


                Forms\Components\Section::make('ADDRESS INFORMATION')
                    ->icon('heroicon-m-home')
                    ->iconColor('primary')
                    ->schema([

                        Forms\Components\Grid::make(5)
                            ->schema([
                                Forms\Components\TextInput::make('v_current')
                                    ->label('CURRENT ADDRESS (HOUSE NUM./STREET)'),

                                Forms\Components\TextInput::make('v_village')
                                    ->label('VILLAGE/SITIO/ZONE'),

                                Forms\Components\TextInput::make('v_barangay')
                                    ->label('BARANGAY'),

                                Forms\Components\TextInput::make('v_town')
                                    ->label('TOWN/CITY'),

                                Forms\Components\Select::make('v_province')
                                    ->label('PROVINCE')
                                    ->searchable()
                                    ->options([
                                        'Leyte' => EntryProvinces::LEYTE->value,
                                        'Southern Leyte' => EntryProvinces::SOUTHERN->value,
                                        'Samar (Western Samar)' => EntryProvinces::SAMAR->value,
                                        'Biliran' => EntryProvinces::BILIRAN->value,
                                        'Eastern Samar' => EntryProvinces::EASTERN->value,
                                        'Northern Samar' => EntryProvinces::NORTHERN->value,
                                    ]),
                            ]),

                        Forms\Components\Grid::make(5)
                            ->schema([
                                Forms\Components\TextInput::make('v_other')
                                    ->label('OTHER ADDRESS (HOUSE NUM./STREET)')
                                    ->helperText('Optional'),

                                Forms\Components\TextInput::make('v_villagee')
                                    ->label('VILLAGE/SITIO/ZONE'),

                                Forms\Components\TextInput::make('v_barangayy')
                                    ->label('BARANGAY'),

                                Forms\Components\Select::make('v_townn')
                                    ->label('TOWN/CITY'),

                                Forms\Components\Select::make('v_provincee')
                                    ->label('PROVINCE')
                                    ->searchable()
                                    ->options([
                                        'Leyte' => EntryProvinces::LEYTE->value,
                                        'Southern Leyte' => EntryProvinces::SOUTHERN->value,
                                        'Samar (Western Samar)' => EntryProvinces::SAMAR->value,
                                        'Biliran' => EntryProvinces::BILIRAN->value,
                                        'Eastern Samar' => EntryProvinces::EASTERN->value,
                                        'Northern Samar' => EntryProvinces::NORTHERN->value,
                                    ]),
                            ]),
                    ])->collapsible(),

                Forms\Components\Section::make('OTHER RELEVANT INFORMATION')
                    ->icon('heroicon-m-square-3-stack-3d')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Select::make('v_highest')
                            ->label('HIGHEST EDUCATIONAL ATTAINMENT')
                            ->searchable()
                            ->options([
                                'Doctoral Degree' => EntryEducational::DOCTORAL->value,
                                'Masters Degree' => EntryEducational::MASTERS->value,
                                'Bachelors Degree' => EntryEducational::BACHELOR->value,
                                'Vocational or Technical Certifications' => EntryEducational::VOCATIONAL->value,
                                'High School Diploma' => EntryEducational::HIGHSCHOOL->value,
                                'Elementary Education' => EntryEducational::ELEMENTARY->value,
                                'No Formal Education' => EntryEducational::NO_FORMAL->value,
                            ]),

                        Forms\Components\TextInput::make('v_occupation')
                            ->label('OCCUPATION'),

                        Forms\Components\TextInput::make('v_work')
                            ->label('WORK ADDRESS'),

                        Forms\Components\TextInput::make('v_email')
                            ->label('EMAIL ADDRESS (if Any)')
                            ->email()
                            ->unique(ignoreRecord: true),
                    ])->collapsible()->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('')
                    ->weight(FontWeight::Bold),

                //VICTIMS TABLE
                Tables\Columns\TextColumn::make('v_fm')
                    ->label('Family Name')
                    ->sortable()
                    ->searchable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('v_fn')
                    ->label('First Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('v_mn')
                    ->label('Middle Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_q')
                    ->label('Qualifier')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_n')
                    ->label('Nickname')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_citizen')
                    ->label('Citizenship')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_gender')
                    ->label('Sex/Gender')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_civil')
                    ->label('Civil Status')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_dob')
                    ->label('Date Of Birth')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_age')
                    ->label('Age')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_pob')
                    ->label('Place Of Birth')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_mp')
                    ->label('Mobile Phone')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-square-2-stack')
                    ->copyMessage('MobilePhone copied'),

                Tables\Columns\TextColumn::make('v_current')
                    ->label('Current Address')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('v_village')
                    ->label('Village/Sitio/Zone')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_barangay')
                    ->label('Barangay')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('v_town')
                    ->label('Town/City')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('v_province')
                    ->label('Province')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('v_other')
                    ->label('Other Address')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_villagee')
                    ->label('Village/Sitio/Zone')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_barangayy')
                    ->label('Barangay')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_townn')
                    ->label('Town/City')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_provincee')
                    ->label('Province')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_highest')
                    ->label('Highest Educational Attainment')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_occupation')
                    ->label('Occupation')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('v_work')
                    ->label('Work Address')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('v_email')
                    ->label('Email Address')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->searchable()
                ->label('Created At'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Update')
                    ->since()
                    ->sortable()
                    ->searchable(),
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
                            $indicators['created_from'] = 'Victims from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Victims until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

                Tables\Filters\TrashedFilter::make(),

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

                    Tables\Actions\DeleteAction::make()
                        ->color('danger')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('IRF Entry Updated Successfully!')
                                ->body('Record deleted successfully!')
                        )

                ])->tooltip('Actions')->icon('heroicon-m-ellipsis-horizontal')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
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
            'index' => Pages\ListVictims::route('/'),
        ];
    }
}
