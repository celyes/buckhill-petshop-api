<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('remember_token');
            $table->string('uuid')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();
            $table->string('address');
            $table->string('phone_number');
            $table->boolean('is_marketing')->default(false);
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
