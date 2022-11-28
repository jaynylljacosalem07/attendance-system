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
        //
        Schema::table('attendance_sheets', function (Blueprint $table) {
            //
            $table->text('in')->default([])->change();
            $table->text('out')->default([])->change();
            // $table->json('course_id')->default([])->change();
            // $table->json('year_level')->default([])->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
