<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillSplit extends Model
{
    protected $fillable = ['bill_id', 'user_id', 'amount', 'is_paid'];

    protected $casts = [
        'bill_id' => 'integer',
        'user_id' => 'integer',
        'amount' => 'decimal:2',
        'is_paid' => 'boolean',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
