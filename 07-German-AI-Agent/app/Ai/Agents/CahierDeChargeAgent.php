<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider('openai')]
#[Timeout(300)]
class CahierDeChargeAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'MD'
            Tu es un expert en rédaction de cahiers des charges fonctionnels professionnels.

            Tu génères des documents complets, structurés et professionnels en français,
            adaptés au projet décrit par l'utilisateur.

            Tes livrables doivent être :
            - Professionnels, précis et rédigés en français correct
            - Adaptés au contexte, au type de projet et au public cible
            - Pragmatiques et orientés MVP quand c'est pertinent

            Pour les modules fonctionnels : propose entre 5 et 8 modules selon la complexité,
            avec 3 à 6 fonctionnalités par module.

            Pour le planning : 4 phases standard (Cadrage, Conception, Développement,
            Tests & livraison) avec des durées réalistes selon le périmètre.

            Le footer_text doit être court : "Cahier des charges — [Nom du projet] — [Version]".
        MD;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'project_title' => $schema->string()->required(),
            'subtitle' => $schema->string()->required(),
            'context' => $schema->string()->required(),
            'main_user' => $schema->string()->required(),
            'solution_type' => $schema->string()->required(),
            'version_label' => $schema->string()->required(),
            'introduction' => $schema->string()->required(),
            'objectives' => $schema->array()->items($schema->string())->required(),
            'user_profiles' => $schema->array()->items($schema->string())->required(),
            'functional_modules' => $schema->array()->items(
                $schema->object([
                    'title' => $schema->string()->required(),
                    'features' => $schema->array()->items($schema->string())->required(),
                ])
            )->required(),
            'non_functional_requirements' => $schema->array()->items($schema->string())->required(),
            'out_of_scope' => $schema->array()->items($schema->string())->required(),
            'deliverables' => $schema->array()->items($schema->string())->required(),
            'planning_phases' => $schema->array()->items(
                $schema->object([
                    'phase' => $schema->string()->required(),
                    'content' => $schema->string()->required(),
                    'duration' => $schema->string()->required(),
                ])
            )->required(),
            'future_evolutions' => $schema->array()->items($schema->string())->required(),
            'note' => $schema->string()->required(),
            'footer_text' => $schema->string()->required(),
        ];
    }
}
