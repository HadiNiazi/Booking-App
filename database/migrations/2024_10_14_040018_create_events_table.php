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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 255
            $table->text('description'); //65k
            $table->foreignId('category_id')->constrained();  // category_id
            $table->string('location');
            $table->string('type', 50); // free - paid
            $table->bigInteger('price')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->bigInteger('max_attendees')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
