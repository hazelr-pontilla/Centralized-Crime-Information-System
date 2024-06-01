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
        Schema::create('childrens', function (Blueprint $table) {
            $table->id();

//            $table->foreignId('entryID')
//                ->constrained('entries')
//                ->cascadeOnDelete();

            //FOR CHILDREN IN CONFLICT
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('mobile_phone')->nullable();

            $table->longText('remarks')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('childrens');
    }
};
