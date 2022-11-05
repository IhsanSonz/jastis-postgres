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
    Schema::create('lecture_user', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('lecture_id');
      $table->unsignedBigInteger('user_id');
      $table->integer('level')->default(3)->comment("1 = admin\n2 = lecturer\n3 = student");
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('lectures_users');
  }
};
