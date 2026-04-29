<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class SpecificationRedactorAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a helpful assistant.';
    }

    // public function instructions(): Stringable|string
    // {
    //     return match (true) {
    //         ($this->user->age <= 15) => 'You are a helpful assistant for teens. You basically only speak in acronyms and slang.',
    //         ($this->user->age <= 25) => 'You are a helpful assistant for young adults. Be casual and cool.',
    //         default => 'You are a helpful assistant for older adults. Be professional and concise.',
    //     };
    // }

    //     public function instructions(): Stringable|string
    // {
    //     return <<<'MD'
    //         You are an agent that finds potential leads from our Laravel Cloud contact form.

    //         You should find any potential leads and report them to the user using the defined schema.

    //         We are prioritizing high-growth start-ups as well as established mid-market companies.

    //         We are not currently taking government leads.
    //     MD;
    // }

    // public function instructions(): Stringable|string
    // {
    //     return <<<'MD'
    //         <role>
    //         Tu es un agent de qualification commerciale.
    //         </role>

    //         <mission>
    //         Identifier les prospects potentiels depuis les messages du formulaire Laravel Cloud.
    //         </mission>

    //         <priorites>
    //         - Startups à forte croissance
    //         - Entreprises mid-market
    //         - Exclure les demandes gouvernementales
    //         </priorites>

    //         <contraintes>
    //         Tu dois toujours répondre avec le schéma défini.
    //         Ne devine pas les informations manquantes.
    //         </contraintes>
    //     MD;
    // }

    // DANS TOOLS
    // public function handle(Request $request): Stringable|string
    // {
    //     return match ($request['customer_name']) {
    //         'Taylor Otwell' => 'Taylor is spending $1,200 / month on Laravel Cloud.',
    //         'Jason Beggs' => 'Jason is spending $2,400 / month on Laravel Cloud.',
    //         'Caleb Porzio' => 'Jason is spending $3,600 / month on Laravel Cloud.',
    //         default => 'No spending information found for the given customer',
    //     };
    // }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }

    // public function tools(): iterable
    // {
    //     return [
    //         // new CloudSpend,

    //         SimilaritySearch::usingModel(Lead::class, 'embedding', minSimilarity: 0.1, limit: 5)
    //             ->withDescription(<<<'MD'
    //                 This tool can be used to search for relevant Cloud leads using a semantic search query.
    //             MD),
    //     ];
    // }

    public function middleware(): array
    {
        return [];
    }
}
