<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration {

	public function up()
	{
		Schema::create('units', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->string('name');
			$table->tinyInteger('status');
		});
	}

	public function down()
	{
		Schema::drop('units');
	}
}