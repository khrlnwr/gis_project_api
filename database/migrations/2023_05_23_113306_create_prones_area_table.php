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
        Schema::create('prones_area', function (Blueprint $table) {
            $table->id();
            $table->integer('id_type');
            $table->integer('id_province');
            $table->integer('id_city');
            $table->string('name');
            $table->double('lat');
            $table->double('long');            
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
        Schema::dropIfExists('prones_area');
    }
};
