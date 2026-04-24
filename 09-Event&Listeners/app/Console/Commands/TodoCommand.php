<?php

namespace App\Console\Commands;

use App\Events\TodoCreated;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('todo {title} {priority} {due_at?}')]
#[Description('Command description')]
class TodoCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();
        $this->info($user->name);

        $todo = $user->todos()->create([
            'title'    => $this->argument('title'),
            'priority' => $this->argument('priority'),
            'due_at'   =>  $this->argument('due_at') ? now()->addDays((int) $this->argument('due_at')) : now()->addDays(2),
        ]);

        TodoCreated::dispatch($todo, $user);

        $this->info($todo->title . ' created successfully.');
    }
}
