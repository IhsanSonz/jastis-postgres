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
    // Lecture many to many User
    Schema::table('lectures_users', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    // Lecture many to many User
    Schema::table('lectures', function (Blueprint $table) {
      $table->dropForeign('user_id');
      $table->dropForeign('lecture_id');
    });
  }
};
