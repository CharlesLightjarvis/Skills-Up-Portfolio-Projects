<?php

namespace App\Console\Commands;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSpecificationPDFCommand extends Command
{
    protected $signature = 'pdf:cahier-charge 
                            {--path=cahiers/cahier-des-charges-gestion-immobiliere.pdf : Chemin relatif dans storage/app}';

    protected $description = 'Génère le cahier des charges fonctionnel en PDF dans le storage local';

    public function handle(): int
    {
        $relativePath = ltrim($this->option('path'), '/');
        $directory = dirname($relativePath);

        Storage::disk('local')->makeDirectory($directory);

        $absolutePath = Storage::disk('local')->path($relativePath);

        Pdf::setOption([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
        ]);

        $pdf = Pdf::loadHTML($this->html())
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        $pdf->save($absolutePath);

        $this->info('PDF généré avec succès.');
        $this->line('Chemin storage : storage/app/'.$relativePath);
        $this->line('Chemin absolu : '.$absolutePath);

        return self::SUCCESS;
    }

    private function html(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cahier des charges fonctionnel</title>

    <style>
        @page {
            margin: 42px 46px 58px 46px;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            color: #1f2933;
            font-size: 12px;
            line-height: 1.55;
            background: #ffffff;
        }

        .page-footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -34px;
            height: 28px;
            border-top: 1px solid #d7dde5;
            font-size: 9px;
            color: #7b8794;
            text-align: center;
            padding-top: 8px;
        }

        .cover {
            text-align: center;
            padding: 18px 0 14px;
            margin-bottom: 22px;
            border-bottom: 3px solid #1f4e79;
        }

        .cover .label {
            display: inline-block;
            padding: 5px 12px;
            margin-bottom: 12px;
            background: #eaf2f8;
            color: #1f4e79;
            border-radius: 4px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: bold;
        }

        h1 {
            margin: 0;
            color: #12344d;
            font-size: 23px;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .subtitle {
            margin-top: 9px;
            font-size: 13px;
            color: #52616f;
        }

        .meta-box {
            margin: 18px 0 22px;
            border: 1px solid #d7dde5;
            border-radius: 8px;
            overflow: hidden;
        }

        .meta-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #e3e8ef;
        }

        .meta-row:last-child {
            border-bottom: none;
        }

        .meta-label,
        .meta-value {
            display: table-cell;
            padding: 10px 12px;
            vertical-align: middle;
        }

        .meta-label {
            width: 34%;
            background: #f4f7fa;
            color: #12344d;
            font-weight: bold;
        }

        .meta-value {
            color: #34495e;
        }

        .intro {
            margin: 0 0 22px;
            padding: 14px 16px;
            background: #f8fafc;
            border-left: 4px solid #1f4e79;
            border-radius: 4px;
            text-align: justify;
        }

        h2 {
            margin: 22px 0 9px;
            padding-bottom: 5px;
            color: #12344d;
            font-size: 15px;
            border-bottom: 1px solid #cbd5e1;
        }

        h3 {
            margin: 15px 0 6px;
            color: #1f4e79;
            font-size: 13px;
        }

        ul {
            margin: 6px 0 12px 18px;
            padding: 0;
        }

        li {
            margin-bottom: 5px;
        }

        .section {
            page-break-inside: avoid;
        }

        .note {
            margin-top: 8px;
            padding: 10px 12px;
            background: #fff8e6;
            border-left: 4px solid #f0b429;
            border-radius: 4px;
            color: #4f3b00;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 14px;
            font-size: 11px;
        }

        th {
            background: #1f4e79;
            color: #ffffff;
            padding: 9px 8px;
            text-align: left;
            border: 1px solid #1f4e79;
        }

        td {
            padding: 9px 8px;
            border: 1px solid #d7dde5;
            vertical-align: top;
        }

        tr:nth-child(even) td {
            background: #f8fafc;
        }

