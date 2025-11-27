<?php

namespace App\Models\expense;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expense extends Model
{
    use HasFactory;

    public function expenseAccount()
    {
        return $this->belongsTo(\App\Models\Accounts\CashAccounts::class,'account_id');
    }

    public function expenseCategory()
    {
        return $this->belongsTo(\App\Models\expense\expenseCategory::class,'category_id');
    }

    public function expenseSubCategory()
    {
        return $this->belongsTo(\App\Models\expense\expenseSubCategory::class,'sub_category_id');
    }
}
