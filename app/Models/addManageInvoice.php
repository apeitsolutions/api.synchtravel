<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\otherPaxDetail;

class addManageInvoice extends Model
{
    use HasFactory;
    
    protected $table = 'add_manage_invoices';
    
    public function otherPax()
    {
        return $this->hasMany(otherPaxDetail::class, 'invoice_id');
    }

}
