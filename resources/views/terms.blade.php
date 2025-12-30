@extends('layouts.website')

@section('title', 'Terms & Conditions - Fortress Lenders Ltd')
@section('meta_description', 'Read the terms and conditions for using Fortress Lenders Ltd services, including our loans, advisory services, and website.')

@section('content')
    <section class="bg-white pt-20 pb-12 sm:pt-24 sm:pb-16">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 max-w-5xl mx-auto">
            <header class="mb-8 sm:mb-10">
                <p class="text-xs sm:text-sm font-semibold tracking-[0.2em] text-teal-600 uppercase mb-2">Legal</p>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                    Terms &amp; Conditions
                </h1>
                <p class="text-sm sm:text-base text-gray-600">
                    Please read these terms carefully before using our services or applying for a loan with Fortress Lenders Ltd.
                </p>
            </header>

            <div class="space-y-8 text-sm sm:text-base text-gray-700 leading-relaxed">
                <section>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">1. About Fortress Lenders</h2>
                    <p>
                        Fortress Lenders Ltd is a registered credit-only institution in the Republic of Kenya.
                        By accessing our website, contacting us, or applying for a loan, you agree to be bound by these Terms &amp; Conditions
                        and any additional terms provided in specific loan agreements or documentation.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">2. Eligibility</h2>
                    <p class="mb-2">To apply for a loan or use our financial services, you must:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Be at least 18 years old.</li>
                        <li>Provide accurate and complete personal, contact, and financial information.</li>
                        <li>Comply with all applicable Kenyan laws and regulations.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">3. Loan Applications &amp; Approval</h2>
                    <p class="mb-2">
                        Submitting a loan application through our website, branches, or agents does not guarantee approval.
                        Each application is evaluated based on our internal credit assessment criteria and supporting documentation.
                    </p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>We may request additional information or documents before making a decision.</li>
                        <li>Approved loans will be documented in a separate agreement outlining amounts, fees, interest, and repayment terms.</li>
                        <li>You are responsible for reviewing and understanding all terms before accepting any loan offer.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">4. Repayment Obligations</h2>
                    <p class="mb-2">
                        By accepting a loan, you agree to repay all amounts due (principal, interest, and applicable fees) in accordance
                        with the signed loan agreement and repayment schedule.
                    </p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Late or missed payments may attract additional charges and affect your future access to credit.</li>
                        <li>We may contact you or your provided referees/guarantors regarding outstanding obligations.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">5. Use of the Website</h2>
                    <p class="mb-2">
                        You agree to use this website only for lawful purposes and not to engage in any activity that could harm,
                        disable, overburden, or impair our systems or services.
                    </p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Do not attempt to gain unauthorized access to any part of the site or related systems.</li>
                        <li>Do not submit false, misleading, or fraudulent information.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">6. Privacy &amp; Data Protection</h2>
                    <p>
                        We handle your personal information in line with applicable data protection laws and our internal policies.
                        Information you provide may be used for application processing, customer support, compliance, and service improvement.
                        For specific privacy details, please contact us using the information on the Contact page.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">7. Cookies</h2>
                    <p>
                        Our website uses cookies to improve your browsing experience, analyze traffic, and personalize content.
                        You can choose to accept or reject non-essential cookies using the cookie banner. Some essential cookies
                        are required for the site to function correctly.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">8. Changes to These Terms</h2>
                    <p>
                        Fortress Lenders Ltd may update these Terms &amp; Conditions from time to time.
                        When we do, we will revise the “last updated” date below. Continued use of our website or services
                        after changes are published constitutes your acceptance of the updated terms.
                    </p>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">9. Contact Us</h2>
                    <p class="mb-1">
                        If you have any questions about these Terms &amp; Conditions or our services, please contact us:
                    </p>
                    <p class="text-sm text-gray-700">
                        Phone: +254 743 838 312 / +254 722 295 194<br>
                        Email: <a href="mailto:info@fortresslenders.com" class="text-teal-700 hover:text-teal-800 underline">info@fortresslenders.com</a>
                    </p>
                </section>

                <p class="text-xs text-gray-500 mt-6">
                    Last updated: {{ now()->format('F d, Y') }}
                </p>
            </div>
        </div>
    </section>
@endsection







