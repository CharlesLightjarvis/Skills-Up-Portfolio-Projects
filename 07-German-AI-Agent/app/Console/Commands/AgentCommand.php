<?php

namespace App\Console\Commands;

use App\Ai\Agents\SalesCoach;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Laravel\Ai\Audio;
use Laravel\Ai\Image;
use Laravel\Ai\Transcription;

#[Signature('app:agent')]
#[Description('Command description')]
class AgentCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // PROMPT
        // $response = (new SalesCoach)
        //     ->prompt('cest quoi la capitale economique du cameroun ?');

        // return $this->info(
        //     response()->json($response)
        // );

        // IMAGES
        // $image = Image::of('genere moi une image de sangoku dbz en mode afro africain')->generate();

        // $path = $image->storeAs('sangoku.png');

        // $this->info("Image saved to: {$path}");

        // return self::SUCCESS;

        // TTS
        //   $audio = Audio::of('Kamehameha c\'est le cri de guerre de Sangoku')
        //     ->female()
        //     ->generate();

        // $path = $audio->storeAs('tts-sangoku.mp3');

        // $this->info("Audio généré : {$path}");

        // return self::SUCCESS;

        //STT
         $path = storage_path('app/private/tts-sangoku.mp3');

        if (! file_exists($path)) {
            $this->error("Fichier introuvable : {$path}");
            return self::FAILURE;
        }

        $transcript = Transcription::fromPath($path)->generate();

        $this->info('Transcription :');
        $this->line((string) $transcript);

        return self::SUCCESS;


    }
}
