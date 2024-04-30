<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Journal extends Pivot
{
    use HasFactory;
    protected $fillable = ['client_id', 'entry_date', 'content'];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
