<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowManagerIdToShowsTable extends Migration
{
    public function up()
    {
        Schema::table('shows', function (Blueprint $table) {
            // Adding the foreign key column
            $table->unsignedBigInteger('show_manager_id')->nullable();

            // Optional: If you want to make it a foreign key
            $table->foreign('show_manager_id')
                  ->references('id')->on('users')  // Assuming 'show_managers' is the table for show managers
                  ->onDelete('set null');  // Optionally set to null when the related show manager is deleted
        });
    }

    public function down()
    {
        Schema::table('shows', function (Blueprint $table) {
            // Dropping the foreign key and the column in case of rollback
            $table->dropForeign(['show_manager_id']);
            $table->dropColumn('show_manager_id');
        });
    }
}
