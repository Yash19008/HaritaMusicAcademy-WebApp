<?php

/**
 * Locale / Country Configuration
 * ---------------------------------
 * Single source of truth for all country-specific landing-page data.
 * To adjust demo prices, plan prices, or add new markets — edit this file only.
 *
 * Supported slugs: in | us | uk | cad | uae
 *
 * Fields per locale:
 *  country          – Display name
 *  flag             – Emoji flag
 *  currency_code    – ISO 4217 code used by Razorpay
 *  currency_sym     – Symbol prepended to prices in the UI
 *  phone_code       – E.164 dial prefix (for future use)
 *  demo_price       – Demo class fee in major currency unit (e.g., 499 for ₹499)
 *  individual_plans – Ordered array of individual tuition plans
 *  group_plans      – Ordered array of group tuition plans
 *
 * Plan fields:
 *  programme   – Programme family name
 *  tier        – Plan tier label shown as subtitle
 *  duration    – Duration string shown in the pill badge
 *  classes     – Total number of live classes
 *  per_week    – Classes per week
 *  price       – Price in major currency unit
 *  popular     – (optional) true → "MOST POPULAR" badge
 *  premium     – (optional) true → Wide premium row layout
 */

return [

    // ─── India ────────────────────────────────────────────────────────────────
    'in' => [
        'country'       => 'India',
        'flag'          => '🇮🇳',
        'currency_code' => 'INR',
        'currency_sym'  => '₹',
        'phone_code'    => '+91',
        'demo_price'    => 499,

        'individual_plans' => [
            [
                'programme' => 'Musical Foundation',
                'tier'      => 'Essential',
                'duration'  => '3 Months',
                'classes'   => 24,
                'per_week'  => 2,
                'price'     => 16000,
            ],
            [
                'programme' => 'Musical Foundation',
                'tier'      => 'Accelerated',
                'duration'  => '3 Months',
                'classes'   => 36,
                'per_week'  => 3,
                'price'     => 25000,
                'popular'   => true,
            ],
            [
                'programme' => 'Musical Development',
                'tier'      => 'Essential',
                'duration'  => '6 Months',
                'classes'   => 48,
                'per_week'  => 2,
                'price'     => 32000,
            ],
            [
                'programme' => 'Musical Development',
                'tier'      => 'Accelerated',
                'duration'  => '6 Months',
                'classes'   => 72,
                'per_week'  => 3,
                'price'     => 47300,
            ],
            [
                'programme' => 'Musical Transformation',
                'tier'      => 'Advance',
                'duration'  => '10 Months',
                'classes'   => 60,
                'per_week'  => 2,
                'price'     => 47000,
                'premium'   => true,
            ],
            [
                'programme' => 'Musical Transformation',
                'tier'      => 'Signature',
                'duration'  => '12 Months',
                'classes'   => 80,
                'per_week'  => 2,
                'price'     => 62000,
                'premium'   => true,
            ],
        ],

        'group_plans' => [
            [
                'programme' => 'Musical Foundation',
                'duration'  => '3 Months',
                'classes'   => 24,
                'per_week'  => 2,
                'price'     => 7200,
            ],
            [
                'programme' => 'Musical Development',
                'duration'  => '6 Months',
                'classes'   => 48,
                'per_week'  => 2,
                'price'     => 14400,
            ],
        ],

        'reviews' => [
            [
                'name'  => 'Ananya Deshpande',
                'type'  => 'Student',
                'loc'   => 'Mumbai, India',
                'img'   => 'ananya_deshpande.png',
                'quote' => '“I never imagined that learning Hindustani classical music online could feel so personal. Every class gives me something new to practice and helps me understand my voice better.”',
                'date'  => 'Aug 2026',
            ],
            [
                'name'  => 'Anil Mehta',
                'type'  => 'Parent',
                'loc'   => 'Pune, India',
                'img'   => 'anil_mehta.png',
                'quote' => '“We wanted our daughter to learn Hindustani classical music properly rather than simply learning a few songs, and HMA has given her a strong foundation along with a genuine love for music.”',
                'date'  => 'Aug 2026',
            ],
            [
                'name'  => 'Meera Krishnan',
                'type'  => 'Student',
                'loc'   => 'New Delhi, India',
                'img'   => 'meera_krishnan.png',
                'quote' => '“Since joining HMA, I have become much more confident in my singing. The teachers explain every concept patiently and encourage me to keep improving with regular riyaaz.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'Rohit Deshmukh',
                'type'  => 'Parent',
                'loc'   => 'Bangalore, India',
                'img'   => 'rohit_deshmukh.png',
                'quote' => '“The biggest change we have noticed is our child’s confidence. She has become much more comfortable with her voice and now looks forward to her music classes every week.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'Pooja Nair',
                'type'  => 'Student',
                'loc'   => 'Chennai, India',
                'img'   => 'pooja_nair.png',
                'quote' => '“What I love most about HMA is that we don’t just learn songs—we learn the actual foundation and discipline of Hindustani classical music.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'Vihaan Mehta',
                'type'  => 'Parent',
                'loc'   => 'Hyderabad, India',
                'img'   => 'vihaan_mehta.png',
                'quote' => '“We are very impressed with the discipline and structure of the classes. Our child is not only learning music but also understanding the importance of regular practice and riyaaz.”',
                'date'  => 'Jun 2026',
            ],
        ],
    ],

    // ─── United States ────────────────────────────────────────────────────────
    'us' => [
        'country'       => 'United States',
        'flag'          => '🇺🇸',
        'currency_code' => 'USD',
        'currency_sym'  => '$',
        'phone_code'    => '+1',
        'demo_price'    => 6,

        'individual_plans' => [
            [
                'programme' => 'Musical Foundation',
                'tier'      => 'Essential',
                'duration'  => '3 Months',
                'classes'   => 24,
                'per_week'  => 2,
                'price'     => 499,
            ],
            [
                'programme' => 'Musical Foundation',
                'tier'      => 'Accelerated',
                'duration'  => '3 Months',
                'classes'   => 36,
                'per_week'  => 3,
                'price'     => 749,
                'popular'   => true,
            ],
            [
                'programme' => 'Musical Development',
                'tier'      => 'Essential',
                'duration'  => '6 Months',
                'classes'   => 48,
                'per_week'  => 2,
                'price'     => 999,
            ],
            [
                'programme' => 'Musical Development',
                'tier'      => 'Accelerated',
                'duration'  => '6 Months',
                'classes'   => 72,
                'per_week'  => 3,
                'price'     => 1499,
            ],
            [
                'programme' => 'Musical Transformation',
                'tier'      => 'Advance',
                'duration'  => '10 Months',
                'classes'   => 60,
                'per_week'  => 2,
                'price'     => 1250,
                'premium'   => true,
            ],
            [
                'programme' => 'Musical Transformation',
                'tier'      => 'Signature',
                'duration'  => '12 Months',
                'classes'   => 80,
                'per_week'  => 2,
                'price'     => 2000,
                'premium'   => true,
            ],
        ],

        'group_plans' => [
            [
                'programme' => 'Musical Foundation',
                'duration'  => '3 Months',
                'classes'   => 24,
                'per_week'  => 2,
                'price'     => 299,
            ],
            [
                'programme' => 'Musical Development',
                'duration'  => '6 Months',
                'classes'   => 48,
                'per_week'  => 2,
                'price'     => 599,
            ],
        ],

        'reviews' => [
            [
                'name'  => 'Isabella Wright',
                'type'  => 'Student',
                'loc'   => 'New Jersey, USA',
                'img'   => 'isabella_wright.png',
                'quote' => '“Even though I live outside India, HMA has given me the opportunity to learn authentic Hindustani classical music in a traditional and structured way.”',
                'date'  => 'Aug 2026',
            ],
            [
                'name'  => 'Ethan Mitchell',
                'type'  => 'Parent',
                'loc'   => 'California, USA',
                'img'   => 'ethan_mitchell.png',
                'quote' => '“As parents living abroad, finding authentic Hindustani classical music training was important to us. HMA has provided the traditional learning environment and guidance we were looking for.”',
                'date'  => 'Aug 2026',
            ],
            [
                'name'  => 'Emily Watson',
                'type'  => 'Student',
                'loc'   => 'Texas, USA',
                'img'   => 'emily_watson.png',
                'quote' => '“Learning Hindustani classical music has helped me become more confident not only as a singer but also as a performer. I am really happy with how far I have come.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'James Whitaker',
                'type'  => 'Parent',
                'loc'   => 'Illinois, USA',
                'img'   => 'james_whitaker.png',
                'quote' => '“HMA has given our child an opportunity to stay connected with Indian culture through music while receiving serious and structured classical training. We are very happy with the progress.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'Neha Kapoor',
                'type'  => 'Student',
                'loc'   => 'New York, USA',
                'img'   => 'neha_kapoor.png',
                'quote' => '“Being able to learn Indian classical music from experienced teachers while living abroad is something I truly value. HMA has helped me stay connected to our musical heritage.”',
                'date'  => 'Jun 2026',
            ],
            [
                'name'  => 'Arjun Malhotra',
                'type'  => 'Parent',
                'loc'   => 'Washington, USA',
                'img'   => 'arjun_malhotra.png',
                'quote' => '“The online classes have worked beautifully for our family. Despite living abroad, our child receives consistent guidance and continues to learn Hindustani classical music in a traditional manner.”',
                'date'  => 'Jun 2026',
            ],
        ],
    ],

    // ─── United Kingdom ───────────────────────────────────────────────────────
    'uk' => [
        'country'       => 'United Kingdom',
        'flag'          => '🇬🇧',
        'currency_code' => 'GBP',
        'currency_sym'  => '£',
        'phone_code'    => '+44',
        'demo_price'    => 4,

        'individual_plans' => [
            [
                'programme' => 'Musical Foundation',
                'tier'      => 'Essential',
                'duration'  => '3 Months',
                'classes'   => 24,
                'per_week'  => 2,
                'price'     => 370,
            ],
            [
                'programme' => 'Musical Foundation',
                'tier'      => 'Accelerated',
                'duration'  => '3 Months',
                'classes'   => 36,
                'per_week'  => 3,
                'price'     => 555,
                'popular'   => true,
            ],
            [
                'programme' => 'Musical Development',
                'tier'      => 'Essential',
                'duration'  => '6 Months',
                'classes'   => 48,
                'per_week'  => 2,
                'price'     => 740,
            ],
            [
                'programme' => 'Musical Development',
                'tier'      => 'Accelerated',
                'duration'  => '6 Months',
                'classes'   => 72,
                'per_week'  => 3,
                'price'     => 1111,
            ],
            [
                'programme' => 'Musical Transformation',
                'tier'      => 'Advance',
                'duration'  => '10 Months',
                'classes'   => 60,
                'per_week'  => 2,
                'price'     => 926,
                'premium'   => true,
            ],
            [
                'programme' => 'Musical Transformation',
                'tier'      => 'Signature',
                'duration'  => '12 Months',
                'classes'   => 80,
                'per_week'  => 2,
                'price'     => 1482,
                'premium'   => true,
            ],
        ],

        'group_plans' => [
            [
                'programme' => 'Musical Foundation',
                'duration'  => '3 Months',
                'classes'   => 24,
                'per_week'  => 2,
                'price'     => 225,
            ],
            [
                'programme' => 'Musical Development',
                'duration'  => '6 Months',
                'classes'   => 48,
                'per_week'  => 2,
                'price'     => 449,
            ],
        ],

        'reviews' => [
            [
                'name'  => 'Charlotte Bennett',
                'type'  => 'Student',
                'loc'   => 'London, UK',
                'img'   => 'charlott_bennete.png',
                'quote' => '“The classes are challenging in a good way, and I can genuinely see my progress over time. My voice, confidence and understanding of music have all improved.”',
                'date'  => 'Aug 2026',
            ],
            [
                'name'  => 'Oliver Grant',
                'type'  => 'Parent',
                'loc'   => 'Birmingham, UK',
                'img'   => 'oliver_grant.png',
                'quote' => '“The teachers are patient, encouraging and attentive to the individual needs of the student. We can clearly see the difference in our child’s confidence and musical understanding.”',
                'date'  => 'Aug 2026',
            ],
            [
                'name'  => 'Sophia Williams',
                'type'  => 'Student',
                'loc'   => 'Manchester, UK',
                'img'   => 'sophia_williams.png',
                'quote' => '“I used to find classical music difficult to understand, but the way everything is explained and practiced at HMA makes it much easier and more enjoyable.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'Daniel Foster',
                'type'  => 'Parent',
                'loc'   => 'Leicester, UK',
                'img'   => 'daniel_foster.png',
                'quote' => '“What we appreciate most about HMA is that the focus is on building fundamentals. Our child is learning voice culture, swaras, rhythm and proper musical discipline step by step.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'Meena Sharma',
                'type'  => 'Student',
                'loc'   => 'Leeds, UK',
                'img'   => 'meena_sharma.png',
                'quote' => '“My favourite part of learning with HMA is the personal attention during classes. I feel comfortable asking questions and never feel rushed while learning something new.”',
                'date'  => 'Jun 2026',
            ],
            [
                'name'  => 'Karan Rathod',
                'type'  => 'Parent',
                'loc'   => 'Glasgow, UK',
                'img'   => 'karan_rathod.png',
                'quote' => '“We have seen a beautiful change in our child’s relationship with music. What started as an extracurricular activity has gradually become something she genuinely enjoys and looks forward to.”',
                'date'  => 'Jun 2026',
            ],
        ],
    ],

    // ─── Canada ───────────────────────────────────────────────────────────────
    'cad' => [
        'country'       => 'Canada',
        'flag'          => '🇨🇦',
        'currency_code' => 'CAD',
        'currency_sym'  => 'C$',
        'phone_code'    => '+1',
        'demo_price'    => 7.5,

        'individual_plans' => [
            [
                'programme' => 'Musical Foundation',
                'tier'      => 'Essential',
                'duration'  => '3 Months',
                'classes'   => 24,
                'per_week'  => 2,
                'price'     => 689,
            ],
            [
                'programme' => 'Musical Foundation',
                'tier'      => 'Accelerated',
                'duration'  => '3 Months',
                'classes'   => 36,
                'per_week'  => 3,
                'price'     => 1034,
                'popular'   => true,
            ],
            [
                'programme' => 'Musical Development',
                'tier'      => 'Essential',
                'duration'  => '6 Months',
                'classes'   => 48,
                'per_week'  => 2,
                'price'     => 1380,
            ],
            [
                'programme' => 'Musical Development',
                'tier'      => 'Accelerated',
                'duration'  => '6 Months',
                'classes'   => 72,
                'per_week'  => 3,
                'price'     => 2070,
            ],
            [
                'programme' => 'Musical Transformation',
                'tier'      => 'Advance',
                'duration'  => '10 Months',
                'classes'   => 60,
                'per_week'  => 2,
                'price'     => 1726,
                'premium'   => true,
            ],
            [
                'programme' => 'Musical Transformation',
                'tier'      => 'Signature',
                'duration'  => '12 Months',
                'classes'   => 80,
                'per_week'  => 2,
                'price'     => 2762,
                'premium'   => true,
            ],
        ],

        'group_plans' => [
            [
                'programme' => 'Musical Foundation',
                'duration'  => '3 Months',
                'classes'   => 24,
                'per_week'  => 2,
                'price'     => 419,
            ],
            [
                'programme' => 'Musical Development',
                'duration'  => '6 Months',
                'classes'   => 48,
                'per_week'  => 2,
                'price'     => 829,
            ],
        ],

        'reviews' => [
            [
                'name'  => 'Yuna Kim',
                'type'  => 'Student',
                'loc'   => 'Toronto, Canada',
                'img'   => 'yuna_kim.png',
                'quote' => '“HMA has made music a very meaningful part of my weekly routine. I have become more disciplined with my riyaaz and genuinely look forward to every class.”',
                'date'  => 'Aug 2026',
            ],
            [
                'name'  => 'Luca Bianchi',
                'type'  => 'Parent',
                'loc'   => 'Vancouver, Canada',
                'img'   => 'luca_bianchi.png',
                'quote' => '“As parents, we were looking for quality, discipline and authentic teaching, and HMA has delivered all three. It is wonderful to see our child growing both musically and personally through this journey.”',
                'date'  => 'Aug 2026',
            ],
            [
                'name'  => 'Elena Papado',
                'type'  => 'Student',
                'loc'   => 'Calgary, Canada',
                'img'   => 'elena_papado.png',
                'quote' => '“Being able to learn Indian classical music from experienced teachers while living abroad is something I truly value. HMA has helped me stay connected to our musical heritage.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'Nir Canada',
                'type'  => 'Parent',
                'loc'   => 'Ottawa, Canada',
                'img'   => 'nir_canada.png',
                'quote' => '“HMA has given our child an opportunity to stay connected with Indian culture through music while receiving serious and structured classical training. We are very happy with the progress.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'Radhika Iyer',
                'type'  => 'Student',
                'loc'   => 'Montreal, Canada',
                'img'   => 'radhika_iyer.png',
                'quote' => '“Even though I live outside India, HMA has given me the opportunity to learn authentic Hindustani classical music in a traditional and structured way.”',
                'date'  => 'Jun 2026',
            ],
            [
                'name'  => 'Abhinav Verma',
                'type'  => 'Parent',
                'loc'   => 'Edmonton, Canada',
                'img'   => 'abhinav_verma.png',
                'quote' => '“The online classes have worked beautifully for our family. Despite living abroad, our child receives consistent guidance and continues to learn Hindustani classical music in a traditional manner.”',
                'date'  => 'Jun 2026',
            ],
        ],
    ],

    // ─── UAE ──────────────────────────────────────────────────────────────────
    'uae' => [
        'country'       => 'UAE',
        'flag'          => '🇦🇪',
        'currency_code' => 'AED',
        'currency_sym'  => 'AED ',
        'phone_code'    => '+971',
        'demo_price'    => 20,

        'individual_plans' => [
            [
                'programme' => 'Musical Foundation',
                'tier'      => 'Essential',
                'duration'  => '3 Months',
                'classes'   => 24,
                'per_week'  => 2,
                'price'     => 1833,
            ],
            [
                'programme' => 'Musical Foundation',
                'tier'      => 'Accelerated',
                'duration'  => '3 Months',
                'classes'   => 36,
                'per_week'  => 3,
                'price'     => 2751,
                'popular'   => true,
            ],
            [
                'programme' => 'Musical Development',
                'tier'      => 'Essential',
                'duration'  => '6 Months',
                'classes'   => 48,
                'per_week'  => 2,
                'price'     => 3669,
            ],
            [
                'programme' => 'Musical Development',
                'tier'      => 'Accelerated',
                'duration'  => '6 Months',
                'classes'   => 72,
                'per_week'  => 3,
                'price'     => 5506,
            ],
            [
                'programme' => 'Musical Transformation',
                'tier'      => 'Advance',
                'duration'  => '10 Months',
                'classes'   => 60,
                'per_week'  => 2,
                'price'     => 4591,
                'premium'   => true,
            ],
            [
                'programme' => 'Musical Transformation',
                'tier'      => 'Signature',
                'duration'  => '12 Months',
                'classes'   => 80,
                'per_week'  => 2,
                'price'     => 7346,
                'premium'   => true,
            ],
        ],

        'group_plans' => [
            [
                'programme' => 'Musical Foundation',
                'duration'  => '3 Months',
                'classes'   => 24,
                'per_week'  => 2,
                'price'     => 1099,
            ],
            [
                'programme' => 'Musical Development',
                'duration'  => '6 Months',
                'classes'   => 48,
                'per_week'  => 2,
                'price'     => 2199,
            ],
        ],

        'reviews' => [
            [
                'name'  => 'Zara Khan',
                'type'  => 'Student',
                'loc'   => 'Dubai, UAE',
                'img'   => 'zara_khan.png',
                'quote' => '“Even though I live outside India, HMA has given me the opportunity to learn authentic Hindustani classical music in a traditional and structured way.”',
                'date'  => 'Aug 2026',
            ],
            [
                'name'  => 'Rashid Al Neyadi',
                'type'  => 'Parent',
                'loc'   => 'Abu Dhabi, UAE',
                'img'   => 'rashid_al_neyadi.png',
                'quote' => '“As parents living abroad, finding authentic Hindustani classical music training was important to us. HMA has provided the traditional learning environment and guidance we were looking for.”',
                'date'  => 'Aug 2026',
            ],
            [
                'name'  => 'Salma Al Qasimi',
                'type'  => 'Student',
                'loc'   => 'Sharjah, UAE',
                'img'   => 'salma_al_qasimi.png',
                'quote' => '“HMA has made music a very meaningful part of my weekly routine. I have become more disciplined with my riyaaz and genuinely look forward to every class.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'Rohit Yadav',
                'type'  => 'Parent',
                'loc'   => 'Dubai, UAE',
                'img'   => 'rohit_yadav.png',
                'quote' => '“HMA has given our child an opportunity to stay connected with Indian culture through music while receiving serious and structured classical training. We are very happy with the progress.”',
                'date'  => 'Jul 2026',
            ],
            [
                'name'  => 'Tara Sharma',
                'type'  => 'Student',
                'loc'   => 'Abu Dhabi, UAE',
                'img'   => 'tara_sharma.png',
                'quote' => '“My favourite part of learning with HMA is the personal attention during classes. I feel comfortable asking questions and never feel rushed while learning something new.”',
                'date'  => 'Jun 2026',
            ],
            [
                'name'  => 'Aman Verma',
                'type'  => 'Parent',
                'loc'   => 'Ajman, UAE',
                'img'   => 'aman_verma.png',
                'quote' => '“As parents, we were looking for quality, discipline and authentic teaching, and HMA has delivered all three. It is wonderful to see our child growing both musically and personally through this journey.”',
                'date'  => 'Jun 2026',
            ],
        ],
    ],

];
