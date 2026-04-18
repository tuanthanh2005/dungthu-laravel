<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \App\Models\AffiliateInvoice::where('bill_image', 'like', 'images/bills/%')
            ->get()
            ->each(function ($invoice) {
                $invoice->update([
                    'bill_image' => str_replace('images/bills/', 'uploads/affiliates/', $invoice->bill_image)
                ]);
            });
    }

    public function down(): void
    {
        \App\Models\AffiliateInvoice::where('bill_image', 'like', 'uploads/affiliates/%')
            ->get()
            ->each(function ($invoice) {
                $invoice->update([
                    'bill_image' => str_replace('uploads/affiliates/', 'images/bills/', $invoice->bill_image)
                ]);
            });
    }
};
