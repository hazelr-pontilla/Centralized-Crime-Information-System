<?php

namespace App\Filament\Resources;

use Filament\Tables\Enums\FiltersLayout;
use App\Enums\EntryCities;
use App\Enums\EntryCitizenship;
use App\Enums\EntryCivilStatus;
use App\Enums\EntryEducational;
use App\Enums\EntryGender;
use App\Enums\EntryIDPresented;
use App\Enums\EntryProvinces;
use App\Enums\EntryTypeIncident;
use App\Filament\Resources\ChildrenResource\RelationManagers\ChildrenRelationManager;
use App\Filament\Resources\EntryResource\Pages;
use App\Filament\Resources\EntryResource\RelationManagers;
use App\Filament\Resources\SuspectResource\RelationManagers\SuspectRelationManager;
use App\Filament\Resources\VictimResource\RelationManagers\VictimRelationManager;
use App\Models\Entry;
use App\Models\Role;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Support\Carbon;

class EntryResource extends Resource
{
    protected static ?string $model = Entry::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $navigationGroup = 'RECORD FORM';

    protected static ?string $navigationLabel = 'Clients Data';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    protected static array $statuses = [
        'complete' => 'Complete',
        'incomplete' => 'Incomplete',
        'solved' => 'Solved'
    ];

    protected static array $cases = [
        'Settled' => 'Settled',
        'Convicted' => 'Convicted',
        'Dismissed' => 'Dismissed',
        'N/A' => 'N/A',
    ];

    protected static array $timestatus = [
        'PM' => 'PM',
        'AM' => 'AM',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('REPORT STATUS')
                    ->iconColor('warning')
                    ->icon('heroicon-m-scale')
                    ->schema([

                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Radio::make('status')
                                    ->label(' ')
                                    ->inline()
                                    ->options(self::$statuses),
                            ]),
                    ]),

