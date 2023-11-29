<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('description');
            $table->date('date');
            $table->decimal('amount', 10);
            $table->boolean('paid');
            $table->string('note')->nullable();
            $table->integer('user_id')->nullable()->index('user_id');
            $table->integer('category_id')->nullable()->index('category_id');
            $table->integer('payment_id')->nullable();
            $table->integer('attachment_id')->nullable()->index('attachment_id');
            $table->integer('method_id')->nullable()->index('method_id');
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
};
