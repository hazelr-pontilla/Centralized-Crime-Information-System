<?php

namespace App\Filament\Widgets;

use App\Models\Entry;
use App\Models\Role;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use App\Enums\EntryTypeIncident;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestEntries extends BaseWidget
{
    protected static ?int $sort = 5;

    protected static ?string $maxHeight = '500px';

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Entry::query()->latest();
    }

    protected function getTableFilters(): array
    {
        return [

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

            Tables\Filters\Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn(Builder $query, $date): Builder => $query
                                ->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn(Builder $query, $date): Builder => $query
                                ->whereDate('created_at', '<=', $date),
                        );
            }),

        ];

    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->label('Report Status')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('case')
                ->badge()
                ->label('Case Status')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('entryID')
                ->label('Document Number')
                ->sortable()
                ->searchable()
                ->weight(FontWeight::Bold),

            Tables\Columns\TextColumn::make('assignedBy.name')
                ->badge()
                ->label('Created By')
                ->searchable()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('assignedTo.name')
                ->searchable()
                ->label('Investigator-On-Duty')
                ->badge()
                ->sortable(),

            Tables\Columns\TextColumn::make('type')
                ->label('Type of Incident')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->date()
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('case')
                ->label('Added Cases')
                ->sortable()
                ->searchable()
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
                ->searchable(),

            Tables\Columns\TextColumn::make('r_town')
                ->label('Town/City')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('r_province')
                ->label('Province')
                ->sortable()
                ->searchable(),

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

            Tables\Columns\TextColumn::make('note')
                ->label('Disposition of IOC')
                ->toggleable(isToggledHiddenByDefault: true),
        ];


    }


}
