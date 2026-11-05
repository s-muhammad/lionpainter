<?php

use App\Models\ContactMessage;
use App\Models\QuoteRequest;

beforeEach(function () {
    $this->seed();
});

test('a contact message is stored', function () {
    $response = $this->from('/contact')->post('/contact', [
        'name' => 'Test Visitor',
        'email' => 'visitor@example.com',
        'phone' => '0400 000 000',
        'suburb' => 'Richmond',
        'subject' => 'Kitchen repaint',
        'message' => 'We would like a quote for our kitchen and hallway.',
    ]);

    $response->assertRedirect('/contact');
    $response->assertSessionHas('success');

    expect(ContactMessage::where('email', 'visitor@example.com')->exists())->toBeTrue();
});

test('contact details are validated', function () {
    $this->post('/contact', [])
        ->assertSessionHasErrors(['name', 'email', 'message']);

    expect(ContactMessage::count())->toBe(2);
});

test('the contact honeypot rejects spam', function () {
    $this->post('/contact', [
        'name' => 'Spam Bot',
        'email' => 'spam@example.com',
        'message' => 'Buy cheap followers now and forever.',
        'website' => 'https://spam.example.com',
    ])->assertStatus(422);
});

test('a quote request is stored', function () {
    $response = $this->post('/quote', [
        'name' => 'Test Homeowner',
        'email' => 'homeowner@example.com',
        'phone' => '0411 111 111',
        'suburb' => 'Kew',
        'service' => 'Interior Painting',
        'property_type' => 'House',
        'bedrooms' => '3',
        'timeline' => 'Within 1–3 months',
        'details' => 'Living areas and bedrooms.',
    ]);

    $response->assertRedirect('/quote');
    $response->assertSessionHas('quote_success', true);

    expect(QuoteRequest::where('email', 'homeowner@example.com')->exists())->toBeTrue();
});

test('quote requests require the essential fields', function () {
    $this->post('/quote', [])
        ->assertSessionHasErrors(['name', 'email', 'phone', 'suburb', 'service']);

    expect(QuoteRequest::count())->toBe(3);
});
