<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('emoney_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('card_number', 16)->unique();
            $table->decimal('balance', 15, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->date('issued_date');
            $table->date('expired_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('emoney_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emoney_card_id')->constrained()->onDelete('cascade');
            $table->string('transaction_number')->unique();
            $table->enum('transaction_type', ['topup', 'payment', 'transfer', 'withdrawal']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('success');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('emoney_transactions');
        Schema::dropIfExists('emoney_cards');
    }
};