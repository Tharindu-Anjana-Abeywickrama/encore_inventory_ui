<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_path'); // path to image or file
            $table->string('file_type')->nullable(); // e.g., image/png
            $table->string('file_name')->nullable();
            
            // Polymorphic relationship fields
            $table->unsignedBigInteger('fileable_id');
            $table->string('fileable_type');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
