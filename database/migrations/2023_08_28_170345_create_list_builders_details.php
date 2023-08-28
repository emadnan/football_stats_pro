<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListBuildersDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_builders_details', function (Blueprint $table) {
            $table->id();
            $table->integer('list_id');
            $table->integer('dropdown1_id');
            $table->integer('operator_id');
            $table->integer('input_value');
            $table->tinyInteger('is_AND');
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
        Schema::dropIfExists('list_builders_details');
    }
}
