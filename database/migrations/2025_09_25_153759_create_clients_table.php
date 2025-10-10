<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ClientRegistrationEnum;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->softDeletes();
			$table->string('name');
			$table->string('email')->unique();
			$table->string('phone')->unique();
			$table->string('address');
			$table->decimal('balance', 12,2);
			$table->tinyInteger('status');
			$table->tinyInteger('registered_via')->default(ClientRegistrationEnum::pos); // 1=web, 2=android, 3=ios
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}