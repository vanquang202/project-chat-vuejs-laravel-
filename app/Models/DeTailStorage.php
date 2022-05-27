<?php

namespace App\Models;

use App\Casts\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeTailStorage extends Model
{
    use HasFactory;

    protected $table = 'details_storage';
    protected $guarded = [];
    public $timestamp = false;
    protected $casts = [
        'created_at' => FormatDate::class
    ];

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }
}
