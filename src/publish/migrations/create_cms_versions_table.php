<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsVersionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_name', 100);
            $table->text('description')->nullable();
            $table->string('status', 30)->default('Draft')->comment('Draft|Archived|Published');
            $table->dateTime('publish_at')->nullable();
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
        Schema::dropIfExists('cms_versions');
    }

}