        .signature-block {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .signature-table td {
            height: 70px;
            width: 50%;
        }

        .small-muted {
            color: #7b8794;
            font-size: 10px;
        }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 12px;
            background: #edf2f7;
            color: #1f4e79;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="page-footer">
        Cahier des charges fonctionnel — Plateforme web de gestion immobilière — MVP
    </div>

    <div class="cover">
        <div class="label">Document fonctionnel</div>
        <h1>Cahier des charges fonctionnel</h1>
        <div class="subtitle">
            Projet de plateforme web de gestion immobilière — version initiale MVP
        </div>
    </div>

    <div class="meta-box">
        <div class="meta-row">
            <div class="meta-label">Contexte</div>
            <div class="meta-value">Digitalisation de la gestion de biens immobiliers à Lomé</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Utilisateur principal</div>
            <div class="meta-value">Propriétaire / administrateur</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Type de solution</div>
            <div class="meta-value">Application web responsive</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Version visée</div>
            <div class="meta-value"><span class="badge">MVP</span> première mise en service</div>
        </div>
    </div>

    <div class="intro">
        Le présent cahier des charges décrit les besoins fonctionnels d’une première version d’une plateforme
        de gestion immobilière destinée à un propriétaire privé. L’objectif est de disposer d’un outil simple,
        fiable et évolutif pour suivre les biens, les locataires, les contrats et les loyers, sans alourdir
        le lancement par des fonctions trop complexes.
    </div>

    <div class="section">
        <h2>1. Objectifs du projet</h2>
        <ul>
            <li>Centraliser les informations liées aux biens immobiliers.</li>
            <li>Suivre les locataires et les contrats de location en cours.</li>
            <li>Enregistrer les paiements de loyers et identifier les impayés.</li>
            <li>Disposer d’un tableau de bord simple pour piloter l’activité.</li>
            <li>Préparer une base évolutive pour de futures améliorations.</li>
        </ul>
    </div>

    <div class="section">
        <h2>2. Profil utilisateur visé</h2>
        <ul>
            <li>Administrateur principal : le propriétaire.</li>
            <li>Aucun espace locataire dans la version initiale.</li>
            <li>Aucun module multi-agents ou multi-propriétaires dans la version initiale.</li>
        </ul>
    </div>

    <div class="section">
        <h2>3. Modules fonctionnels attendus</h2>

        <h3>3.1 Authentification et sécurité</h3>
        <ul>
            <li>Connexion via identifiant et mot de passe.</li>
            <li>Accès protégé à l’espace administrateur.</li>
            <li>Possibilité de modification du mot de passe.</li>
        </ul>

        <h3>3.2 Gestion des biens</h3>
        <ul>
            <li>Création, modification et consultation d’un bien.</li>
            <li>Informations minimales : type, localisation, loyer, statut, observations.</li>
            <li>Statuts suggérés : occupé, libre, en maintenance.</li>
        </ul>

        <h3>3.3 Gestion des locataires</h3>
        <ul>
            <li>Création d’une fiche locataire.</li>
            <li>Données prévues : nom, téléphone, adresse e-mail, pièce d’identité, personne à contacter si besoin.</li>
            <li>Association d’un locataire à un bien loué.</li>
        </ul>

        <h3>3.4 Gestion des contrats</h3>
        <ul>
            <li>Création et suivi d’un contrat de location.</li>
            <li>Informations prévues : date de début, date de fin, montant du loyer, caution, périodicité.</li>
            <li>Import ou archivage d’un contrat en PDF.</li>
        </ul>

        <h3>3.5 Paiements et loyers</h3>
        <ul>
            <li>Génération ou suivi des échéances de loyer.</li>
            <li>Enregistrement manuel des paiements.</li>
            <li>Statuts de paiement : payé, partiel, impayé.</li>
            <li>Historique des paiements par locataire et par bien.</li>
            <li>Rappels automatiques pour les loyers à échéance proche ou en retard.</li>
        </ul>

        <h3>3.6 Tableau de bord</h3>
        <ul>
            <li>Nombre total de biens.</li>
            <li>Biens occupés / libres.</li>
            <li>Loyers attendus sur la période.</li>
            <li>Montants encaissés et impayés.</li>
            <li>Alertes simples sur contrats expirant bientôt et loyers en retard.</li>
        </ul>

        <h3>3.7 Documents et impressions</h3>
        <ul>
            <li>Édition d’un reçu simple de paiement.</li>
            <li>Export PDF de listes ou fiches de base.</li>
        </ul>
    </div>

    <div class="section">
        <h2>4. Exigences non fonctionnelles</h2>
        <ul>
            <li>Interface claire, sobre et facile à prendre en main.</li>
            <li>Compatibilité avec les écrans d’ordinateur et de smartphone.</li>
            <li>Temps de réponse correct sur une connexion internet standard.</li>
            <li>Sauvegarde raisonnable des données et accès sécurisé.</li>
            <li>Architecture permettant l’ajout de nouveaux modules par la suite.</li>
        </ul>
    </div>

    <div class="section">
        <h2>5. Hors périmètre de la version initiale</h2>
        <ul>
            <li>Application mobile native Android / iOS.</li>
            <li>Intégration Mobile Money ou passerelle de paiement.</li>
            <li>Espace locataire avec accès individuel.</li>
            <li>Comptabilité avancée, rapports financiers détaillés ou gestion fiscale.</li>
            <li>Signature électronique et workflow de validation complexe.</li>
            <li>Gestion de maintenance avancée avec tickets et affectation techniciens.</li>
        </ul>
    </div>

    <div class="section">
        <h2>6. Livrables attendus</h2>
        <ul>
            <li>Application web fonctionnelle déployée sur un hébergement.</li>
            <li>Base de données configurée.</li>
            <li>Code source du projet.</li>
            <li>Documentation d’utilisation courte pour l’administrateur.</li>
            <li>Période de maintenance initiale selon l’offre validée.</li>
        </ul>
    </div>

    <div class="section">
        <h2>7. Planning indicatif</h2>

        <table>
            <thead>
                <tr>
                    <th>Phase</th>
                    <th>Contenu</th>
                    <th>Durée estimative</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Cadrage</strong></td>
                    <td>Validation du besoin, ajustements du périmètre et structure des données.</td>
                    <td>1 à 2 jours</td>
                </tr>
                <tr>
                    <td><strong>Conception</strong></td>
                    <td>Maquettage simple, architecture et base de données.</td>
                    <td>5 jours</td>
                </tr>
                <tr>
                    <td><strong>Développement</strong></td>
                    <td>Réalisation des modules retenus et intégration.</td>
                    <td>20 jours</td>
                </tr>
                <tr>
                    <td><strong>Tests & livraison</strong></td>
                    <td>Recette, corrections finales et mise en production.</td>
                    <td>5 jours</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>8. Évolutions possibles en phase 2</h2>
        <ul>
            <li>Intégration de paiement Mobile Money.</li>
            <li>Multi-utilisateurs avec rôles.</li>
            <li>Portail locataire.</li>
            <li>Statistiques et reporting avancés.</li>
        </ul>
    </div>

    <div class="note">
        Ce cahier des charges correspond à une version initiale MVP. Les modules avancés pourront être intégrés
        progressivement après validation de la première mise en service.
    </div>

    <div class="signature-block">
        <h2>Validation</h2>

        <table class="signature-table">
            <tr>
                <td>
                    <strong>Client / Propriétaire</strong><br>
                    <span class="small-muted">Nom, date et signature</span>
                </td>
                <td>
                    <strong>Prestataire</strong><br>
                    <span class="small-muted">Nom, date et signature</span>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
HTML;
    }
}
