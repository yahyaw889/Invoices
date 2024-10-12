<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name' , 'description' , 'section_id'];

    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoices::class );
    }
}
