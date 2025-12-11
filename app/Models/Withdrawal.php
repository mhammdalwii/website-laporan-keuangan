<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'status',
        'reference_no',
        'beneficiary_name',
        'beneficiary_account',
        'beneficiary_bank',
    ];
}
