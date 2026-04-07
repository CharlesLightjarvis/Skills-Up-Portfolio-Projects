<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class DeployToCloud implements Tool
{
    public function description(): Stringable|string
    {
        return 'Deploy an application to Laravel Cloud. Actions: "ship" (create app + deploy in one step), "deploy" (deploy existing app), "list" (show all apps), "status" (check deployment status).';
    }

    public function handle(Request $request): Stringable|string
    {
        $token = config('services.laravel_cloud.token');

        if (! $token) {
            return 'Laravel Cloud token not configured. Set LARAVEL_CLOUD_TOKEN in your .env file.';
        }

        $action = $request['action'];

        return match ($action) {
            'ship' => $this->ship($request, $token),
            'deploy' => $this->deploy($request, $token),
            'status' => $this->getStatus($request, $token),
            'list' => $this->listApplications($token),
            default => "Unknown action: {$action}. Use 'ship', 'deploy', 'status', or 'list'.",
        };
    }

    /**
     * Create a new application on Cloud and trigger first deployment.
     */
    private function ship(Request $request, string $token): string
    {
        $name = $request['name'];
        $repository = $request['repository'];
        $region = $request['region'] ?? 'us-east-1';

        // Step 1: Create the application
        $response = $this->api($token)->post('https://cloud.laravel.com/api/applications', [
            'name' => $name,
            'region' => $region,
            'repository' => $repository,
            'source_control_provider_type' => 'github',
        ]);

        if (! $response->successful()) {
            return 'Failed to create application: ' . $response->body();
        }

        $appId = $response->json('data.id');
        $appName = $response->json('data.attributes.name', $name);

        // Step 2: Get the default environment
        sleep(3); // Give Cloud a moment to create the environment

        $envResponse = $this->api($token)->get("https://cloud.laravel.com/api/applications/{$appId}/environments");

        if (! $envResponse->successful()) {
            return "Application '{$appName}' created (ID: {$appId}), but couldn't fetch environments yet. Try 'deploy' action in a moment with the application ID.";
        }

        $environments = $envResponse->json('data', []);

        if (empty($environments)) {
            return "Application '{$appName}' created (ID: {$appId}). Environment is still being set up. Try 'deploy' in a few seconds.";
        }

        $envId = $environments[0]['id'];
        $envName = $environments[0]['attributes']['name'] ?? 'production';

        // Step 3: Initiate deployment
        $deployResponse = $this->api($token)->post("https://cloud.laravel.com/api/environments/{$envId}/deployments");

        if (! $deployResponse->successful()) {
            return "Application '{$appName}' created. Environment '{$envName}' (ID: {$envId}) ready. But deployment failed: " . $deployResponse->body();
        }

        $status = $deployResponse->json('data.attributes.status', 'initiated');

        return "🚀 Application '{$appName}' created and deployment {$status}!\n\nApp ID: {$appId}\nEnvironment: {$envName} ({$envId})\nRegion: {$region}\n\nThe app is being deployed to Laravel Cloud now.";
    }

    /**
     * Deploy an existing application by app ID or environment ID.
     */
    private function deploy(Request $request, string $token): string
    {
        $environmentId = $request['environment_id'] ?? null;
        $applicationId = $request['application_id'] ?? null;

        // If only app ID given, find the default environment
        if (! $environmentId && $applicationId) {
            $envResponse = $this->api($token)->get("https://cloud.laravel.com/api/applications/{$applicationId}/environments");

            if (! $envResponse->successful()) {
                return 'Failed to fetch environments: ' . $envResponse->body();
            }

            $environments = $envResponse->json('data', []);

            if (empty($environments)) {
                return 'No environments found for this application.';
            }

            $environmentId = $environments[0]['id'];
        }

        if (! $environmentId) {
            return 'Please provide an environment_id or application_id.';
        }

        $response = $this->api($token)->post("https://cloud.laravel.com/api/environments/{$environmentId}/deployments");

        if (! $response->successful()) {
            return 'Failed to deploy: ' . $response->body();
        }

        $status = $response->json('data.attributes.status', 'initiated');

        return "🚀 Deployment {$status} for environment {$environmentId}.";
    }

    private function getStatus(Request $request, string $token): string
    {
        $environmentId = $request['environment_id'] ?? null;
        $applicationId = $request['application_id'] ?? null;

        if (! $environmentId && $applicationId) {
            $envResponse = $this->api($token)->get("https://cloud.laravel.com/api/applications/{$applicationId}/environments");
            $environments = $envResponse->json('data', []);
            $environmentId = $environments[0]['id'] ?? null;
        }

        if (! $environmentId) {
            return 'Please provide an environment_id or application_id.';
        }

        $response = $this->api($token)->get("https://cloud.laravel.com/api/environments/{$environmentId}/deployments");

        if (! $response->successful()) {
            return 'Failed to get status: ' . $response->body();
        }

        $deployments = $response->json('data', []);

        if (empty($deployments)) {
            return 'No deployments found.';
        }

        $latest = $deployments[0];
        $status = $latest['attributes']['status'] ?? 'unknown';
        $commit = $latest['attributes']['commit_message'] ?? '';

        return "Latest deployment: {$status} — \"{$commit}\"";
    }

    private function listApplications(string $token): string
    {
        $response = $this->api($token)->get('https://cloud.laravel.com/api/applications', [
            'include' => 'environments',
        ]);

        if (! $response->successful()) {
            return 'Failed to list applications: ' . $response->body();
        }

        $apps = $response->json('data', []);

        if (empty($apps)) {
            return 'No applications found on Laravel Cloud.';
        }

        $list = collect($apps)->map(function ($app) {
            $name = $app['attributes']['name'];
            $region = $app['attributes']['region'];

            return "- {$name} ({$region}) [ID: {$app['id']}]";
        })->implode("\n");

        return "Applications on Laravel Cloud:\n{$list}";
    }

    private function api(string $token)
    {
        return Http::withToken($token)->accept('application/json');
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'action' => $schema->string()->required(),
            'name' => $schema->string(),
            'repository' => $schema->string(),
            'region' => $schema->string(),
            'application_id' => $schema->string(),
            'environment_id' => $schema->string(),
        ];
    }
}
