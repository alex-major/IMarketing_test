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
        Schema::create('item_characteristics', function (Blueprint $table) {
            $table->id();
			$table->foreignId('item_id')->index();
			$table->decimal('length', total: 3, places: 2);
			$table->decimal('width', total: 3, places: 2);
			$table->decimal('weight', total: 3, places: 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_characteristics');
    }
};
