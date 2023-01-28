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
        Schema::create('medias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 25)->unique();
            $table->json('mime_type');
            $table->string('disk');
            $table->timestamps();
        });

        Schema::create('model_has_medias', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id');
            $table->foreign('media_id')
                ->references('id')
                ->on('medias')
                ->onDelete('cascade');

            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->primary(['media_id', 'model_type', 'model_id']);
        });

        Schema::create('media_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('storage');
            $table->string('url');
            $table->bigInteger('size');
            $table->timestamps();

            $table->unsignedBigInteger('media_id');
            $table->foreign('media_id')
                ->references('id')
                ->on('medias')
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
        Schema::dropIfExists('medias');
        Schema::dropIfExists('media_files');
        Schema::dropIfExists('model_has_medias');
    }
};
