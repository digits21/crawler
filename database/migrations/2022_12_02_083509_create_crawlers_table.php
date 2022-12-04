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
        Schema::create('crawlers', function (Blueprint $table) {
            $table->id();
            $table->string('url',255);
            $table->string('serial_number');
            // $table->string('unique_number')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->longText('body')->nullable();
            $table->integer('status')->default(0);
            $table->text('screenshot')->nullable();
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
        Schema::dropIfExists('crawlers');
    }
};
