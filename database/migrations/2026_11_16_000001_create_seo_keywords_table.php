<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->string('cluster')->nullable()->index();
            $table->enum('intent', ['informational', 'commercial', 'transactional', 'local']);
            $table->enum('content_type', ['home', 'service_page', 'location_page', 'blog_post', 'portfolio', 'other']);
            $table->string('target_url')->nullable();
            $table->unsignedTinyInteger('priority')->default(2);
            $table->enum('status', ['idea', 'planned', 'in_progress', 'published', 'needs_update', 'skipped'])
                ->default('idea')
                ->index();
            $table->enum('difficulty', ['low', 'medium', 'high'])->nullable();
            $table->unsignedInteger('search_volume')->nullable();
            $table->unsignedSmallInteger('current_position')->nullable();
            $table->date('target_date')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_keywords');
    }
};
