<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GenerateCahierDeChargeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_name' => ['required', 'string', 'max:100'],
            'client_name' => ['required', 'string', 'max:100'],
            'project_type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:50', 'max:5000'],
        ];
    }

    public function buildPrompt(): string
    {
        $projectName = $this->validated('project_name');
        $clientName = $this->validated('client_name');
        $projectType = $this->validated('project_type');
        $description = $this->validated('description');

        return <<<MD
            Génère un cahier des charges fonctionnel complet pour le projet suivant :

            **Nom du projet :** {$projectName}
            **Client / Porteur du projet :** {$clientName}
            **Type de projet :** {$projectType}

            **Description du besoin :**
            {$description}

            Le project_title doit être le nom du projet.
            Le context doit mentionner le client et le contexte métier.
            Le footer_text doit suivre ce format : "Cahier des charges — {$projectName} — MVP".
        MD;
    }
}
