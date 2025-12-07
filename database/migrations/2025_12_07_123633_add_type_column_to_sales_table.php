<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\SaleTypeEnum;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('returns');
        Schema::table('sales', function (Blueprint $table) {
            $table->tinyInteger('type');
        });
        DB::table('sales')->update(['type' => SaleTypeEnum::sale->value]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::create('returns', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('sale_id')->unsigned();
            $table->bigInteger('safe_id')->unsigned();
            $table->bigInteger('user_Id')->unsigned();
            $table->string('return_number')->nullable();
            $table->decimal('return_amount', 10,2);
            $table->text('reason')->nullable();
        });
    }
};
