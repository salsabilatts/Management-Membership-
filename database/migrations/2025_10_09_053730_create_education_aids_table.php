<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('education_aids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('program_type', ['PIP', 'KIP', 'BIB']);
            $table->string('student_name');
            $table->string('school_name');
            $table->string('grade_level');
            $table->decimal('aid_amount', 12, 2);
            $table->string('academic_year');
            $table->enum('status', ['pending', 'approved', 'disbursed', 'rejected'])->default('pending');
            $table->date('submission_date');
            $table->date('approval_date')->nullable();
            $table->date('disbursement_date')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('education_aids');
    }
};