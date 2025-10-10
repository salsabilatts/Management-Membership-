<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('health_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('event_time');
            $table->text('location');
            $table->integer('quota')->default(0);
            $table->integer('registered_count')->default(0);
            $table->enum('status', ['open', 'closed', 'completed', 'cancelled'])->default('open');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('health_event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('health_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['registered', 'attended', 'absent'])->default('registered');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_event_participants');
        Schema::dropIfExists('health_events');
    }
};