<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Testimonial;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class DashboardMetrics
{
    public function summary(): array
    {
        return [
            'new_quotes' => QuoteRequest::new()->count(),
            'quotes_total' => QuoteRequest::count(),
            'unread_messages' => ContactMessage::unread()->count(),
            'projects' => Project::published()->count(),
            'services' => Service::active()->count(),
            'posts' => Post::published()->count(),
            'testimonials' => Testimonial::published()->count(),
            'quotes_this_month' => QuoteRequest::where('created_at', '>=', now()->startOfMonth())->count(),
        ];
    }

    /**
     * Quote requests for the last 7 days, ready for the dashboard chart.
     *
     * @return Collection<int, array{label: string, value: int, percent: int}>
     */
    public function weeklyQuotes(): Collection
    {
        $days = collect(range(6, 0))->map(fn (int $offset) => CarbonImmutable::today()->subDays($offset));

        $counts = QuoteRequest::where('created_at', '>=', $days->first())
            ->get()
            ->groupBy(fn (QuoteRequest $quote) => $quote->created_at->toDateString())
            ->map->count();

        $max = max(1, $counts->max() ?: 1);

        return $days->map(fn (CarbonImmutable $day) => [
            'label' => $day->locale('fa')->translatedFormat('D'),
            'value' => $counts->get($day->toDateString(), 0),
            'percent' => (int) round(($counts->get($day->toDateString(), 0) / $max) * 100),
        ]);
    }
}
