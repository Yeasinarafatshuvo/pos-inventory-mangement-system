<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;

    protected $fillable = [
        'provident_fund',
        'delay',
        'absence',
        'attendance_year',
        'attendance_month',
        'paymentMethod',
        'check_details',
        'loan_adjust',
        'mobile_allowance',
        'lunch_allowance',
        'festibal_allowance',
        'other_allowance',
        'automated_user_id',
        'total_earnings_value',
        'total_deduction',
        'total_net_pay',
    ];
}
