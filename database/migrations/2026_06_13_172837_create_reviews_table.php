<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('yandex_oid');

            $table->foreign('yandex_oid')
                ->references('yandex_oid')
                ->on('yandex_places')
                ->cascadeOnDelete();

            $table->string('author')->nullable();

            $table->tinyInteger('rating')->nullable();

            $table->text('text');

            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['yandex_oid', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
