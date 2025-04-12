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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->morphs('gradable');

            $table->foreignId('subject_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('class_room_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('period_id')->constrained()->cascadeOnDelete();
            $table->date('date')->nullable();
        
            $table->decimal('score', 5, 2);
            $table->string('grade')->nullable();
            $table->longText('note')->nullable();
        
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
