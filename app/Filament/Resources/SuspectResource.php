<?php

namespace App\Filament\Resources;

use App\Enums\EntryCities;
use App\Enums\EntryCitizenship;
use App\Enums\EntryCivilStatus;
use App\Enums\EntryEducational;
use App\Enums\EntryGender;
use App\Enums\EntryProvinces;
use App\Filament\Resources\SuspectResource\Pages;
use App\Filament\Resources\SuspectResource\RelationManagers;
use App\Models\Entry;
use App\Models\Suspect;
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

class SuspectResource extends Resource
{
    protected static ?string $model = Suspect::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'RECORD FORM';

    protected static ?string $navigationLabel = 'Suspects Data';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    protected static ?int $navigationSort = 3;


    protected static array $statuses = [
        'complete' => 'Complete',
        'lacking' => 'Lacking Documents'
    ];


    protected static array $cases = [
        'Settled' => 'Settled',
        'Convicted' => 'Convicted',
        'Dismissed' => 'Dismissed',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ITEM "B" - BASIC INFORMATION')
                    ->icon('heroicon-m-folder-minus')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([

                            Forms\Components\TextInput::make('s_fm')
                                ->label('FAMILY NAME'),

                            Forms\Components\TextInput::make('s_fn')
                                ->label('FIRST NAME'),

                            Forms\Components\TextInput::make('s_mn')
                                ->label('MIDDLE NAME'),

                            Forms\Components\TextInput::make('s_q')
                                ->label('QUALIFIER')
                                ->helperText('Examples: Jr./Sr./I/II/III'),

                            Forms\Components\TextInput::make('s_n')
                                ->label('NICKNAME')
                                ->helperText('Optional'),

                        ]),
                    ]),

                Forms\Components\Grid::make(4)
                    ->schema([
                        Forms\Components\Select::make('s_citizen')
                            ->label('CITIZENSHIP')
                            ->searchable()
                            ->options([
                                'Filipino' => EntryCitizenship::FILIPINO->value,
                                'American' => EntryCitizenship::AMERICAN->value,
                                'Chinese' => EntryCitizenship::CHINESE->value,
                                'British' => EntryCitizenship::BRITISH->value,
                                'Korean' => EntryCitizenship::KOREAN->value,
                            ]),

                        Forms\Components\Select::make('s_gender')
                            ->label('SEX / GENDER')
                            ->searchable()
                            ->options([
                                'Male' => EntryGender::MALE->value,
                                'Female' => EntryGender::FEMALE->value,
                            ]),


                        Forms\Components\Select::make('s_civil')
                            ->label('CIVIL STATUS')
                            ->searchable()
                            ->options([
                                'Single' =>EntryCivilStatus::SINGLE->value,
                                'Married' => EntryCivilStatus::MARRIED->value,
                                'Widowed' => EntryCivilStatus::WIDOWED->value,
                                'Separated' => EntryCivilStatus::SEPARATED->value,
                            ]),

                        Forms\Components\DatePicker::make('s_dob')
                            ->label('DATE OF BIRTH'),

                        Forms\Components\TextInput::make('s_age')
                            ->label('AGE')
                            ->numeric(),

                        Forms\Components\TextInput::make('s_pob')
                            ->label('PLACE OF BIRTH'),

                        Forms\Components\TextInput::make('s_mp')
                            ->label('MOBILE PHONE'),
                    ]),

                Forms\Components\Section::make('ADDRESS INFORMATION')
                    ->icon('heroicon-m-home')
                    ->iconColor('primary')
                    ->schema([

                        Forms\Components\Grid::make(5)
                            ->schema([
                                Forms\Components\TextInput::make('s_current')
                                    ->label('CURRENT ADDRESS (HOUSE NUM./STREET)'),

                                Forms\Components\TextInput::make('s_village')
                                    ->label('VILLAGE/SITIO/ZONE'),

                                Forms\Components\TextInput::make('s_barangay')
                                    ->label('BARANGAY'),

                                Forms\Components\TextInput::make('s_town')
                                    ->label('TOWN/CITY'),

                                Forms\Components\Select::make('s_province')
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
                                Forms\Components\TextInput::make('s_other')
                                    ->label('OTHER ADDRESS (HOUSE NUM./STREET)')
                                    ->helperText('Optional'),

                                Forms\Components\TextInput::make('s_villagee')
                                    ->label('VILLAGE/SITIO/ZONE'),

                                Forms\Components\TextInput::make('s_barangayy')
                                    ->label('BARANGAY'),

                                Forms\Components\TextInput::make('s_town')
                                    ->label('TOWN/CITY'),

                                Forms\Components\Select::make('s_provincee')
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
                    ]),

                Forms\Components\Section::make('OTHER RELEVANT INFORMATION')
                    ->icon('heroicon-m-square-3-stack-3d')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Select::make('s_highest')
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

                        Forms\Components\TextInput::make('s_occupation')
                            ->label('OCCUPATION'),

                        Forms\Components\TextInput::make('s_work')
                            ->label('WORK ADDRESS'),

                        Forms\Components\TextInput::make('s_relation')
                            ->label('RELATION TO VICTIM'),

                        Forms\Components\TextInput::make('s_email')
                            ->label('EMAIL ADDRESS (if Any)')
                            ->email()
                            ->unique(ignoreRecord: true),
                    ])->collapsible()->columns(5),

                Forms\Components\Section::make('SUSPECTS IDENTITY')
                    ->icon('heroicon-m-user')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('afp_personnel')
                                    ->label('IF AFP/PNP PERSONNEL: RANK'),

                                Forms\Components\TextInput::make('unit')
                                    ->label('UNIT ASSIGNMENT'),

                                Forms\Components\TextInput::make('group')
                                    ->label('GROUP AFFILIAION'),

                                Forms\Components\TextInput::make('previous_record')
                                    ->label('WITH PREVIOUS CRIMINAL RECORD?')
                                    ->helperText('Type: YES or NO. If YES, Pls. Specify'),
                            ]),

                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('height')
                                    ->label('HEIGHT')
                                    ->helperText('Input inches')
                                    ->numeric(),

                                Forms\Components\TextInput::make('weight')
                                    ->label('WEIGHT')
                                    ->helperText('Input KILOGRAMS')
                                    ->numeric(),

                                Forms\Components\TextInput::make('built')
                                    ->label('BUILT'),

                                Forms\Components\TextInput::make('color_eyes')
                                    ->label('COLOR OF EYES'),

                                Forms\Components\TextInput::make('description_eyes')
                                    ->label('DESCRIPTION OF EYES'),

                                Forms\Components\TextInput::make('color_hair')
                                    ->label('COLOR OF HAIR'),

                                Forms\Components\TextInput::make('description_hair')
                                    ->label('DESCRIPTION OF HAIR'),

                                Forms\Components\TextInput::make('under')
                                    ->label('UNDER THE INFLUENCE?')
                                    ->helperText('Type: NO, DRUGS, LIQUOR or OTHERS'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('')
                    ->weight(FontWeight::Bold),

                //SUSPECT TABLE
                Tables\Columns\TextColumn::make('s_fm')
                    ->label('Family Name')
                    ->sortable()
                    ->searchable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('s_fn')
                    ->label('First Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('s_mn')
                    ->label('Middle Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_q')
                    ->label('Qualifier')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_n')
                    ->label('Nickname')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_citizen')
                    ->label('Citizenship')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_gender')
                    ->label('Sex/Gender')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('s_civil')
                    ->label('Civil Status')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_dob')
                    ->label('Date Of Birth')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_age')
                    ->label('Age')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_pob')
                    ->label('Place Of Birth')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_mp')
                    ->label('Mobile Phone')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-square-2-stack')
                    ->copyMessage('Mobile Phone copied'),

                Tables\Columns\TextColumn::make('s_current')
                    ->label('Current Address')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('s_village')
                    ->label('Village/Sitio/Zone')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_barangay')
                    ->label('Barangay')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('s_town')
                    ->label('Town/City')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_province')
                    ->label('Province')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('s_other')
                    ->label('Other Address')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_villagee')
                    ->label('Village/Sitio/Zone')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_barangayy')
                    ->label('Barangay')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_townn')
                    ->label('Town/City')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_provincee')
                    ->label('Province')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_highest')
                    ->label('Highest Educational Attainment')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_occupation')
                    ->label('Occupation')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('s_work')
                    ->label('Work Address')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('s_relation')
                    ->label('Relation to Victim')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('s_email')
                    ->label('Email Address')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('afp_personnel')
                    ->label('IF AFP/PNP PERSONNEL:RANK')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('unit')
                    ->label('Unit Assignment')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('group')
                    ->label('Group Affiliation')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('previous_record')
                    ->label('With Prev Crimanal Record?')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('height')
                    ->label('Height')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('weight')
                    ->label('Weight')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('built')
                    ->label('Built')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('color_eyes')
                    ->label('Color of Eyes')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('description_eyes')
                    ->label('Description of Eyes')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('color_hair')
                    ->label('Color of Hair')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('description_hair')
                    ->label('Description of Hair')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('under')
                    ->label('Under of Influence?')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                            $indicators['created_from'] = 'Suspects from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Suspects until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
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
            'index' => Pages\ListSuspects::route('/'),
//            'create' => Pages\CreateSuspect::route('/create'),
//            'edit' => Pages\EditSuspect::route('/{record}/edit'),
        ];
    }
}
