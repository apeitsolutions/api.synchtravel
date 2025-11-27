<?php

namespace App\Models\expense;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenseCategory extends Model
{
    use HasFactory;

    public function categoryExpense()
    {
        return $this->hasMany(\App\Models\expense\expense::class,'category_id');
    }
}
