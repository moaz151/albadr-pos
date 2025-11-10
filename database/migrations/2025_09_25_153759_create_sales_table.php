<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Warehouse;

class CreateSalesTable extends Migration {

	public function up()
	{
		Schema::create('sales', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->bigInteger('client_id')->unsigned();
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('safe_id')->unsigned();
			$table->decimal('total', 10,2)->default(0);
			$table->decimal('discount', 10,2)->default(0)->nullable();
			$table->tinyInteger('discount_type');
			$table->decimal('shipping_cost', 10,2)->default(0);
			$table->decimal('net_amount', 10,2)->default(0);
			$table->decimal('paid_amount', 10,2)->default(0);
			$table->decimal('remaining_amount', 10,2)->default(0);
			$table->string('invoice_number');
			$table->foreignIdFor(Warehouse::class, 'warehouse_id');
			$table->tinyInteger('payment_type');
		});
	}

	public function down()
	{
		Schema::drop('sales');
	}
}