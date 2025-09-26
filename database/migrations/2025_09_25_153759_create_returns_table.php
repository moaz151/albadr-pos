<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsTable extends Migration {

	public function up()
	{
		Schema::create('returns', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->bigInteger('sale_id')->unsigned();
			$table->bigInteger('safe_id')->unsigned();
			$table->bigInteger('user_id')->unsigned();
			$table->string('return_number')->nullable();
			$table->decimal('return_amount', 10,2);
			$table->text('reason')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('returns');
	}
}