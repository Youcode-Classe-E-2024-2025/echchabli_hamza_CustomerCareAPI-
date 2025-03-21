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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->enum('progress' , ['inprogress' , 'done'])->default('inprogress');
            $table->integer('confirmed')->default(0);
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
