<?php

use App\Enums\EvaluationType;
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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->foreignId('evaluator_volunteer_id')->constrained('volunteers')->cascadeOnDelete();
            $table->foreignId('evaluated_volunteer_id')->nullable()->constrained('volunteers')->nullOnDelete();
            $table->string('type')->default(EvaluationType::INDIVIDUAL->value)->index();
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
