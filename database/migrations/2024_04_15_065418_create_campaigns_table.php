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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titre de la campagne
            $table->text('description')->nullable(); // Description de la campagne (optionnelle)
            $table->dateTime('start_date'); // Date de début de la campagne
            $table->dateTime('end_date'); // Date de fin de la campagne
            $table->unsignedBigInteger('segment_id'); // Segment cible de la campagne
             $table->foreign('segment_id')->references('id')->on('segmentations')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
