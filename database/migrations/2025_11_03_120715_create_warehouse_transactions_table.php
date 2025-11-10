<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Item;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Item::class)->constrained();
            $table->tinyInteger('transaction_type'); // enum: [initial, addition, removal, adjustment]
            $table->decimal('quantity', 10,2);
            $table->decimal('quantity_after', 10,2);
            $table->nullableMorphs('reference');
            $table->text('description');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_transactions');
    }
};
