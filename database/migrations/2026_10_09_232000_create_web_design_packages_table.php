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
        Schema::create('web_design_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('badge')->nullable();
            $table->string('badge_color')->default('primary');
            $table->unsignedBigInteger('price')->default(0);
            $table->json('features')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_design_packages');
    }
};
