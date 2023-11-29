<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id('expense_id');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('description');
            $table->foreignId('payment_id')->constrained('methods');
            $table->decimal('amount', 10, 2);
            $table->boolean('paid');
            $table->date('date');
            $table->string('note')->nullable();
            $table->foreignId('attachment_id')->constrained('attachments');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
