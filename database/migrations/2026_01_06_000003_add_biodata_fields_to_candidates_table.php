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
        Schema::table('candidates', function (Blueprint $table) {
            // Bio data fields
            $table->string('national_id')->nullable()->after('date_of_birth');
            $table->string('kra_pin')->nullable()->after('national_id');
            $table->string('nssf_number')->nullable()->after('kra_pin');
            $table->string('nhif_number')->nullable()->after('nssf_number');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->after('nhif_number');
            $table->string('emergency_contact_name')->nullable()->after('marital_status');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            $table->text('medical_conditions')->nullable()->after('emergency_contact_relationship');
            $table->text('allergies')->nullable()->after('medical_conditions');
            $table->string('blood_group')->nullable()->after('allergies');
            $table->text('next_of_kin_name')->nullable()->after('blood_group');
            $table->string('next_of_kin_phone')->nullable()->after('next_of_kin_name');
            $table->string('next_of_kin_relationship')->nullable()->after('next_of_kin_phone');
            $table->text('next_of_kin_address')->nullable()->after('next_of_kin_relationship');
            
            // Bio data completion status
            $table->boolean('biodata_completed')->default(false)->after('next_of_kin_address');
            $table->timestamp('biodata_completed_at')->nullable()->after('biodata_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'national_id',
                'kra_pin',
                'nssf_number',
                'nhif_number',
                'marital_status',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
                'medical_conditions',
                'allergies',
                'blood_group',
                'next_of_kin_name',
                'next_of_kin_phone',
                'next_of_kin_relationship',
                'next_of_kin_address',
                'biodata_completed',
                'biodata_completed_at',
            ]);
        });
    }
};
