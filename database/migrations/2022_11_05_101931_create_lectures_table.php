<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('lectures', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('desc');
      $table->string('code');
      $table->string('color');
      $table->string('notification_key')->nullable();
      $table->timestamps();

      $table->unique('code');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('lectures');
  }
};
