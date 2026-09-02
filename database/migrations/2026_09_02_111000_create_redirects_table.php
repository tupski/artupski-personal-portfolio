<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Slug-change and legacy-URL redirects, consumed by the HandleRedirects middleware
 * (BUILD-PLAN §4, spec §29).
 *
 * DEVIATION from BUILD-PLAN §4: `from_path` is varchar(191), not varchar(2048) with a
 * prefixed unique index. MariaDB/MySQL cannot build a plain UNIQUE index over 2048
 * utf8mb4 characters (3072-byte limit), and BUILD-PLAN §4 explicitly sanctions the
 * varchar(191) fallback. 191 chars is ample for a path with no host and no query.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();

            $table->string('from_path', 191)->unique();
            $table->string('to_path', 2048);
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('hits')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
