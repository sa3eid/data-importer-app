<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    protected $fillable = [
        'paidAmount',
        'Currency',
        'parentEmail',
        'statusCode',
        'paymentDate',
        'parentIdentification',
    ];

    public $timestamps = false;

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'parentEmail', 'email');
    }

}
