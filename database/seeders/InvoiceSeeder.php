<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductInvoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {
            $invoice = Invoice::factory()->create([
                'customer_id' => $customer->id,
            ]);

            $products = Product::all()->random(rand(1, 5));

            $totalAmount = 0;

            foreach ($products as $product) {
                $quantity = rand(1, 5);
                $amount = $product->selling_price * $quantity;

                $totalAmount += $amount;

                ProductInvoice::create([
                    'product_id' => $product->id,
                    'invoice_id' => $invoice->id,
                    'sold_quantity' => $quantity,
                    'sold_amount' => $amount,
                ]);
            }

            $invoice->update(['amount' => $totalAmount]);
        }
    }
}
