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
    public function up(): void
    {
        Schema::create('media_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 25)->unique();
            $table->json('mime_type');
            $table->string('disk')->default('local');
            $table->timestamps();
        });

        Schema::create('media_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_name');
            $table->string('path');
            $table->bigInteger('size');
            $table->timestamps();

            $table->unsignedBigInteger('media_type_id');
            $table->foreign('media_type_id')
                ->references('id')
                ->on('media_types')
                ->onDelete('cascade');

            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('media_types');
        Schema::dropIfExists('media_files');
//        Schema::dropIfExists('model_has_medias');
    }
};
