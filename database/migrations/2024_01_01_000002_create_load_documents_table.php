<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('load_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('load_id')->constrained('loads')->cascadeOnDelete();
            $table->enum('type', ['POD', 'PHOTO', 'OTHER'])->default('OTHER');
            $table->string('filename');
            $table->string('path');
            $table->string('mime_type', 100);
            $table->unsignedInteger('size'); // bytes
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            $table->index('load_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('load_documents');
    }
};
