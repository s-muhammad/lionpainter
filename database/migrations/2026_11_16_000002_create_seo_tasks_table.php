<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['tracking', 'technical', 'on_page', 'content', 'local', 'links', 'other']);
            $table->unsignedTinyInteger('priority')->default(2);
            $table->enum('status', ['todo', 'doing', 'done'])->default('todo')->index();
            $table->string('phase')->nullable()->index();
            $table->date('due_date')->nullable()->index();
            $table->foreignId('keyword_id')
                ->nullable()
                ->constrained('seo_keywords')
                ->nullOnDelete();
            $table->text('notes')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_tasks');
    }
};
