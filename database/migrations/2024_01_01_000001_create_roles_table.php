<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default roles
        DB::table('roles')->insert([
            ['name' => 'admin', 'description' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'teacher', 'description' => 'Teacher/Instructor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'student', 'description' => 'Student', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}