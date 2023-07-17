<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailPanel extends Model
{
    use HasFactory;

    protected $table = 'email_panels';

    protected $fillable = ['customer_name','customer_email','customer_phone'];
}
