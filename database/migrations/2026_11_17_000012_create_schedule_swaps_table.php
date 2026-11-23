<?php

use App\Enums\SwapStatus;
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
        Schema::create('schedule_swaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_volunteer_id')->constrained('volunteers')->cascadeOnDelete();
            $table->foreignId('target_volunteer_id')->constrained('volunteers')->cascadeOnDelete();
            $table->foreignId('requester_event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('target_event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->string('status')->default(SwapStatus::PENDING->value)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_swaps');
    }
};
