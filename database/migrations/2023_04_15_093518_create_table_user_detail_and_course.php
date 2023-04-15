<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserDetailAndCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('job')->nullable();
            $table->string('role')->nullable();
            $table->float('salary')->nullable();
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });

        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->text('content')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });

        Schema::create('thread_replies', function (Blueprint $table) {
            $table->id();
            $table->integer('thread_id');
            $table->text('content')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });

        Schema::create('user_courses', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('threads');
        Schema::dropIfExists('user_courses');
        Schema::dropIfExists('thread_replies');
    }
}
