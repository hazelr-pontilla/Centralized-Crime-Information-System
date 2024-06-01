<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EntryStatus:  string implements  HasLabel, HasColor, HasIcon

{
    case COMPLETE = 'complete';
    case INCOMPLETE = 'incomplete';
    case SOLVED = 'solved';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::COMPLETE => 'Complete',
            self::INCOMPLETE => 'Incomplete',
            self::SOLVED => 'Solved'
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::COMPLETE => 'success',
            self::SOLVED => 'warning',
            self::INCOMPLETE => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::COMPLETE => 'heroicon-m-check-circle',
            self::INCOMPLETE => 'heroicon-m-x-circle',
            self::SOLVED => 'heroicon-m-archive-box',
        };
    }
}


