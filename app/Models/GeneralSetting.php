<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = [
        'logo_path',
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
    ];
}



