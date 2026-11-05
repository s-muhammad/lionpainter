<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect()
            ->push(['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly']);

        foreach (['services.index', 'projects.index', 'about', 'service-areas', 'faqs', 'posts.index', 'contact', 'quote'] as $name) {
            $urls->push(['loc' => route($name), 'priority' => '0.8', 'changefreq' => 'monthly']);
        }

        Service::active()->ordered()->get()->each(function (Service $service) use ($urls) {
            $urls->push([
                'loc' => route('services.show', $service),
                'lastmod' => $service->updated_at?->toAtomString(),
                'priority' => '0.9',
                'changefreq' => 'monthly',
            ]);
        });

        Project::published()->ordered()->get()->each(function (Project $project) use ($urls) {
            $urls->push([
                'loc' => route('projects.show', $project),
                'lastmod' => $project->updated_at?->toAtomString(),
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ]);
        });

        Post::published()->latest('published_at')->get()->each(function (Post $post) use ($urls) {
            $urls->push([
                'loc' => route('posts.show', $post),
                'lastmod' => $post->updated_at?->toAtomString(),
                'priority' => '0.6',
                'changefreq' => 'monthly',
            ]);
        });

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /admin/',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
