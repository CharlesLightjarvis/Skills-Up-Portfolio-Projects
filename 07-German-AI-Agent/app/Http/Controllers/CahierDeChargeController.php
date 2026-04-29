<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateCahierDeChargeRequest;
use App\Jobs\GenerateCahierDeChargeJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CahierDeChargeController extends Controller
{
    public function store(GenerateCahierDeChargeRequest $request): JsonResponse
    {
        $jobId = Str::uuid()->toString();
        $filename = Str::slug($request->validated('project_name')).'-cahier-des-charges.pdf';

        Cache::put("cahier_de_charge.{$jobId}", 'pending', now()->addHour());
        Cache::put("cahier_de_charge.{$jobId}.filename", $filename, now()->addHour());

        GenerateCahierDeChargeJob::dispatch($jobId, $request->buildPrompt(), $filename);

        return response()->json(['job_id' => $jobId], 202);
    }

    public function status(string $jobId): JsonResponse
    {
        $status = Cache::get("cahier_de_charge.{$jobId}");

        if ($status === null) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        return response()->json(['status' => $status]);
    }

    public function download(string $jobId): BinaryFileResponse|JsonResponse
    {
        if (Cache::get("cahier_de_charge.{$jobId}") !== 'completed') {
            return response()->json(['error' => 'PDF not ready'], 404);
        }

        $path = "cahiers/{$jobId}.pdf";

        abort_unless(Storage::disk('local')->exists($path), 404);

        $filename = Cache::get("cahier_de_charge.{$jobId}.filename", 'cahier-des-charges.pdf');

        return response()->download(
            Storage::disk('local')->path($path),
            $filename,
        )->deleteFileAfterSend(true);
    }
}
