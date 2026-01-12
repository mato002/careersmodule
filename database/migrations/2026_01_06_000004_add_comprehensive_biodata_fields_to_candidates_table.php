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
            // Employment & Personal Details
            $table->string('position')->nullable()->after('name');
            $table->string('nationality')->nullable()->after('national_id');
            $table->enum('sex', ['male', 'female', 'other'])->nullable()->after('nationality');
            $table->string('religion')->nullable()->after('sex');
            
            // Address Details
            $table->text('current_address')->nullable()->after('address');
            $table->string('home_county')->nullable()->after('current_address');
            $table->string('home_sub_county')->nullable()->after('home_county');
            $table->string('home_ward')->nullable()->after('home_sub_county');
            $table->string('home_estate')->nullable()->after('home_ward');
            $table->string('home_house_number')->nullable()->after('home_estate');
            
            // Spouse Information
            $table->string('spouse_name')->nullable()->after('marital_status');
            $table->string('spouse_phone_country_code', 5)->nullable()->after('spouse_name');
            $table->string('spouse_phone')->nullable()->after('spouse_phone_country_code');
            
            // Children Information
            $table->integer('number_of_children')->default(0)->after('spouse_phone');
            $table->text('children_names')->nullable()->after('number_of_children'); // JSON or comma-separated
            
            // Parents Information
            $table->string('father_name')->nullable()->after('children_names');
            $table->string('father_phone_country_code', 5)->nullable()->after('father_name');
            $table->string('father_phone')->nullable()->after('father_phone_country_code');
            $table->string('father_county')->nullable()->after('father_phone');
            $table->string('father_sub_county')->nullable()->after('father_county');
            $table->string('father_ward')->nullable()->after('father_sub_county');
            $table->string('mother_name')->nullable()->after('father_ward');
            $table->string('mother_phone_country_code', 5)->nullable()->after('mother_name');
            $table->string('mother_phone')->nullable()->after('mother_phone_country_code');
            
            // Health/Physical Condition
            $table->string('health_physical_condition')->nullable()->after('medical_conditions');
            
            // Emergency Contact (with country code)
            $table->string('emergency_contact_phone_country_code', 5)->nullable()->after('emergency_contact_phone');
            
            // Next of Kin (with country code)
            $table->string('next_of_kin_phone_country_code', 5)->nullable()->after('next_of_kin_phone');
            
            // Educational Qualifications
            $table->string('primary_school')->nullable()->after('next_of_kin_phone_country_code');
            $table->integer('primary_graduation_year')->nullable()->after('primary_school');
            $table->string('secondary_school')->nullable()->after('primary_graduation_year');
            $table->integer('secondary_graduation_year')->nullable()->after('secondary_school');
            $table->string('university_college')->nullable()->after('secondary_graduation_year');
            $table->integer('university_graduation_year')->nullable()->after('university_college');
            $table->string('professional_qualifications')->nullable()->after('university_graduation_year');
            
            // Special Skills
            $table->text('special_skills')->nullable()->after('professional_qualifications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'position',
                'nationality',
                'sex',
                'religion',
                'current_address',
                'home_county',
                'home_sub_county',
                'home_ward',
                'home_estate',
                'home_house_number',
                'spouse_name',
                'spouse_phone_country_code',
                'spouse_phone',
                'number_of_children',
                'children_names',
                'father_name',
                'father_phone_country_code',
                'father_phone',
                'father_county',
                'father_sub_county',
                'father_ward',
                'mother_name',
                'mother_phone_country_code',
                'mother_phone',
                'emergency_contact_phone_country_code',
                'next_of_kin_phone_country_code',
                'health_physical_condition',
                'primary_school',
                'primary_graduation_year',
                'secondary_school',
                'secondary_graduation_year',
                'university_college',
                'university_graduation_year',
                'professional_qualifications',
                'special_skills',
            ]);
        });
    }
};
