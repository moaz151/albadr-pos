<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration {

	public function up()
	{
		Schema::create('order_items', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->bigInteger('order_id')->unsigned();
			$table->bigInteger('item_id')->unsigned();
			$table->decimal('unit_price', 10,2);
			$table->decimal('quantity', 10,2);
			$table->decimal('total_price', 10,2);
		});
	}

	public function down()
	{
		Schema::drop('order_items');
	}
}