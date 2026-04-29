<?php

namespace App\Jobs;

use App\Ai\Agents\CahierDeChargeAgent;
use App\Services\CahierDeChargePdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Responses\StructuredAgentResponse;
use Throwable;

class GenerateCahierDeChargeJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 300;

    public function __construct(
        public readonly string $jobId,
        public readonly string $prompt,
        public readonly string $filename,
    ) {}

    public function handle(CahierDeChargePdfService $pdfService): void
    {
        /** @var StructuredAgentResponse $specification */
        $specification = (new CahierDeChargeAgent)->prompt($this->prompt);

        $pdf = $pdfService->generate($specification->toArray());

        Storage::disk('local')->put("cahiers/{$this->jobId}.pdf", $pdf->output());

        Cache::put("cahier_de_charge.{$this->jobId}", 'completed', now()->addHour());
    }

    public function failed(Throwable $exception): void
    {
        Cache::put("cahier_de_charge.{$this->jobId}", 'failed', now()->addHour());
    }
}
