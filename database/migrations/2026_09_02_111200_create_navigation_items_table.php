<?php

use App\Enums\NavigationLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-managed header/footer navigation. Flat by decision — no parent_id
 * (BUILD-PLAN §4, spec §22).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();

            $table->string('label');
            $table->string('url');
            $table->enum('location', NavigationLocation::values())
                ->default(NavigationLocation::Header->value);
            $table->boolean('target_blank')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->index(['location', 'is_visible', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_items');
    }
};
