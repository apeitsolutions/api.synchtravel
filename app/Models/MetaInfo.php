<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MetaInfo;

class MetaInfo extends Model
{
    use HasFactory;
    protected $fillables = [
        'meta_title',
        'keywords',
        'meta_desc',
    ];
    
    protected $table = 'meta_infos';
    
    public function hotels()
    {
        return $this->belongsTo(Hotels::class, 'id');
    }
}
