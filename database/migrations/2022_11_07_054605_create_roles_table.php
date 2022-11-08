<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('id')->constrained();
        });

        Role::insert([
            [
                'name' => 'user',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
};
