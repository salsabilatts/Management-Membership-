<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('legal_aids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('case_number')->unique();
            $table->string('case_title');
            $table->text('case_description');
            $table->enum('case_type', ['pidana', 'perdata', 'tata_usaha', 'lainnya']);
            $table->date('submission_date');
            $table->enum('status', ['pending', 'in_process', 'completed', 'rejected'])->default('pending');
            $table->foreignId('institution_id')->nullable()->constrained();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->date('verification_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('legal_aids');
    }
};