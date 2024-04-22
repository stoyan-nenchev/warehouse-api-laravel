<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function products() {
        return $this->belongsToMany(Product::class)->using(ProductInvoice::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
