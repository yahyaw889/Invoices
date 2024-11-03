<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment_invoice extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_id', 'attachment' ,'name', 'type', 'views' , 'user_id'];
    protected $table = 'attachment_invoices';

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoices::class , 'invoice_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



}
