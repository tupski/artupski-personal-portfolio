<?php

use App\Enums\ContentStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Portfolio projects, authored as case studies (BUILD-PLAN §4, spec §11/§12).
 * Two status columns on purpose: `project_status` = delivery state, `status` = publishing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description', 500)->nullable();
            $table->longText('content')->nullable();
            $table->json('content_blocks')->nullable();

            $table->text('challenges')->nullable();
            $table->text('solutions')->nullable();
            $table->text('results')->nullable();

            $table->enum('project_type', ProjectType::values())
                ->default(ProjectType::Website->value)
                ->index();

            $table->string('client')->nullable();
            $table->string('role')->nullable();
            $table->date('started_on')->nullable();
            $table->date('ended_on')->nullable();

            $table->enum('project_status', ProjectStatus::values())
                ->default(ProjectStatus::Completed->value);

            $table->enum('status', ContentStatus::values())->default(ContentStatus::Draft->value);
            $table->timestamp('published_at')->nullable();

            $table->string('live_url')->nullable();
            $table->string('repository_url')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);

            $table->string('seo_title')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image_path')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['is_featured', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
