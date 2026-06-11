<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'payer_id',
        'description',
        'amount',
        'date'
    ];

    protected function casts(): array
    {
        return [
            'group_id' => 'integer',
            'payer_id' => 'integer',
            'amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public function splits()
    {
        return $this->hasMany(BillSplit::class);
    }
}
