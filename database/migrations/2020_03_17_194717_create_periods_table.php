<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('academic_year_id');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });

        Schema::table('periods', function (Blueprint $table) {
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->unique(['academic_year_id','start_date','end_date' ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periods');
    }
}
