<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">Fortress Lenders Ltd</h1>
                            <p style="margin: 10px 0 0 0; color: #e6fffa; font-size: 14px;">The Force Of Possibilities!</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="margin: 0 0 20px 0; color: #1f2937; font-size: 24px;">Latest News & Updates</h2>
                            
                            <p style="margin: 0 0 30px 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                Thank you for subscribing to our newsletter! Here are the latest updates and news from Fortress Lenders.
                            </p>

                            @if($posts->count() > 0)
                                @foreach($posts as $post)
                                    <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid #e5e7eb;">
                                        @if($post->featured_image_path)
                                            <img src="{{ asset('storage/' . $post->featured_image_path) }}" alt="{{ $post->title }}" style="width: 100%; max-width: 540px; height: auto; border-radius: 8px; margin-bottom: 15px;">
                                        @endif
                                        
                                        <h3 style="margin: 0 0 10px 0; color: #0d9488; font-size: 20px;">
                                            <a href="{{ route('posts.show', $post->slug) }}" style="color: #0d9488; text-decoration: none;">{{ $post->title }}</a>
                                        </h3>
                                        
                                        @if($post->published_at)
                                            <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 14px;">
                                                {{ $post->published_at->format('F d, Y') }}
                                            </p>
                                        @endif
                                        
                                        @if($post->excerpt)
                                            <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                                {{ $post->excerpt }}
                                            </p>
                                        @elseif($post->content)
                                            <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                                {{ Str::limit(strip_tags($post->content), 200) }}
                                            </p>
                                        @endif
                                        
                                        <a href="{{ route('posts.show', $post->slug) }}" style="display: inline-block; padding: 12px 24px; background-color: #0d9488; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px;">
                                            Read More →
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <p style="margin: 0 0 30px 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                    Stay tuned for upcoming news and updates from Fortress Lenders!
                                </p>
                            @endif

                            <!-- CTA Section -->
                            <div style="margin-top: 30px; padding: 20px; background-color: #f0fdfa; border-radius: 8px; border-left: 4px solid #0d9488;">
                                <h3 style="margin: 0 0 10px 0; color: #1f2937; font-size: 18px;">Interested in Our Services?</h3>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 14px; line-height: 1.6;">
                                    Explore our loan products and financial solutions designed to empower your goals.
                                </p>
                                <a href="{{ route('products') }}" style="display: inline-block; padding: 10px 20px; background-color: #0d9488; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; margin-right: 10px;">
                                    View Products
                                </a>
                                <a href="mailto:{{ $generalSettings->company_email ?? 'info@example.com' }}" style="display: inline-block; padding: 10px 20px; background-color: #ffffff; color: #0d9488; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; border: 2px solid #0d9488;">
                                    Contact Us
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1f2937; padding: 20px; text-align: center;">
                            <p style="margin: 0 0 10px 0; color: #9ca3af; font-size: 12px;">
                                Fortress Lenders Ltd | P.O BOX: 7214- 20110, Nakuru Town, KENYA
                            </p>
                            <p style="margin: 0 0 10px 0; color: #9ca3af; font-size: 12px;">
                                Phone: +254 743 838 312 | +254 722 295 194<br>
                                Email: info@fortresslenders.com
                            </p>
                            <p style="margin: 15px 0 0 0; color: #6b7280; font-size: 11px;">
                                You are receiving this email because you subscribed to our newsletter.<br>
                                <a href="{{ route('newsletter.unsubscribe') }}" style="color: #14b8a6; text-decoration: underline;">Unsubscribe</a>
                            </p>
                            <p style="margin: 15px 0 0 0; color: #6b7280; font-size: 11px;">
                                &copy; {{ date('Y') }} Fortress Lenders Ltd. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