//                Forms\Components\Section::make('CASE STATUS')
//                    ->iconColor('warning')
//                    ->icon('heroicon-m-scale')
//                    ->schema([
//                        Forms\Components\Grid::make(1)
//                            ->schema([
//                                Forms\Components\Radio::make('case')
//                                    ->label(' ')
//                                    ->inline()
//                                    ->options(self::$cases),
//                            ])
//                    ])->columnSpan(1),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('entryID')
                            ->label('DOCUMENT NUMBER')
                            ->unique(ignoreRecord: true)
                            ->autofocus()
                            ->helperText('FORMAT: MM-DD-YYYY-HHMMs'),

                        Forms\Components\Select::make('type')
                            ->label('TYPE OF INCIDENT')
                            ->options([
                                'Sec. 4(a)(1) Illegal Access' => EntryTypeIncident::ACCESS->value,
                                'Sec. 4(a)(2) Illegal Interception' => EntryTypeIncident::INTERCEPTION->value,
                                'Sec. 4(a)(3) Data Interference' => EntryTypeIncident::DATA->value,
                                'Sec. 4(a)(4) System Interference' => EntryTypeIncident::SYSTEM->value,
                                'Sec. 4(a)(5) Misuse of the Devices' => EntryTypeIncident::MISUSE->value,
                                'Sec. 4(a)(6) Cyber-Squatting' => EntryTypeIncident::CYBER->value,
                                'Sec. 4(b)(1) Computer-Related Forgery' => EntryTypeIncident::FORGERY->value,
                                'Sec. 4(b)(2) Computer-Related Fraud' => EntryTypeIncident::FRAUD->value,
                                'Sec. 4(b)(3) Computer-Related Identity Theft' => EntryTypeIncident::IDENTITY->value,
                                //
                                'Sec. 4(c)(1) Cybersex' => EntryTypeIncident::CYBERSEX->value,
                                'Sec. 4(c)(2) Child Pornography/RA 9775' => EntryTypeIncident::CHILD->value,
                                'Sec. 4(c)(3) Unsolicited Commercial Communication' => EntryTypeIncident::UNSOLICITED->value,
                                'Sec. 4(c)(4) Libel/Act. 355' => EntryTypeIncident::LIBEL->value,
                                //
                                'Sec. 5(a)(1) Aiding/Abetting in Commission of Cybercrime' => EntryTypeIncident::AIDING->value,
                                'Sec. 5(b)(2) Attempt in the Commission of Crime' => EntryTypeIncident::ATTEMPT->value,
                                'RA 9995 (Anti Photo and Video Voyeurism Act)' => EntryTypeIncident::ANTI_PHOTO->value,
                                'RA 10364 / RA 9208 (Expanded Trafficking in Person Act)' => EntryTypeIncident::EXPANDED->value,
                                'RA 9262 (Violence Against Woman and their Children)' => EntryTypeIncident::VIOLENCE->value,
                                'RA 7610 (Special Protection of Child Against Abuse and Exploitation)' => EntryTypeIncident::SPECIAL->value,
                                'RA 8484 (Access Device and Regulation Act) Ammended RA 11449' => EntryTypeIncident::ACCESS_DEVICE->value,
                                'RA 10627 (Cyber Bullying)' => EntryTypeIncident::CYBER_BULLYING->value,
                                'Art. 282 of RPC (Grave Threat/Sextortion)' => EntryTypeIncident::GRAVE_THREAT->value,
                                'Art. 286 of RPC (Grave Coercion)' => EntryTypeIncident::GRAVE_COERCION->value,
                                'Art. 287 of RPC (Robbery Extortion)' => EntryTypeIncident::ROBBERY->value,
                                'Online Scam (Violation of Art. 315 of RPC in Rel. Sec. 6 of RA 10175)' => EntryTypeIncident::ONLINE_SCAM->value,
                                'RA 11930 (OSAEC)' => EntryTypeIncident::OSAEC->value,
                            ])->searchable(),

                        Forms\Components\Textarea::make('added_case')
                            ->label('ADDED CASES')
                            ->helperText('Optional, if there are additional cases.')
                            ->rows(2)
                    ])->columns(3),

                Forms\Components\Section::make('DOCUMENT SECTION')
                    ->icon('heroicon-m-inbox-stack')
                    ->iconColor('success')
                    ->description('This is for the IOC Documentary')
                    ->schema([
                        Forms\Components\Section::make('DOCUMENTARY')
                            ->schema([
                                Forms\Components\Toggle::make('complain_affidavit')
                                    ->label('Complain Affidavit')
                                    ->onIcon('heroicon-s-check')
                                    ->offIcon('heroicon-m-x-mark')
                                    ->onColor('success')
                                    ->offColor('danger'),

                                Forms\Components\Toggle::make('affidavit_witnesses')
                                    ->label('Affidavit of Witnesses')
                                    ->onIcon('heroicon-s-check')
                                    ->offIcon('heroicon-m-x-mark')
                                    ->onColor('success')
                                    ->offColor('danger'),
                            ])->columnSpan(1),

                        Forms\Components\Section::make('SCREENSHOTS')
                            ->schema([

//                                Forms\Components\Toggle::make('post')
//                                    ->label('Post')
//                                    ->onIcon('heroicon-s-check')
//                                    ->offIcon('heroicon-m-x-mark')
//                                    ->onColor('success')
//                                    ->offColor('danger'),

                                Forms\Components\Toggle::make('transactions')
                                    ->label('Transactions')
                                    ->onIcon('heroicon-s-check')
                                    ->offIcon('heroicon-m-x-mark')
                                    ->onColor('success')
                                    ->offColor('danger'),

                                Forms\Components\Toggle::make('conversations')
                                    ->label('Conversations')
                                    ->onIcon('heroicon-s-check')
                                    ->offIcon('heroicon-m-x-mark')
                                    ->onColor('success')
                                    ->offColor('danger'),

                            ])->columnSpan(1),

                        Forms\Components\Textarea::make('note')
                            ->label('INVESTIGATORS NOTE')
                            ->rows(4)
                            ->helperText('NOTE: Use this section to write down any observations, findings, or important details.'),

                    ])->columns(3)->collapsible(),

                Forms\Components\Section::make('INCIDENT RECORD FORM')
                    ->icon('heroicon-m-folder-minus')
                    ->iconColor('success')
                    ->schema([

                        Forms\Components\Grid::make(1)
                            ->schema([

//                                Forms\Components\TextInput::make('copy')
//                                    ->label('COPY FOR'),

                                Forms\Components\TextInput::make('place')
                                    ->label('PLACE OF INCIDENT'),
                            ])->columnSpanFull(),

                        Forms\Components\Fieldset::make('DATE AND TIME REPORTED')
                            ->schema([
                                Forms\Components\DatePicker::make('reported')
                                    ->label('DATE'),

                                Forms\Components\TextInput::make('reportedTime')
                                    ->label('TIME (HOURS : MINUTES)'),

                                Forms\Components\Select::make('rclock')
                                    ->label('TIME MARKER')
                                    ->searchable()
                                    ->options(self::$timestatus)

                            ])->columns(3),

                        Forms\Components\Fieldset::make('DATE AND TIME INCIDENT')
                            ->schema([
                                Forms\Components\DatePicker::make('incident')
                                    ->label('DATE'),

                                Forms\Components\TextInput::make('incidentTime')
                                    ->label('TIME (HOURS : MINUTES)'),

                                Forms\Components\Select::make('iclock')
                                    ->label('TIME MARKER')
                                    ->searchable()
                                    ->options(self::$timestatus)

                            ])->columns(3),
                    ])->collapsible(),

                Forms\Components\Section::make('ITEM "A" - REPORTING PERSON')
                    ->icon('heroicon-m-user')
                    ->iconColor('success')
                    ->schema([
                        Forms\Components\TextInput::make('r_fm')
                            ->label('FAMILY NAME'),

                        Forms\Components\TextInput::make('r_fn')
                            ->label('FIRST NAME'),

                        Forms\Components\TextInput::make('r_mn')
                            ->label('MIDDLE NAME'),

                        Forms\Components\TextInput::make('r_q')
                            ->label('QUALIFIER')
                            ->helperText('Examples: Jr./Sr./I/II/III'),

                        Forms\Components\TextInput::make('r_n')
                            ->label('NICKNAME')
                            ->helperText('Optional'),

                        Forms\Components\Select::make('r_citizen')
                            ->label('CITIZENSHIP')
                            ->searchable()
                            ->options([
                                'Filipino' => EntryCitizenship::FILIPINO->value,
                                'American' => EntryCitizenship::AMERICAN->value,
                                'Chinese' => EntryCitizenship::CHINESE->value,
                                'British' => EntryCitizenship::BRITISH->value,
                                'Korean' => EntryCitizenship::KOREAN->value,
                            ]),

                        Forms\Components\Select::make('r_gender')
                            ->label('SEX / GENDER')
                            ->searchable()
                            ->options([
                                'Male' => EntryGender::MALE->value,
                                'Female' => EntryGender::FEMALE->value,
                            ]),

                        Forms\Components\Select::make('r_civil')
                            ->label('CIVIL STATUS')
                            ->searchable()
                            ->options([
                                'Single' =>EntryCivilStatus::SINGLE->value,
                                'Married' => EntryCivilStatus::MARRIED->value,
                                'Widowed' => EntryCivilStatus::WIDOWED->value,
                                'Separated' => EntryCivilStatus::SEPARATED->value,
                            ]),

                        Forms\Components\DatePicker::make('r_dob')
                            ->label('DATE OF BIRTH'),

                        Forms\Components\TextInput::make('r_age')
                            ->label('AGE')
                            ->numeric(),

                        Forms\Components\TextInput::make('r_pob')
                            ->label('PLACE OF BIRTH'),

                        Forms\Components\TextInput::make('r_mp')
                            ->label('MOBILE PHONE'),


                    ])->collapsible()->columns(4),


                Forms\Components\Section::make('ADDRESS INFORMATION')
                    ->icon('heroicon-m-home')
                    ->iconColor('success')
                    ->schema([

                        Forms\Components\Grid::make(5)
                            ->schema([
                                Forms\Components\TextInput::make('r_current')
                                    ->label('CURRENT ADDRESS (HOUSE NUM./STREET)'),

                                Forms\Components\TextInput::make('r_village')
                                    ->label('VILLAGE/SITIO/ZONE'),

                                Forms\Components\TextInput::make('r_barangay')
                                    ->label('BARANGAY'),

                                Forms\Components\TextInput::make('r_town')
                                    ->label('TOWN/CITY'),

                                Forms\Components\Select::make('r_province')
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
                                Forms\Components\TextInput::make('r_other')
                                    ->label('OTHER ADDRESS (HOUSE NUM./STREET)')
                                    ->helperText('Optional'),

                                Forms\Components\TextInput::make('r_villagee')
                                    ->label('VILLAGE/SITIO/ZONE'),

                                Forms\Components\TextInput::make('r_barangayy')
                                    ->label('BARANGAY'),

                                Forms\Components\TextInput::make('r_townn')
                                    ->label('TOWN/CITY'),

                                Forms\Components\Select::make('r_provincee')
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
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Select::make('r_highest')
                                    ->label('HIGHEST EDUCATIONAL ATTAINMENT')
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

                                Forms\Components\TextInput::make('r_occupation')
                                    ->label('OCCUPATION'),

                                Forms\Components\Select::make('r_id')
                                    ->label('ID_PRESENTED')
                                    ->searchable()
                                    ->options([
                                        'National ID' => EntryIDPresented::NATIONAL->value,
                                        'Drivers License' => EntryIDPresented::DRIVER->value,
                                        'Philhealth ID'  => EntryIDPresented::PHILHEALTH->value,
                                        'Senior Citizen ID' => EntryIDPresented::SENIOR->value,
                                        'Passport ID' => EntryIDPresented::PASSPORT->value,
                                        'Postal ID' => EntryIDPresented::POSTAL->value,
                                        'UMID ID' => EntryIDPresented::UMID->value,
                                        'PWD  ID' => EntryIDPresented::PWD->value,
                                        'Student ID' => EntryIDPresented::STUDENT->value,
                                        'PRC ID' => EntryIDPresented::PRC->value,
                                        'N/A' => EntryIDPresented::NA->value,
                                    ]),

                                Forms\Components\TextInput::make('r_email')
                                    ->label('EMAIL ADDRESS (if Any)')
                                    ->email()
                                    ->unique(ignoreRecord: true),
                            ])->columns(4),

                        Forms\Components\Section::make('Incident Record Form [COPY]')
                            ->schema([
                                Forms\Components\FileUpload::make('attachment_front')
                                    ->label('Front Copy')
                                    ->directory('form-front')
                                    ->image()
                                    ->imageEditor()
                                    ->openable()
                                    ->downloadable()
                                    ->preserveFilenames(),

                                Forms\Components\FileUpload::make('attachment_back')
                                    ->label('Back Copy')
                                    ->directory('form-back')
                                    ->image()
                                    ->imageEditor()
                                    ->openable()
                                    ->downloadable()
                                    ->preserveFilenames(),

                            ])->columns(2)
                    ])->collapsible(),

                Forms\Components\Section::make('Users')
                    ->schema([
                        Forms\Components\Select::make('assigned_to')
                            ->label('Assigned To')
                            ->searchable()
                            ->required()
                            ->relationship('assignedTo', 'name')
                            ->helperText('NOTE: Kindly search for your name. This document will ONLY be viewed by the assigned person in charge.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) =>
            auth()->user()->hasRole(Role::ROLES['Superadmin']) ?
                $query : $query->where('assigned_to', auth()->id())
            )
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('')
                    ->weight(FontWeight::Bold),


                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Report Status')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('case')
                    ->label('Case Status')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('entryID')
                    ->label('Document Number')
                    ->sortable()
                    ->searchable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type of Incident')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('added_case')
                    ->label('Added Cases')
                    ->sortable()
                    ->searchable()
                    ->words(10)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('copy')
                    ->label('Copy For')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('reported')
                    ->label('Date Reported')
                    ->sortable()
                    ->date()
                    ->searchable(),

                Tables\Columns\TextColumn::make('reportedTime')
                    ->label('Time Reported')
                    ->icon('heroicon-m-clock'),

                Tables\Columns\TextColumn::make('rclock')
                    ->label('Time Markers'),

                Tables\Columns\TextColumn::make('incident')
                    ->label('Date Incident')
                    ->sortable()
                    ->date()
                    ->searchable(),

                Tables\Columns\TextColumn::make('incidentTime')
                    ->label('Time Incident')
                    ->icon('heroicon-m-clock'),

                Tables\Columns\TextColumn::make('iclock')
                    ->label('Time Markers'),

                Tables\Columns\TextColumn::make('place')
                    ->label('Place of Incident')
                    ->sortable()
                    ->searchable(),

                //DOCUMENT SECTION
                Tables\Columns\IconColumn::make('complain_affidavit')
                    ->label('Complain Affidavit')
                    ->boolean()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('affidavit_witnesses')
                    ->label('Affidavit of Witnesses')
                    ->boolean()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('post')
                    ->label('Post')
                    ->boolean()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('transactions')
                    ->label('Transactions')
                    ->boolean()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('conversations')
                    ->label('Conversations')
                    ->boolean()
                    ->sortable()
                    ->searchable(),

                //REPORTING PERSON INFORMATION
                Tables\Columns\TextColumn::make('r_fm')
                    ->label('Family Name')
                    ->sortable()
                    ->searchable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('r_fn')
                    ->label('First Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('r_mn')
                    ->label('Middle Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_q')
                    ->label('Qualifier')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_n')
                    ->label('Nickname')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_citizen')
                    ->label('Citizenship')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_gender')
                    ->label('Sex/Gender')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('r_civil')
                    ->label('Civil Status')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('r_dob')
                    ->label('Date Of Birth')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_age')
                    ->label('Age')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_pob')
                    ->label('Place Of Birth')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_mp')
                    ->label('Mobile Phone')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Mobile phone copied')
                    ->icon('heroicon-m-square-2-stack')
                    ->copyMessage('Mobile Phone copied'),

                Tables\Columns\TextColumn::make('r_current')
                    ->label('Current Address')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_village')
                    ->label('Village/Sitio/Zone')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_barangay')
                    ->label('Barangay')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_town')
                    ->label('Town/City')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_province')
                    ->label('Province')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_other')
                    ->label('Other Address')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_villagee')
                    ->label('Village/Sitio/Zone')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_barangayy')
                    ->label('Barangay')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_townn')
                    ->label('Town/City')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_provincee')
                    ->label('Province')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_highest')
                    ->label('Highest Educational Attainment')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_occupation')
                    ->label('Occupation')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('r_id')
                    ->label('ID Presented')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('r_email')
                    ->label('Email Address')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Update')
                    ->since()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('assignedBy.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Assigned To')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('note')
                    ->label('Investigators Note')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('type')
                ->label('Select Type of Incident')
                ->multiple()
                ->options([
                    'Sec. 4(a)(1) Illegal Access' => EntryTypeIncident::ACCESS->value,
                    'Sec. 4(a)(2) Illegal Interception' => EntryTypeIncident::INTERCEPTION->value,
                    'Sec. 4(a)(3) Data Interference' => EntryTypeIncident::DATA->value,
                    'Sec. 4(a)(4) System Interference' => EntryTypeIncident::SYSTEM->value,
                    'Sec. 4(a)(5) Misuse of the Devices' => EntryTypeIncident::MISUSE->value,
                    'Sec. 4(a)(6) Cyber-Squatting' => EntryTypeIncident::CYBER->value,
                    'Sec. 4(b)(1) Computer-Related Forgery' => EntryTypeIncident::FORGERY->value,
                    'Sec. 4(b)(2) Computer-Related Fraud' => EntryTypeIncident::FRAUD->value,
                    'Sec. 4(b)(3) Computer-Related Identity Theft' => EntryTypeIncident::IDENTITY->value,
                    //
                    'Sec. 4(c)(1) Cybersex' => EntryTypeIncident::CYBERSEX->value,
                    'Sec. 4(c)(2) Child Pornography/RA 9775' => EntryTypeIncident::CHILD->value,
                    'Sec. 4(c)(3) Unsolicited Commercial Communication' => EntryTypeIncident::UNSOLICITED->value,
                    'Sec. 4(c)(4) Libel/Act. 355' => EntryTypeIncident::LIBEL->value,
                    //
                    'Sec. 5(a)(1) Aiding/Abetting in Commission of Cybercrime' => EntryTypeIncident::AIDING->value,
                    'Sec. 5(b)(2) Attempt in the Commission of Crime' => EntryTypeIncident::ATTEMPT->value,
                    'RA 9995 (Anti Photo and Video Voyeurism Act)' => EntryTypeIncident::ANTI_PHOTO->value,
                    'RA 10364 / RA 9208 (Expanded Trafficking in Person Act)' => EntryTypeIncident::EXPANDED->value,
                    'RA 9262 (Violence Against Woman and their Children)' => EntryTypeIncident::VIOLENCE->value,
                    'RA 7610 (Special Protection of Child Against Abuse and Exploitation)' => EntryTypeIncident::SPECIAL->value,
                    'RA 8484 (Access Device and Regulation Act) Ammended RA 11449' => EntryTypeIncident::ACCESS_DEVICE->value,
                    'RA 10627 (Cyber Bullying)' => EntryTypeIncident::CYBER_BULLYING->value,
                    'Art. 282 of RPC (Grave Threat/Sextortion)' => EntryTypeIncident::GRAVE_THREAT->value,
                    'Art. 286 of RPC (Grave Coercion)' => EntryTypeIncident::GRAVE_COERCION->value,
                    'Art. 287 of RPC (Robbery Extortion)' => EntryTypeIncident::ROBBERY->value,
                    'Online Scam (Violation of Art. 315 of RPC in Rel. Sec. 6 of RA 10175)' => EntryTypeIncident::ONLINE_SCAM->value,
                    'RA 11930 (OSAEC)' => EntryTypeIncident::OSAEC->value,
                ])->searchable(),


                Tables\Filters\SelectFilter::make('case')
                    ->label('Case Status')
                    ->options(self::$cases)
                    ->searchable()
                    ->preload(),


                Tables\Filters\SelectFilter::make('assigned_by')
                    ->label('Created By')
                    ->relationship('assignedBy', 'name')
                    ->preload()
                    ->searchable(),

                Tables\Filters\TrashedFilter::make()
                    ->searchable(),

                Tables\Filters\SelectFilter::make('assigned_by')
                    ->label('Created By')
                    ->relationship('assignedBy', 'name')
                    ->preload()
                    ->searchable(),


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
                            $indicators['created_from'] = 'IRF Entry from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'IRF Entry until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),




            ],layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(5)
            ->persistFiltersInSession()


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
            SuspectRelationManager::class,
            VictimRelationManager::class,
            ChildrenRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntries::route('/'),
            'create' => Pages\CreateEntry::route('/create'),
            'edit' => Pages\EditEntry::route('/{record}/edit'),
        ];
    }

}
