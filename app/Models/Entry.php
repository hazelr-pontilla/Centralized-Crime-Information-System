<?php

namespace App\Models;

use App\Enums\EntryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Entry extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'entryID',
        'type',
        'copy',
        'reported',

        'reportedTime',
        'rclock',

        'incidentTime',
        'iclock',

        'incident',

        'place',
        'status',

        'complain_affidavit',
        'affidavit_witnesses',
        'note', //added

        //newly added
        'case',
        'added_case',

        'transactions',
        'conversations',
        'post',

        'r_fm',
        'r_fn',
        'r_mn',
        'r_q',
        'r_n',

        'r_citizen',
        'r_gender',
        'r_civil',
        'r_dob',
        'r_age',
        'r_pob',
        'r_mp',

        'r_current',
        'r_village',
        'r_barangay',
        'r_town',
        'r_province',

        'r_other',
        'r_villagee',
        'r_barangayy',
        'r_townn',
        'r_provincee',

        'r_highest',
        'r_occupation',
        'r_id',
        'r_email',

        'attachment_front',
        'attachment_back',

        //USERS
        'assigned_to',
        'assigned_by',

    ];

    protected $casts=[
        'status'=>EntryStatus::class,
    ];

    public function suspect(): HasMany
    {
        return $this->hasMany(Suspect::class, 'entryID');
    }

    public function victim(): HasMany
    {
        return $this->hasMany(Victim::class, 'entryID');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Children::class,'entryID');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
