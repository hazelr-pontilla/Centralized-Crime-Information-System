<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Children extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

//        'entryID',

        //CHILDREN IN CONFLICT MODEL
        'name',
        'address',
        'mobile_phone',
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
