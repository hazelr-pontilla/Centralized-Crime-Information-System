<?php

namespace App\Enums;

enum EntryEducational:string {
    case DOCTORAL = 'Doctoral Degree';
    case MASTERS = 'Masters Degree';
    case BACHELOR = 'Bachelors Degree';
    case VOCATIONAL = 'Vocational or Technical Certifications';
    case HIGHSCHOOL = 'High School Diploma';
    case ELEMENTARY = 'Elementary Education';
    case NO_FORMAL = 'No Formal Education';
}
