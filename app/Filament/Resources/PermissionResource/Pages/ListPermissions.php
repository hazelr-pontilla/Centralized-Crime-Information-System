<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected ?string $subheading = 'NOTE: This section is intended for authorized users only.
    Unauthorized access may result in data loss and compromised security. Feel free to customize this
    section to your preferences.';
}
