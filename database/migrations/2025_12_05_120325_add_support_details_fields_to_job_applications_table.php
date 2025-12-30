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
        Schema::table('job_applications', function (Blueprint $table) {
            $table->text('certifications')->nullable()->after('support_details');
            $table->text('languages')->nullable()->after('certifications');
            $table->text('professional_memberships')->nullable()->after('languages');
            $table->text('awards_recognition')->nullable()->after('professional_memberships');
            $table->text('portfolio_links')->nullable()->after('awards_recognition');
            $table->string('availability_travel')->nullable()->after('portfolio_links');
            $table->string('availability_relocation')->nullable()->after('availability_travel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'certifications',
                'languages',
                'professional_memberships',
                'awards_recognition',
                'portfolio_links',
                'availability_travel',
                'availability_relocation'
            ]);
        });
    }
};
