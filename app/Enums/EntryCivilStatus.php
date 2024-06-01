<?php

namespace App\Enums;

enum EntryCivilStatus : string {

    case SINGLE = 'Single';
    case MARRIED = 'Married';
    case WIDOWED = 'Widowed';
    case SEPARATED = 'Separated';
}
