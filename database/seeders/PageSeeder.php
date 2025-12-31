<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about-us',
                'title_en' => 'About Mohaaseb',
                'title_ar' => 'من نحن',
                'short_description_en' => 'Mohaaseb is a smart accounting platform designed to simplify financial management for businesses and individuals.',
                'short_description_ar' => 'محاسب هو نظام محاسبي ذكي يهدف إلى تبسيط إدارة الحسابات للشركات والأفراد.',
                'content_en' => "Mohaaseb is a cloud-based accounting platform designed to help businesses, freelancers, and startups manage their financial operations efficiently.\n\nOur system provides tools for tracking income and expenses, managing invoices, monitoring cash flow, and generating financial reports with accuracy and ease.\n\nMohaaseb is a software solution and does not provide legal, tax, or financial consulting services. Users are responsible for ensuring compliance with local laws and regulations.\n\nOur mission is to deliver a reliable, secure, and user-friendly accounting system that supports business growth and financial clarity.",
                'content_ar' => "محاسب هو منصة محاسبية سحابية تم تطويرها لمساعدة الشركات، رواد الأعمال، وأصحاب الأعمال الحرة على إدارة عملياتهم المالية بكفاءة.\n\nيوفر النظام أدوات لإدارة الإيرادات والمصروفات، الفواتير، التدفقات النقدية، والتقارير المالية بسهولة ودقة.\n\nمحاسب هو حل برمجي ولا يقدم استشارات قانونية أو ضريبية أو مالية، ويقع على عاتق المستخدم الالتزام بالقوانين واللوائح المحلية.\n\nهدفنا هو تقديم نظام محاسبي آمن وسهل الاستخدام يساعد على وضوح الرؤية المالية ودعم نمو الأعمال.",
                'is_published' => true,
            ],
            [
                'slug' => 'privacy-policy',
                'title_en' => 'Privacy Policy',
                'title_ar' => 'سياسة الخصوصية',
                'short_description_en' => 'Learn how Mohaaseb collects, uses, and protects your personal data and wallet information.',
                'short_description_ar' => 'تعرف على كيفية جمع واستخدام وحماية بياناتك ومعلومات المحفظة في منصة محاسب.',
                'content_en' => "Mohaaseb respects your privacy and is committed to protecting your personal data.\n\nWe collect information such as name, email address, account details, wallet balance, transaction history, and usage activity in order to operate and improve our services.\n\nYour data is used for account management, subscription processing, wallet balance management, customer support, payment reconciliation, and system improvements.\n\nIn cases of subscription cancellation or plan changes, any eligible remaining amount may be credited to the user's internal wallet balance in accordance with our Refund & Cancellation Policy. Wallet balances are non-withdrawable and can only be used within the platform.\n\nWe do not sell or share your personal data with third parties except when required by law or when necessary to provide essential services such as payment processing.\n\nWe apply appropriate technical and organizational security measures to protect your data; however, no online platform can guarantee absolute security.\n\nBy using Mohaaseb, you agree to this Privacy Policy.",
                'content_ar' => "تحترم منصة محاسب خصوصيتك وتلتزم بحماية بياناتك الشخصية.\n\nنقوم بجمع بعض البيانات مثل الاسم، البريد الإلكتروني، تفاصيل الحساب، رصيد المحفظة، سجل المعاملات، ونشاط الاستخدام وذلك بهدف تشغيل وتحسين خدماتنا.\n\nتُستخدم بياناتك لإدارة الحساب، معالجة الاشتراكات، إدارة رصيد المحفظة، مطابقة المدفوعات، الدعم الفني، وتحسين النظام.\n\nفي حال إلغاء الاشتراك أو تغيير الخطة، قد يتم إضافة أي مبلغ متبقٍ مؤهل إلى رصيد محفظة المستخدم الداخلية وذلك وفقًا لسياسة الإلغاء والاسترداد. رصيد المحفظة غير قابل للسحب ويُستخدم داخل المنصة فقط.\n\nلا نقوم ببيع أو مشاركة بياناتك مع أطراف خارجية إلا إذا كان ذلك مطلوبًا بموجب القانون أو ضروريًا لتشغيل خدمات أساسية مثل بوابات الدفع.\n\nنطبق إجراءات تقنية وتنظيمية مناسبة لحماية بياناتك، ومع ذلك لا يمكن لأي منصة إلكترونية ضمان الأمان الكامل.\n\nباستخدامك لمنصة محاسب فإنك توافق على سياسة الخصوصية هذه.",
                'is_published' => true,
            ],
            [
                'slug' => 'refund-cancellation-policy',
                'title_en' => 'Refund & Cancellation Policy',
                'title_ar' => 'سياسة الإلغاء والاسترداد',
                'short_description_en' => 'Understand how subscription cancellation and wallet refunds work on Mohaaseb.',
                'short_description_ar' => 'تعرف على آلية إلغاء الاشتراك وتحويل الرصيد إلى المحفظة داخل منصة محاسب.',
                'content_en' => "Mohaaseb operates on a subscription-based model.\n\nUsers may cancel or change their subscription at any time.\n\nPaid amounts are non-refundable to the original payment method. In case of cancellation or plan change, the remaining balance will be credited to the user's internal wallet within the platform.\n\nWallet balance can only be used for future subscriptions or services provided by Mohaaseb and cannot be withdrawn or transferred externally at this time.\n\nBy completing a payment, the user explicitly agrees to this Refund & Cancellation Policy.",
                'content_ar' => "تعمل منصة محاسب بنظام الاشتراكات المدفوعة.\n\nيمكن للمستخدم إلغاء أو تغيير الاشتراك في أي وقت.\n\nالمبالغ المدفوعة غير قابلة للاسترداد إلى وسيلة الدفع الأصلية. في حال الإلغاء أو تغيير الخطة، يتم تحويل الرصيد المتبقي إلى محفظة المستخدم داخل المنصة.\n\nيمكن استخدام رصيد المحفظة فقط للاشتراك في خدمات محاسب مستقبلًا، ولا يمكن سحبه أو تحويله خارج المنصة في الوقت الحالي.\n\nبإتمام عملية الدفع، يقر المستخدم بموافقته الكاملة على سياسة الإلغاء والاسترداد هذه.",
                'is_published' => true,
            ],
            [
                'slug' => 'fair-usage-policy',
                'title_en' => 'Fair Usage Policy',
                'title_ar' => 'سياسة الاستخدام العادل',
                'short_description_en' => 'Guidelines to ensure fair and responsible use of Mohaaseb services.',
                'short_description_ar' => 'إرشادات تضمن الاستخدام العادل والمسؤول لخدمات منصة محاسب.',
                'content_en' => "Mohaaseb is designed to serve businesses fairly and efficiently.\n\nUsers must not misuse the platform in ways that negatively affect system performance, security, or other users.\n\nProhibited activities include excessive automated requests, data scraping, attempting unauthorized access, or using the system for illegal purposes.\n\nMohaaseb reserves the right to limit, suspend, or terminate accounts that violate this policy without prior notice.\n\nThis policy ensures service stability and a fair experience for all users.",
                'content_ar' => "تم تصميم منصة محاسب لتقديم الخدمة بشكل عادل وفعال لجميع المستخدمين.\n\nيُحظر إساءة استخدام النظام بأي شكل قد يؤثر سلبًا على الأداء أو الأمان أو تجربة المستخدمين الآخرين.\n\nتشمل الأنشطة المحظورة الطلبات الآلية المفرطة، استخراج البيانات، محاولة الوصول غير المصرح به، أو استخدام النظام لأغراض غير قانونية.\n\nتحتفظ منصة محاسب بالحق في تقييد أو إيقاف أو إنهاء الحسابات المخالفة دون إشعار مسبق.\n\nتهدف هذه السياسة إلى ضمان استقرار الخدمة وتوفير تجربة عادلة للجميع.",
                'is_published' => true,
            ],
            [
                'slug' => 'terms-conditions',
                'title_en' => 'Terms & Conditions',
                'title_ar' => 'الشروط والأحكام',
                'short_description_en' => 'Read the terms and conditions for using our services.',
                'short_description_ar' => 'اقرأ الشروط والأحكام لاستخدام خدماتنا.',
                'content_en' => '<h2>Terms & Conditions</h2>
                    <p>Welcome to our website. By using our services, you agree to the following terms and conditions:</p>
                    <ul>
                    <li>Use the website responsibly and lawfully.</li>
                    <li>Respect the intellectual property of the website and third parties.</li>
                    <li>We reserve the right to suspend or terminate access for violations.</li>
                    <li>All payments, refunds, and account balance policies apply as described in our Privacy Policy.</li>
                    <li>We are not liable for any damages resulting from misuse of the website.</li>
                    </ul>
                    <p>For detailed information, please contact us via our support channels.</p>',
                'content_ar' => '<h2>الشروط والأحكام</h2>
                    <p>مرحبًا بكم في موقعنا. باستخدام خدماتنا، فإنك توافق على الشروط والأحكام التالية:</p>
                    <ul>
                    <li>استخدام الموقع بطريقة مسؤولة وقانونية.</li>
                    <li>احترام الملكية الفكرية للموقع والأطراف الثالثة.</li>
                    <li>نحتفظ بالحق في تعليق أو إنهاء الوصول في حالة الانتهاك.</li>
                    <li>جميع سياسات الدفع، الاسترداد، ورصيد الحساب تطبق كما هو موضح في سياسة الخصوصية.</li>
                    <li>نحن غير مسؤولين عن أي أضرار ناتجة عن سوء استخدام الموقع.</li>
                    </ul>
                    <p>للحصول على معلومات تفصيلية، يرجى التواصل معنا عبر قنوات الدعم الخاصة بنا.</p>',
                'is_published' => true,
            ]
        ];

        foreach ($pages as $pageData) {
            \App\Models\Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
