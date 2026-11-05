<?php

use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Project;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;

beforeEach(function () {
    $this->seed();
    $this->admin = User::where('is_admin', true)->firstOrFail();
});

test('guests are redirected from the admin panel', function () {
    $this->get('/admin')->assertRedirect(route('admin.login'));
    $this->get('/admin/projects')->assertRedirect(route('admin.login'));
});

test('an admin can sign in and out', function () {
    $this->post(route('admin.login.store'), [
        'email' => $this->admin->email,
        'password' => env('ADMIN_PASSWORD', 'LionPainters2026!'),
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($this->admin);

    $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
});

test('admin pages render', function () {
    $service = Service::firstOrFail();
    $project = Project::firstOrFail();
    $post = Post::firstOrFail();
    $testimonial = Testimonial::firstOrFail();
    $faq = Faq::firstOrFail();
    $message = ContactMessage::firstOrFail();
    $quote = QuoteRequest::firstOrFail();

    $this->actingAs($this->admin);

    $paths = [
        '/admin',
        '/admin/services',
        '/admin/services/create',
        '/admin/services/'.$service->slug.'/edit',
        '/admin/projects',
        '/admin/projects/create',
        '/admin/projects/'.$project->slug.'/edit',
        '/admin/posts',
        '/admin/posts/create',
        '/admin/posts/'.$post->slug.'/edit',
        '/admin/testimonials',
        '/admin/testimonials/'.$testimonial->id.'/edit',
        '/admin/faqs',
        '/admin/faqs/'.$faq->id.'/edit',
        '/admin/messages',
        '/admin/messages/'.$message->id,
        '/admin/quotes',
        '/admin/quotes/'.$quote->id,
        '/admin/settings',
    ];

    foreach ($paths as $path) {
        $this->get($path)->assertOk();
    }
});

test('an admin can create a project', function () {
    $this->actingAs($this->admin);

    $this->post(route('admin.projects.store'), [
        'title' => 'Test Balwyn Residence',
        'category' => 'Residential',
        'suburb' => 'Balwyn',
        'year' => '2026',
        'colour' => 'Dulux Natural White',
        'summary' => 'A test project summary for the Balwyn residence.',
        'description' => '<p>A full interior repaint with careful preparation.</p>',
        'gallery' => "images/hero.svg\nimages/after.svg",
        'sort_order' => 9,
        'is_published' => '1',
        'is_featured' => '1',
    ])->assertRedirect(route('admin.projects.index'));

    $project = Project::where('slug', 'test-balwyn-residence')->firstOrFail();

    expect($project->gallery)->toHaveCount(2)
        ->and($project->is_published)->toBeTrue()
        ->and($project->is_featured)->toBeTrue();

    $this->get(route('projects.show', $project))->assertOk()->assertSee('Test Balwyn Residence');
});

test('an admin can update a service', function () {
    $this->actingAs($this->admin);
    $service = Service::firstOrFail();

    $this->put(route('admin.services.update', $service), [
        'title' => 'Interior Painting & Decorating',
        'slug' => $service->slug,
        'icon' => 'brush',
        'tagline' => 'Updated tagline',
        'excerpt' => $service->excerpt,
        'description' => $service->description,
        'features' => "Walls\nCeilings",
        'sort_order' => 1,
        'is_active' => '1',
        'is_featured' => '1',
    ])->assertRedirect(route('admin.services.index'));

    $service->refresh();

    expect($service->title)->toBe('Interior Painting & Decorating')
        ->and($service->features)->toHaveCount(2);
});

test('an admin can create a testimonial and a faq', function () {
    $this->actingAs($this->admin);

    $this->post(route('admin.testimonials.store'), [
        'name' => 'Test Client',
        'suburb' => 'Armadale',
        'project_type' => 'Interior',
        'rating' => 5,
        'quote' => 'A wonderful, careful team from start to finish.',
        'sort_order' => 20,
        'is_published' => '1',
    ])->assertRedirect();

    $this->post(route('admin.faqs.store'), [
        'question' => 'Do you provide a written quote?',
        'answer' => 'Yes, every quote is fixed-price and provided in writing.',
        'sort_order' => 20,
        'is_published' => '1',
    ])->assertRedirect();

    expect(Testimonial::where('name', 'Test Client')->exists())->toBeTrue()
        ->and(Faq::where('question', 'Do you provide a written quote?')->exists())->toBeTrue();
});

test('an admin can create a journal post', function () {
    $this->actingAs($this->admin);

    $this->post(route('admin.posts.store'), [
        'title' => 'Test Journal Entry',
        'category' => 'Advice',
        'excerpt' => 'A short excerpt for the test journal entry.',
        'body' => '<p>Some practical painting advice.</p>',
        'author' => 'Lion Painters',
        'reading_time' => 4,
        'is_published' => '1',
    ])->assertRedirect(route('admin.posts.index'));

    $post = Post::where('slug', 'test-journal-entry')->firstOrFail();

    expect($post->is_published)->toBeTrue()
        ->and($post->published_at)->not->toBeNull();

    $this->get(route('posts.show', $post))->assertOk()->assertSee('Test Journal Entry');
});

test('an admin can update a quote request status and notes', function () {
    $this->actingAs($this->admin);
    $quote = QuoteRequest::firstOrFail();

    $this->patch(route('admin.quotes.update', $quote), [
        'status' => 'won',
        'admin_notes' => 'Deposit received.',
    ])->assertRedirect();

    $quote->refresh();

    expect($quote->status)->toBe('won')
        ->and($quote->admin_notes)->toBe('Deposit received.');
});

test('an admin can toggle a message as read', function () {
    $this->actingAs($this->admin);
    $message = ContactMessage::firstOrFail();

    $original = $message->is_read;

    $this->patch(route('admin.messages.read', $message))->assertRedirect();

    expect($message->refresh()->is_read)->not->toBe($original);
});

test('an admin can update site settings', function () {
    $this->actingAs($this->admin);

    $this->put(route('admin.settings.update'), [
        'brand' => 'Lion Painters Melbourne',
        'tagline' => 'Excellence in every stroke.',
        'phone' => '03 9000 0000',
        'email' => 'hello@lionpainters.com.au',
        'meta_title' => 'Lion Painters | Melbourne Painting',
        'meta_description' => 'Premium painting services across Melbourne.',
    ])->assertRedirect();

    expect(setting('brand'))->toBe('Lion Painters Melbourne');

    $this->get('/')->assertOk()->assertSee('Lion Painters Melbourne');
});
