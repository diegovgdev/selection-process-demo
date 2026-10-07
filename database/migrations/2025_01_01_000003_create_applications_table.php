<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selection_process_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('stage')->default('received')->index();
            $table->date('applied_at');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['selection_process_id', 'candidate_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
