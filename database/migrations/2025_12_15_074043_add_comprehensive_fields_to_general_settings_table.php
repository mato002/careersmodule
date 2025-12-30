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
        Schema::table('general_settings', function (Blueprint $table) {
            // Company Information
            $table->string('company_name')->nullable()->after('logo_path');
            $table->text('company_description')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_registration_number')->nullable();
            
            // Social Media Links
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            
            // SEO Settings
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('google_analytics_id')->nullable();
            $table->string('google_tag_manager_id')->nullable();
            $table->string('favicon_path')->nullable();
            
            // Footer Settings
            $table->text('footer_text')->nullable();
            $table->string('copyright_text')->nullable();
            $table->string('privacy_policy_url')->nullable();
            $table->string('terms_of_service_url')->nullable();
            
            // Notification Settings
            $table->text('contact_notification_recipients')->nullable(); // Comma-separated emails
            $table->text('loan_notification_recipients')->nullable();
            $table->text('job_notification_recipients')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'company_description',
                'company_email',
                'company_phone',
                'company_address',
                'company_registration_number',
                'facebook_url',
                'twitter_url',
                'linkedin_url',
                'instagram_url',
                'youtube_url',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'google_analytics_id',
                'google_tag_manager_id',
                'favicon_path',
                'footer_text',
                'copyright_text',
                'privacy_policy_url',
                'terms_of_service_url',
                'contact_notification_recipients',
                'loan_notification_recipients',
                'job_notification_recipients',
            ]);
        });
    }
};
