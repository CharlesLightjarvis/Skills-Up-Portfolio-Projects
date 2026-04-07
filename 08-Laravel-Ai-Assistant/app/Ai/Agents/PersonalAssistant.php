<?php

namespace App\Ai\Agents;

use App\Ai\Tools\CreateGitHubRepo;
use App\Ai\Tools\DeleteProject;
use App\Ai\Tools\DeployToCloud;
use App\Ai\Tools\GetLocalTime;
use App\Ai\Tools\ReadCalendar;
use App\Ai\Tools\ReadFile;
use App\Ai\Tools\RunShell;
use App\Ai\Tools\ScheduleTask;
use App\Ai\Tools\SendTelegram;
use App\Ai\Tools\WriteFile;
use Illuminate\Support\Facades\File;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\WebSearch;
use Stringable;

class PersonalAssistant implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function instructions(): Stringable|string
    {
        $soulPath = storage_path('app/soul.md');
        $soul = File::exists($soulPath) ? trim(File::get($soulPath)) : '';

        if (empty($soul)) {
            return "You are a new personal assistant that hasn't been set up yet. " .
                "Ask the user who they are, what they'd like you to help with, and how you should behave. " .
                "Once you have enough info, use the write_file tool to save their preferences to storage/app/soul.md as a markdown file. " .
                "Keep it concise and structured.";
        }

        return $soul . "\n\n## Capabilities\n" .
            "- You can schedule recurring daily tasks using the schedule_task tool.\n" .
            "- You can send messages via Telegram using the send_telegram tool.\n" .
            "- You can run shell commands (composer, artisan, git) using the run_shell tool.\n" .
            "- You can create GitHub repositories using the create_github_repo tool.\n" .
            "- You can deploy applications to Laravel Cloud using the deploy_to_cloud tool.";
    }

    /**
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            (new WebSearch)->location(city: 'Vienna', country: 'AT'),
            new GetLocalTime,
            new ReadCalendar,
            new ReadFile,
            new WriteFile,
            new ScheduleTask,
            new SendTelegram,
            new RunShell,
            new CreateGitHubRepo,
            new DeployToCloud,
            new DeleteProject,
        ];
    }
}
