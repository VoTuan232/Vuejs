<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildParentCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_parent_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('child_id')->nullable()->unsigned();
            $table->integer('parent_id')->nullable()->unsigned();
            $table->timestamps();
        });

        Schema::table('child_parent_category', function ($table) {
            $table->foreign('child_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('child_parent_category', function ($table) {
            $table->dropForeign(['child_id']);
            $table->dropForeign(['parent_id']);
        });
        
        Schema::dropIfExists('child_parent_category');
    }
}
