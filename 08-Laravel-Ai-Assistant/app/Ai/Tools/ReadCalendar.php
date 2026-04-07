<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use om\IcalParser;
use Stringable;

class ReadCalendar implements Tool
{
    public function description(): Stringable|string
    {
        return 'Read upcoming events from the calendar.';
    }

    public function handle(Request $request): Stringable|string
    {
        $cal = new IcalParser;
        $cal->parseFile(storage_path('app/calendar.ics'));

        $events = collect($cal->getEvents()->sorted())
            ->filter(fn ($event) => $event['DTSTART'] >= now()->startOfDay() && $event['DTSTART'] <= now()->addDays(3))
            ->map(fn ($event) => sprintf('%s: %s', $event['DTSTART']->format('l, M j - H:i'), $event['SUMMARY']));

        return $events->isEmpty()
            ? 'No upcoming events in the next 3 days.'
            : $events->implode("\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
