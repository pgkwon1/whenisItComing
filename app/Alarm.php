<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    //
		protected $table = 'alarm';
}

public function up() {
	Schema::create('alarm', function (Blueprint $table) {
    $table->increments('id');
    $table->string('token', 20);
    $table->string('device', 30);
    $table->Integer('arsId', 100);
    $table->Integer('busRouteId', 250);
    $table->timestamps();
  });

}