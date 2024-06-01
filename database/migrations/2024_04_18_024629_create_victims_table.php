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
        Schema::create('victims', function (Blueprint $table) {
            $table->id();

//            $table->foreignId('entryID')
//                ->constrained('entries')
//                ->cascadeOnDelete();

            //VICTIMS DATA
            $table->string('v_fm')->nullable();
            $table->string('v_fn')->nullable();
            $table->string('v_mn')->nullable();
            $table->string('v_q')->nullable();
            $table->string('v_n')->nullable();

            $table->string('v_citizen')->nullable();
            $table->string('v_gender')->nullable();
            $table->string('v_civil')->nullable();
            $table->date('v_dob')->nullable();
            $table->string('v_age')->nullable();
            $table->string('v_pob')->nullable();
            $table->string('v_mp')->nullable();

            $table->string('v_current')->nullable();
            $table->string('v_village')->nullable();
            $table->string('v_barangay')->nullable();
            $table->string('v_town')->nullable();
            $table->string('v_province')->nullable();

            $table->string('v_other')->nullable();
            $table->string('v_villagee')->nullable();
            $table->string('v_barangayy')->nullable();
            $table->string('v_townn')->nullable();
            $table->string('v_provincee')->nullable();

            $table->string('v_highest')->nullable();
            $table->string('v_occupation')->nullable();
            $table->string('v_work')->nullable();
            $table->string('v_email')->nullable();

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
        Schema::dropIfExists('victims');
    }
};
