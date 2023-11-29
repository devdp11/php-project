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
        Schema::table('sharing_expenses', function (Blueprint $table) {
            $table->foreign(['users_id'], 'sharing_expenses_ibfk_2')->references(['id'])->on('users');
            $table->foreign(['expense_id'], 'sharing_expenses_ibfk_1')->references(['id'])->on('expenses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sharing_expenses', function (Blueprint $table) {
            $table->dropForeign('sharing_expenses_ibfk_2');
            $table->dropForeign('sharing_expenses_ibfk_1');
        });
    }
};
