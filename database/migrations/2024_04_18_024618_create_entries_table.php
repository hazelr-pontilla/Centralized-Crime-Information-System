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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assigned_to')->constrained('users');
            $table->foreignId('assigned_by')->constrained('users');

            $table->string('entryID')->nullable()->unique();
            $table->string('type')->nullable();
            $table->string('copy')->nullable();
            $table->date('reported')->nullable();

            $table->string('reportedTime')->nullable(); //setting the time only
            $table->string('rclock')->nullable(); //setting the order clock

            $table->string('incidentTime')->nullable();
            $table->string('iclock')->nullable();

            $table->date('incident')->nullable();
            $table->string('place')->nullable();

            $table->string('status'); //added status of reporting person

            //CHECKLIST DOCUMENTS
            //IOC DOCUMENTARY
            $table->boolean('complain_affidavit')->default(false);
            $table->boolean('affidavit_witnesses')->default(false);

            //SCREENSHOT
            $table->boolean('transactions')->default(false);
            $table->boolean('conversations')->default(false);
            $table->boolean('post')->default(false);

            //DISPOSITION OF IOC
            $table->longText('note')->nullable();

            //CASE STATUS
            $table->string('case')->nullable();
            $table->longText('added_case')->nullable();

            //REPORTING PERSONS DATA
            $table->string('r_fm')->nullable();
            $table->string('r_fn')->nullable();
            $table->string('r_mn')->nullable();
            $table->string('r_q')->nullable();
            $table->string('r_n')->nullable();

            $table->string('r_citizen')->nullable();
            $table->string('r_gender')->nullable();
            $table->string('r_civil')->nullable();
            $table->date('r_dob')->nullable();
            $table->string('r_age')->nullable();
            $table->string('r_pob')->nullable();
            $table->string('r_mp')->nullable();

            $table->string('r_current')->nullable();
            $table->string('r_village')->nullable();
            $table->string('r_barangay')->nullable();
            $table->string('r_town')->nullable();
            $table->string('r_province')->nullable();

            $table->string('r_other')->nullable();
            $table->string('r_villagee')->nullable();
            $table->string('r_barangayy')->nullable();
            $table->string('r_townn')->nullable();
            $table->string('r_provincee')->nullable();

            $table->string('r_highest')->nullable();
            $table->string('r_occupation')->nullable();
            $table->string('r_id')->nullable();
            $table->string('r_email')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
