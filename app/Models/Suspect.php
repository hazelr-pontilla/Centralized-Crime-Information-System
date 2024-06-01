<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suspect extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

//        'entryID',

        //SUSPECTS MODELS
        's_fm',
        's_fn',
        's_mn',
        's_q',
        's_n',

        's_citizen',
        's_gender',
        's_civil',
        's_dob',
        's_age',
        's_pob',
        's_mp',

        's_current',
        's_village',
        's_barangay',
        's_town',
        's_province',

        's_other',
        's_villagee',
        's_barangayy',
        's_townn',
        's_provincee',

        's_highest',
        's_occupation',
        's_work',
        's_relation',
        's_email',

        //SUSPECTS IDENTITY
        'afp_personnel',
        'unit',
        'group',
        'previous_record',
        'height',
        'weight',
        'built',
        'color_eyes',
        'description_eyes',
        'color_hair',
        'description_hair',
        'under',

        'remarks',

    ];

    public function entries():BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entryID', 'id');
    }
}
