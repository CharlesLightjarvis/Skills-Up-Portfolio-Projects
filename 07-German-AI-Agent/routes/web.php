<?php

use Illuminate\Support\Facades\Route;
use Laravel\Ai\Image;
use Laravel\Ai\Responses\ImageResponse;

Route::get('/', function () {

    // == BASIC USAGE ANONYMOUSAGENT ==
    // $response = agent(
    //     instructions: "You are a helpful assistant. You will help the user with their tasks."
    // )->prompt('Who is Lefa');

    // dd($response);

    // == GIVING PROVIDER AND MODEL ==
    //   $response = agent(
    //     instructions: "You are a helpful assistant. You will help the user with their tasks."
    // )->prompt('which model are you ? ', provider: 'openai', model: 'gpt-5.4');

    // dd($response);

    // == GIVING ARRAY OF PROVIDERS AND MODEL FOR FALLBACK ==
    //   $response = agent(
    //     instructions: "You are a helpful assistant. You will help the user with their tasks."
    // )->prompt('which model are you ? ', provider: ['openai', 'gemini']);

    // dd($response);

    // == ANONYMOUSAGENT WITH STRUCTURED OUTPUT ==
    // $response = agent(
    //     instructions: 'You are an anime critic',
    //     schema: fn (JsonSchema $schema) => [
    //         'movie' => $schema->string()->required(),
    //         'rating' => $schema->integer()->min(1)->max(10)->required(),
    //         'reasoning' => $schema->string()->description('One sentence reason for your rating')->required(),
    //         'llms_could_do_better' => $schema->boolean()->description('True if the LLM could have done a better job')->required(),
    //     ]
    // )->prompt('rate the anime death note');

    // dd($response->text);

    // == QUEUE AGENT ==
    // $response = agent()
    //     ->queue('Who is Lefa')
    //     ->then(function (AgentResponse $response) {
    //         logger('Agent Response', [(string) $response]);
    //     });
    // return 'agent queued';

    // == STEAM RESPONSE ==
    // return agent()
    //     ->stream('Who is Lefa')
    //     ->then(function (AgentResponse $response) {
    //         logger('Agent Response', [(string) $response]);
    //     });

    // == GENERATING IMAGE ==
    // Image::of('A donut sitting on the kitchen counter')
    // ->portrait()
    // ->queue()
    // ->then(function (ImageResponse $image) {
    //      $image->storePubliclyAs('image.jpg');
    // });
    // return "generated";

    // });

    // Route::get('/files', function () {
    //     // Store a file...

    //     $file = Files\Document::fromStorage('leads.csv')->put();

    //     $file = Files\Document::fromPath('/path/to/document.pdf')->put();

    //     $file = Files\Document::fromUrl('https://example.com/doc.pdf')->put();

    //     $file = Files\Document::fromUpload($request->file('document'))->put();

    //     dd($file);
});
