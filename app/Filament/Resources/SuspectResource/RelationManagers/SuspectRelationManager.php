<?php

namespace App\Filament\Resources\SuspectResource\RelationManagers;

use App\Enums\EntryCitizenship;
use App\Enums\EntryCivilStatus;
use App\Enums\EntryEducational;
use App\Enums\EntryGender;
use App\Enums\EntryProvinces;
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

class SuspectRelationManager extends RelationManager
{
    protected static string $relationship = 'suspect';

    protected static ?string $title = 'Lead Suspect';

    protected static ?string $icon = 'heroicon-o-user-group';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('BASIC INFORMATION')
                    ->icon('heroicon-m-inbox-stack')
                    ->iconColor('success')
                    ->schema([

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

                    ])->columns(4)->collapsible(),


                Forms\Components\Section::make('ADDRESS INFORMATION')
                    ->icon('heroicon-m-home')
                    ->iconColor('success')
                    ->schema([

                        Forms\Components\Grid::make(5)
                            ->schema([
                                Forms\Components\TextInput::make('s_current')
                                    ->label('CURRENT ADDRESS'),

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
                                    ->label('OTHER ADDRESS')
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
                    ])->collapsible(),

                Forms\Components\Section::make('OTHER RELEVANT INFORMATION')
                    ->icon('heroicon-m-square-3-stack-3d')
                    ->iconColor('success')
                    ->schema([
                        Forms\Components\Select::make('s_highest')
                            ->label('EDUCATIONAL ATTAINMENT')
                            ->searchable()
                            ->options([
                                'Doctoral Degree' => EntryEducational::DOCTORAL->value,
                                'Masters Degree' => EntryEducational::MASTERS->value,
                                'Bachelors Degree' => EntryEducational::BACHELOR->value,
                                'High School Diploma' => EntryEducational::HIGHSCHOOL->value,
                                'Elementary Education' => EntryEducational::ELEMENTARY->value,
                                'No Formal Education' => EntryEducational::NO_FORMAL->value,
                                'Vocational or Technical Certifications' => EntryEducational::VOCATIONAL->value,
                            ]),

                        Forms\Components\TextInput::make('s_occupation')
                            ->label('OCCUPATION'),

                        Forms\Components\TextInput::make('s_work')
                            ->label('WORK ADDRESS'),

                        Forms\Components\TextInput::make('s_relation')
                            ->label('RELATION TO VICTIM'),

                        Forms\Components\TextInput::make('s_email')
                            ->label('EMAIL ADDRESS')
                            ->email()
                            ->unique(ignoreRecord: true),
                    ])->collapsible()->columns(5),


                Forms\Components\Section::make('SUSPECTS IDENTITY')
                    ->icon('heroicon-m-shield-exclamation')
                    ->iconColor('success')
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
                                    ->helperText('INPUT INCHES')
                                    ->numeric(),

                                Forms\Components\TextInput::make('weight')
                                    ->label('WEIGHT')
                                    ->helperText('INPUT KILOGRAMS')
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

                    ])->collapsible(),

                Forms\Components\Textarea::make('remarks')
                    ->label('REMARKS')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('s_fm')
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('')
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('remarks')
                    ->label('Remarks')
                    ->weight(FontWeight::Medium),

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
                    ->copyMessage('MobilePhone copied'),

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
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),


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
            ])
            ->persistFiltersInSession()
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->icon('heroicon-m-funnel')
                    ->color('warning')
                    ->button()
                    ->label('Filter'),
            )
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add New Record'),
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                ]),
            ]);
    }
}
