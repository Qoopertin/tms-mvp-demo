<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loads', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 50)->unique();
            
            // Pickup information
            $table->string('pickup_address');
            $table->decimal('pickup_lat', 10, 7)->nullable();
            $table->decimal('pickup_lng', 10, 7)->nullable();
            
            // Delivery information
            $table->string('delivery_address');
            $table->decimal('delivery_lat', 10, 7)->nullable();
            $table->decimal('delivery_lng', 10, 7)->nullable();
            
            // Status and assignment
            $table->enum('status', ['created', 'assigned', 'in_transit', 'delivered'])->default('created');
            $table->foreignId('assigned_driver_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            
            $table->index('status');
            $table->index('assigned_driver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loads');
    }
};
