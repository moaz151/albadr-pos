<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration {

	public function up()
	{
		Schema::create('files', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->string('usage');
			$table->string('path');
			$table->string('ext');
			$table->nullableMorphs('fileable');
		});
	}

	public function down()
	{
		Schema::drop('files');
	}
}