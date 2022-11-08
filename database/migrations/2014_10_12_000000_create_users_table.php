<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('role_no');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('location')->nullable();
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });
    }
};
