<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('section_id');
            $table->string('name');
            $table->string('file_path');
            $table->string('access_code');
            $table->timestamps();
        });

        Schema::table('files', function (Blueprint $table) {
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->unique(['name','file_path','section_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
