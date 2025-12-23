<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_breadcrumbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('load_id')->constrained('loads')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->timestamp('captured_at');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['load_id', 'user_id']);
            $table->index('captured_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_breadcrumbs');
    }
};
