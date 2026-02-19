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
        Schema::create('violation_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('order');
            $table->string('name');
            $table->boolean('active')->default(false);
            $table->text('remark')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['violation_id', 'order']);
        });
    }

    protected $casts = [
        'isComplete'   => 'boolean',
        'completed_at' => 'datetime',
    ];
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violation_stages');
    }
};
