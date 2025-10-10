<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('social_activities', function (Blueprint $table) {
            $table->id();
            $table->string('activity_name');
            $table->enum('activity_type', ['bencana', 'keagamaan', 'sosial', 'lainnya']);
            $table->text('description')->nullable();
            $table->date('activity_date');
            $table->text('location');
            $table->decimal('fund', 15, 2)->default(0);
            $table->enum('status', ['planned', 'ongoing', 'completed', 'cancelled'])->default('planned');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Schema::create('social_activity_beneficiaries', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('social_activity_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
        //     $table->string('beneficiary_name');
        //     $table->text('aid_received')->nullable();
        //     $table->decimal('aid_value', 12, 2)->default(0);
        //     $table->timestamps();
        // });
    }

    public function down()
    {
        Schema::dropIfExists('social_activity_beneficiaries');
        Schema::dropIfExists('social_activities');
    }
};