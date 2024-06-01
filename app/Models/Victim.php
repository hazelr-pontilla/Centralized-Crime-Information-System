<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Victim extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

//        'entryID',

        //VICTIMS DATA MODEL
        'v_fm',
        'v_fn',
        'v_mn',
        'v_q',
        'v_n',

        'v_citizen',
        'v_gender',
        'v_civil',
        'v_dob',
        'v_age',
        'v_pob',
        'v_mp',

        'v_current',
        'v_village',
        'v_barangay',
        'v_town',
        'v_province',

        'v_other',
        'v_villagee',
        'v_barangayy',
        'v_townn',
        'v_provincee',

        'v_highest',
        'v_occupation',
        'v_work',
        'v_email',

        'remarks'

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
