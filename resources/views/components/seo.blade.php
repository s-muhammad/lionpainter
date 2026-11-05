@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'image' => null,
    'type' => 'website',
    'robots' => 'index,follow,max-image-preview:large',
    'schema' => null,
    'breadcrumbs' => [],
])

@php
    $pageTitle = $title ?? setting('meta_title');
    $pageDescription = $description ?? setting('meta_description');
    $canonicalUrl = $canonical ?? url()->current();
    $imageUrl = $image ? (str_starts_with($image, 'http') ? $image : asset($image)) : asset('images/og-image.jpg');
    $brand = setting('brand', 'Lion Painters');

    $address = setting('address', 'Melbourne VIC');
    $localBusiness = [
        '@context' => 'https://schema.org',
        '@type' => ['LocalBusiness', 'HousePainter'],
        '@id' => route('home').'#business',
        'name' => $brand,
        'description' => setting('meta_description'),
        'url' => route('home'),
        'image' => $imageUrl,
        'logo' => asset('images/logo-mark.svg'),
        'telephone' => setting('phone'),
        'email' => setting('email'),
        'priceRange' => '$$',
        'currenciesAccepted' => 'AUD',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $address,
            'addressLocality' => 'Melbourne',
            'addressRegion' => 'VIC',
            'postalCode' => '3121',
            'addressCountry' => 'AU',
        ],
        'areaServed' => [
            '@type' => 'GeoCircle',
            'geoMidpoint' => ['@type' => 'GeoCoordinates', 'latitude' => -37.8136, 'longitude' => 144.9631],
            'geoRadius' => '40000',
        ],
        'openingHoursSpecification' => [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens' => '07:00',
            'closes' => '17:00',
        ],
        'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => setting('rating', '4.9'),
            'reviewCount' => setting('review_count', '127'),
            'bestRating' => '5',
        ],
        'sameAs' => array_values(array_filter([
            setting('instagram'),
            setting('facebook'),
        ])),
    ];

    $website = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => route('home').'#website',
        'url' => route('home'),
        'name' => $brand,
        'publisher' => ['@id' => route('home').'#business'],
        'inLanguage' => 'en-AU',
    ];

    $schemas = [$localBusiness, $website];

    if ($breadcrumbs) {
        $itemList = [];
        foreach ($breadcrumbs as $i => $crumb) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $crumb['label'],
                'item' => $crumb['url'] ?? url()->current(),
            ];
        }
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemList,
        ];
    }

    if ($schema) {
        $schemas[] = $schema;
    }
@endphp

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta name="author" content="{{ $brand }}">
<meta name="theme-color" content="#0c1e2e">
<meta name="geo.region" content="AU-VIC">
<meta name="geo.placename" content="Melbourne">

<meta property="og:site_name" content="{{ $brand }}">
<meta property="og:locale" content="en_AU">
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ $imageUrl }}">
<meta property="og:image:alt" content="{{ $brand }} — premium painting in Melbourne">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $imageUrl }}">

<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

@foreach ($schemas as $data)
    <script type="application/ld+json">{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endforeach
