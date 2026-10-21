<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name', 100);
            $table->text('description');
            $table->unsignedInteger('price');
            $table->string('photo')->nullable();
            $table->string('tag', 30)->nullable();
            $table->boolean('is_available')->default(true);
            // warna ilustrasi cangkir di kartu menu (dipakai bila tidak ada foto)
            $table->string('color_light', 7)->default('#D9C2A3');
            $table->string('color_dark', 7)->default('#5A321A');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
