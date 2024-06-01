<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EntryCaseStatus: string implements  HasLabel, HasColor, HasIcon
{
    case SETTLED = 'Settled';
    case CONVICTED = 'Convicted';
    case DISMISSED = 'Dismissed';
    case NA = 'N/A';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SETTLED => 'Settled',
            self::CONVICTED => 'Convicted',
            self::DISMISSED => 'Dismissed',
            self::NA => 'N/A',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SETTLED => 'success',
            self::CONVICTED => 'warning',
            self::DISMISSED => 'danger',
            self::NA => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::SETTLED => 'heroicon-m-hand-thumb-up',
            self::CONVICTED => 'heroicon-m-arrow-down-on-square-stack',
            self::DISMISSED => 'heroicon-m-hand-raised',
            self::NA => 'heroicon-m-hand-thumb-up',
        };
    }
}
