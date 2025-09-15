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
        /**
            * Transaction Lines Table :
            * id              (PK)
            * transaction_id  (FK -> transactions.id)
            * account_id      (FK -> accounts.id)
            * type            (enum: 'debit','credit')
            * amount          (decimal 15,2)
            * created_at
            * updated_at
        */
        Schema::create('transaction_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->index();
            $table->unsignedBigInteger('account_id')->index();
            $table->enum('type',['debit','credit']);
            $table->decimal('amount',15,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_lines');
    }
};
