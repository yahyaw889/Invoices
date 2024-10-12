<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class Invoices extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected  $fillable = [
        'invoice_number' ,
        'invoice_date' ,
        'due_date' ,
        'note' ,
        'total',
        'amount_collection' ,
        'amount_commission' ,
        'discount' ,
        'value_vat' ,
        'rate_vat' ,
        'status_id' ,
        'section_id' ,
        'product_id' ,
        'payment_date'
    ];
    protected  $casts = [
        'invoice_number',
        'invoice_date' => 'date:Y-m-d H:i:s',
        'due_date' => 'date:Y-m-d H:i:s',
        'payment_date' => 'date:Y-m-d H:i:s',
        'status' => 'boolean',
        'note' => 'string',
        'discount' => 'integer',
    ];
    public function validateInvoice(): array
    {
        // القواعد الخاصة بالتحقق
        return [
            'invoice_number' => 'required',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'section' => 'required|exists:sections,id',
            'product' => 'required|exists:products,id',
            'amount_collection' => 'required|numeric|min:0.01',
            'amount_commission' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'rate_vat' => 'required|min:0|max:100',
            'value_vat' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ];


    }
    public function validationMessages(): array
    {
        return [
            'invoice_number.required' => 'يجب ادخال رقم الفاتورة',
            'invoice_date.required' => 'يجب ادخال تاريخ الفاتورة',
            'due_date.required' => 'يجب ادخال تاريخ الاستحقاق',
            'section.required' => 'يجب ادخال القسم',
            'product.required' => 'يجب ادخال المنتج',
            'amount_collection.required' => 'يجب ادخال المبلغ المتلقى',
            'amount_collection.numeric' => 'يجب ان يكون المبلغ المتلقى عدد',
            'amount_collection.min' => 'يجب أن يكون الرقم موجب',
            'amount_commission.required' => 'يجب ادخال المبلغ المتلقى',
            'amount_commission.numeric' => 'يجب ان يكون المبلغ المتلقى عدد',
            'amount_commission.min' => 'يجب أن يكون الرقم موجب',
            'discount.numeric' => 'يجب ان يكون المبلغ المتلقى عدد',
            'discount.min' => 'يجب أن يكون الرقم موجب',
            'value_vat.min' => 'يجب أن يكون الرقم موجب',
            'total.min' => 'يجب أن يكون الرقم موجب',
            'note.string' => 'يجب ان يكون نص'


            ];
    }

    public function getTotal($amount_commission ,$discount  , $rate_vat ): float|int
    {
       return ( $amount_commission - $discount ) + $this->get_vat($amount_commission ,$discount  , $rate_vat );
    }
    public function total(float $getTotal ,float $amount_collection ): float
    {
        return $getTotal + $amount_collection;
    }
    public function get_vat($amount_commission ,$discount  , $rate_vat ): float|int
    {
        $rate =  str_replace('%', '', $rate_vat);
        return ( $amount_commission - $discount ) * ($rate / 100 );
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Status::class );
    }

    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class,'section_id');
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function attachment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoices::class , 'invoice_id');
    }

    public function detail(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoices::class , 'invoice_id');
    }



}
