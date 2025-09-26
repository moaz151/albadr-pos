<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingAddressesTable extends Migration {

	public function up()
	{
		Schema::create('shipping_addresses', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->bigInteger('order_id')->unsigned();
			$table->string('name');
			$table->string('email');
			$table->string('phone');
			$table->text('address');
		});
	}

	public function down()
	{
		Schema::drop('shipping_addresses');
	}
}