<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\addManageInvoice;

class otherPaxDetail extends Model
{
    use HasFactory;
    
    protected $table = 'otherPaxDetails';
    
    public function invoice()
    {
        return $this->belongsTo(addManageInvoice::class, 'id');
    }

}
