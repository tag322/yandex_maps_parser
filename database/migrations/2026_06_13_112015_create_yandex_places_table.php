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
        Schema::create('yandex_places', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('yandex_oid')->unique(); // индекс, ибо фильтрация только по этому полю и expires_at (нет смысла вешать составной индекс, ибо запись с таким oid одна)

            $table->string('title');

            $table->decimal('rating', 3, 2)->nullable(); 

            $table->unsignedInteger('reviews_count')->default(0);
            $table->unsignedInteger('ratings_count')->default(0);

            $table->timestamp('expires_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yandex_places');
    }
};
