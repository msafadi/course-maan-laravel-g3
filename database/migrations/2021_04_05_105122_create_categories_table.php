<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            // "id" UNSIGNED BIGINT AUTO INCREMENT Primary Key
            $table->id();
            // "name" VARCHAR -- MAX: 255 char.
            $table->string('name');
            $table->string('slug')->unique();
            // "parent_id" BIGINT UNSIGNED NULL
            $table->unsignedBigInteger('parent_id')->nullable();
            /*
            "created_at" timestamp (datetime)
            "updated_at" timestamp
            */
            $table->timestamps();

            $table->foreign('parent_id')->references('id')
                ->on('categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
