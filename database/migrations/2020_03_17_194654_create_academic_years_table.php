<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('study_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });

        Schema::table('academic_years', function (Blueprint $table) {
            $table->foreign('study_id')->references('id')->on('studies');
            $table->unique(['start_date','end_date', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_years');
    }
}
