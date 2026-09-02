<?php

use App\Enums\SettingType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Key/value site settings, grouped (BUILD-PLAN §4, spec §21).
 * `group` and `key` are reserved words in MariaDB, hence the quoted column names
 * Laravel's grammar emits — no manual escaping needed here.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            $table->string('group', 64);
            $table->string('key', 128);
            $table->text('value')->nullable();
            $table->enum('type', SettingType::values())->default(SettingType::String->value);

            $table->timestamps();

            $table->unique(['group', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
