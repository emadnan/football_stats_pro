<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListBuilderRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_builder_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('list_builder_query_id');
            $table->string('dropdown1_id');
            $table->string('operator_id');
            $table->integer('input_value');
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
        Schema::dropIfExists('list_builder_rules');
    }
}
