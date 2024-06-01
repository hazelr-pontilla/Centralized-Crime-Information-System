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
        Schema::create('suspects', function (Blueprint $table) {
            $table->id();

//            $table->foreignId('entryID')
//                ->constrained('entries')
//                ->cascadeOnDelete();

            $table->string('s_fm')->nullable();
            $table->string('s_fn')->nullable();
            $table->string('s_mn')->nullable();
            $table->string('s_q')->nullable();
            $table->string('s_n')->nullable();

            $table->string('s_citizen')->nullable();
            $table->string('s_gender')->nullable();
            $table->string('s_civil')->nullable();
            $table->date('s_dob')->nullable();
            $table->string('s_age')->nullable();
            $table->string('s_pob')->nullable();
            $table->string('s_mp')->nullable();

            $table->string('s_current')->nullable();
            $table->string('s_village')->nullable();
            $table->string('s_barangay')->nullable();
            $table->string('s_town')->nullable();
            $table->string('s_province')->nullable();

            $table->string('s_other')->nullable();
            $table->string('s_villagee')->nullable();
            $table->string('s_barangayy')->nullable();
            $table->string('s_townn')->nullable();
            $table->string('s_provincee')->nullable();

            $table->string('s_highest')->nullable();
            $table->string('s_occupation')->nullable();
            $table->string('s_work')->nullable();
            $table->string('s_relation')->nullable();
            $table->string('s_email')->nullable();

            //SUSPECTS IDENTITY
            $table->string('afp_personnel')->nullable();
            $table->string('unit')->nullable();
            $table->string('group')->nullable();
            $table->string('previous_record')->nullable();

            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('built')->nullable();

            $table->string('color_eyes')->nullable();
            $table->string('description_eyes')->nullable();
            $table->string('color_hair')->nullable();
            $table->string('description_hair')->nullable();
            $table->string('under')->nullable();

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
        Schema::dropIfExists('suspects');
    }
};
