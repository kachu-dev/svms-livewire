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
            $table->string('classification')->index();
            /* $table->unsignedInteger('count')->default(1); */

            $table->unsignedBigInteger('original_violation_type_id')->nullable()->index();
            $table->foreignId('violation_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('violation_type_snapshot');

            $table->foreignId('violation_remark_id')->nullable()->constrained()->nullOnDelete();
            $table->string('violation_remark_snapshot')->nullable();

            $table->softDeletes();
            $table->timestamps();

            /*            $table->index(['student_id', 'original_violation_type_id', 'created_at']); */
            $table->index(['student_id', 'classification']);
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
