<?php

use App\Models\Poll;
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
        Schema::create('choises', function (Blueprint $table) {
            $table->id();
            $table->string('choise');
            $table->foreignIdFor(Poll::class, 'poll_id');

            $table->foreign('poll_id')->references('id')->on('polls');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choises');
    }
};
