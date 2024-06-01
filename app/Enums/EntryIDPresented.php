<?php

namespace App\Enums;

enum EntryIDPresented : string {
    case NATIONAL = 'National ID';
    case DRIVER = 'Drivers License';
    case PHILHEALTH = 'PhilHealth ID';
    case SENIOR = 'Senior Citizen ID';
    case PASSPORT = 'Passport ID';
    case POSTAL = 'Postal ID';
    case UMID = 'UMID ID';
    case PWD = 'PWD ID';
    case STUDENT = 'Student ID';
    case PRC = 'PRC ID';
    case NA = 'N/A';
}
