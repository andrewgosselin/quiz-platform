<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->string("session_id");
            $table->integer("quiz_id");
            $table->integer("user_id");
            $table->string("status")->default("in progress");
            $table->integer("current_question")->default(0);
            $table->json("answers")->nullable();
            $table->json("score")->nullable();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("email")->nullable();
            $table->string("phone_number")->nullable();
            $table->string("address_1")->nullable();
            $table->string("address_2")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
            $table->string("zip")->nullable();
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
        Schema::dropIfExists('sessions');
    }
}
