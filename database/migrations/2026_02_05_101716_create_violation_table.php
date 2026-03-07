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
            $table->string('student_name');

            $table->foreignId('violation_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('violation_remark_id')->nullable()->constrained()->nullOnDelete();

            $table->unsignedBigInteger('original_violation_type_id')->nullable()->index();

            /*$table->string('classification')->index();
            $table->string('violation_type_snapshot');*/

            $table->string('violation_type_code_snapshot');
            $table->string('violation_type_name_snapshot');
            $table->string('violation_remark_snapshot')->nullable();
            $table->string('classification_snapshot')->index();

            $table->string('status')->default('pending');

            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['student_id', 'classification_snapshot']);
            $table->index('created_at');
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
