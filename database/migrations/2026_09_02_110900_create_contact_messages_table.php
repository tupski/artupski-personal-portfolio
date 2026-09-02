<?php

use App\Enums\ContactMessageStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Contact submissions (BUILD-PLAN §4, spec §31). `message` is plain text, never HTML.
 * Raw IPs are not stored — only sha256(ip + APP_KEY) for abuse triage (§98).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');

            $table->string('company')->nullable();
            $table->string('budget', 100)->nullable();
            $table->string('project_type', 100)->nullable();

            $table->enum('status', ContactMessageStatus::values())
                ->default(ContactMessageStatus::Unread->value);

            $table->char('ip_hash', 64)->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
