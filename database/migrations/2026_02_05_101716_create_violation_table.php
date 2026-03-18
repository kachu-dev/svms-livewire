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
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->string('st_first_name');
            $table->string('st_last_name');
            $table->string('st_mi')->nullable();
            $table->string('st_program');
            $table->string('st_year');

            $table->enum('classification', [
                'Minor',
                'Major - Suspension',
                'Major - Dismissal',
                'Major - Expulsion',
            ])->index();

            $table->string('type_code');
            $table->string('type_name');
            $table->string('remark')->nullable();

            $table->boolean('is_escalated')->default(false)->index();

            $table->string('status')->default('pending');

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['student_id', 'classification']);
            $table->index(['type_code', 'type_name']);
            $table->index('created_at');
            $table->index(['status', 'classification', 'deleted_at']);
            $table->index(['classification', 'student_id', 'created_at', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
