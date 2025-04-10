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
            $table->string('username')->unique()->after('id');
            $table->string('phone')->nullable()->after('username');
            $table->longText('address')->nullable()->after('phone');
            $table->string('postal_code')->nullable()->after('address');
            $table->string('profile_picture')->nullable()->after('postal_code');
            $table->string('dob')->nullable()->after('profile_picture');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('dob');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'username',
                'phone',
                'address',

                'postal_code',
                'profile_picture',
                'dob',
                'gender',
            ]);
        });
    }
};
