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
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreign(['category_id'], 'expenses_ibfk_2')->references(['id'])->on('expense_categories');
            $table->foreign(['attachment_id'], 'expenses_ibfk_4')->references(['id'])->on('attachments');
            $table->foreign(['user_id'], 'expenses_ibfk_1')->references(['id'])->on('users');
            $table->foreign(['method_id'], 'expenses_ibfk_3')->references(['id'])->on('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign('expenses_ibfk_2');
            $table->dropForeign('expenses_ibfk_4');
            $table->dropForeign('expenses_ibfk_1');
            $table->dropForeign('expenses_ibfk_3');
        });
    }
};
