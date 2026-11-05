<?php

use App\Models\Post;
use App\Models\Project;
use App\Models\Service;

beforeEach(function () {
    $this->seed();
});

test('public pages render', function (string $path) {
    $this->get($path)->assertOk();
})->with([
    '/',
    '/services',
    '/portfolio',
    '/about',
    '/service-areas',
    '/faqs',
    '/contact',
    '/quote',
    '/blog',
    '/privacy-policy',
    '/terms',
]);

test('service, project and post detail pages render', function () {
    $service = Service::firstOrFail();
    $project = Project::firstOrFail();
    $post = Post::firstOrFail();

    $this->get(route('services.show', $service))->assertOk()->assertSee($service->title);
    $this->get(route('projects.show', $project))->assertOk()->assertSee($project->title);
    $this->get(route('posts.show', $post))->assertOk()->assertSee($post->title);
});

test('sitemap and robots are available', function () {
    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee(route('home'), false)
        ->assertSee(route('service-areas'), false)
        ->assertSee(route('projects.index'), false);

    $this->get('/robots.txt')
        ->assertOk()
        ->assertSee('Disallow: /admin')
        ->assertSee('Sitemap: '.route('sitemap'));
});

test('unknown pages return a 404', function () {
    $this->get('/not-a-real-page')->assertNotFound();
});

test('the homepage exposes structured data and social meta tags', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('application/ld+json', false)
        ->assertSee('HousePainter', false)
        ->assertSee('og:image', false);
});
