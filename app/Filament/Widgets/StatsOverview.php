<?php

namespace App\Filament\Widgets;

use App\Models\Children;
use App\Models\Entry;
use App\Models\Suspect;
use App\Models\Victim;
use App\Enums\EntryTypeIncident;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;


    protected int | string | array $columnSpan = "full";

    protected function getStats(): array
    {
        return [
            Stat::make('TOTAL INCIDENT: LIBEL', Entry::where('type',  EntryTypeIncident::LIBEL->value)->count())
                ->description('Sec. 4(c)(4) Libel/Act. 355')
                ->color('primary'),

            Stat::make('TOTAL INCIDENT: ILLEGAL ACCESS', Entry::where('type',  EntryTypeIncident::ACCESS->value)->count())
                ->description('Sec. 4(a)(1) Illegal Access')
                ->color('primary'),

            Stat::make('TOTAL INCIDENT: IDENTITY THEFT', Entry::where('type',  EntryTypeIncident::IDENTITY->value)->count())
                ->description('Sec. 4(b)(3) Computer-Related Identity Theft')
                ->color('primary'),

            Stat::make('TOTAL INCIDENT: ONLINE SCAM', Entry::where('type',  EntryTypeIncident::ONLINE_SCAM->value)->count())
                ->description('Online Scam (Violation of Art. 315 of RPC in Rel. Sec. 6 of RA 10175')
                ->color('primary'),
        ];

    }
}
