<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();

            //Relations
            $table->foreignId('manager_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('conseiller_id')->constrained('users')->cascadeOnDelete();

            // Call info
            $table->enum('type', ['entrant', 'sortant']);
            $table->date('date');
            $table->string('reference')->nullable();

            //Audio 
            $table->string('audio')->nullable();

            // Evaluation result
            $table->integer('score')->nullable();
            $table->boolean('has_ko')->default(false);

            //  Status 
            $table->enum('status', ['draft', 'completed', 'signed'])
                  ->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};