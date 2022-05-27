<?php

namespace App\Models;

use App\Casts\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'storages';
    protected $guarded = [];
    public $timestamp = false;
    protected $casts = [
        'created_at' => FormatDate::class
    ];

    public function detail_storage()
    {
        return $this->hasMany(DetailStorage::class, 'storage_id');
    }
}
