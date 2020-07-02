<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('type');
            $table->tinyInteger('state');
            $table->tinyInteger('gender')->default('0');
            $table->tinyInteger('gender_target')->default('0');
            $table->tinyInteger('single_state')->default('0');
            $table->integer('height')->default('0');
            $table->integer('weight')->default('0');
            $table->string('phone',20)->default('');
            $table->string('description',500)->default('');
            $table->date('birthday')->default('1990-01-01');
            $table->integer('job')->default(0);
            $table->integer('provincial')->default(0);
            $table->integer('district')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
