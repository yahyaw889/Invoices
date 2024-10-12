<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id' ,
        'total_payment' ,
        'status_id' ,
        'payment_date' ,
        'payment_method',
        'user_id' ,
        'payment_note' ,
        'remaining_payment',
        'total_amount'
        ];

    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoices::class, 'invoice_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class,'status');
    }
}
