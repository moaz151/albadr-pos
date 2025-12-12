<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderStatusEnum;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->bigInteger('client_id')->unsigned();
			$table->string('order_number')->unique();
			$table->tinyInteger('status')->default(OrderStatusEnum::confirmed->value);
			$table->tinyInteger('payment_method');
			$table->decimal('price', 10,2);
			$table->decimal('shipping_cost', 10,2);
			$table->decimal('total_price', 10,2);
			$table->string('shipping_name');
			$table->text('shipping_address')->nullable();
			$table->string('shipping_phone')->nullable();
			$table->text('notes')->nullable();
			$table->bigInteger('sale_id')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}