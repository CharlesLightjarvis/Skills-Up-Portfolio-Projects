<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $project_title }}</title>

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
        {{ $footer_text }}
    </div>

    <div class="cover">
        <div class="label">Document fonctionnel</div>
        <h1>{{ $project_title }}</h1>
        <div class="subtitle">{{ $subtitle }}</div>
    </div>

    <div class="meta-box">
        <div class="meta-row">
            <div class="meta-label">Contexte</div>
            <div class="meta-value">{{ $context }}</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Utilisateur principal</div>
            <div class="meta-value">{{ $main_user }}</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Type de solution</div>
            <div class="meta-value">{{ $solution_type }}</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Version visée</div>
            <div class="meta-value"><span class="badge">{{ $version_label }}</span></div>
        </div>
    </div>

    <div class="intro">
        {{ $introduction }}
    </div>

    <div class="section">
        <h2>1. Objectifs du projet</h2>
        <ul>
            @foreach ($objectives as $objective)
                <li>{{ $objective }}</li>
            @endforeach
        </ul>
    </div>

    <div class="section">
        <h2>2. Profil utilisateur visé</h2>
        <ul>
            @foreach ($user_profiles as $profile)
                <li>{{ $profile }}</li>
            @endforeach
        </ul>
    </div>

    <div class="section">
        <h2>3. Modules fonctionnels attendus</h2>
        @foreach ($functional_modules as $index => $module)
            <h3>3.{{ $loop->index + 1 }} {{ $module['title'] }}</h3>
            <ul>
                @foreach ($module['features'] as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        @endforeach
    </div>

    <div class="section">
        <h2>4. Exigences non fonctionnelles</h2>
        <ul>
            @foreach ($non_functional_requirements as $requirement)
                <li>{{ $requirement }}</li>
            @endforeach
        </ul>
    </div>

    <div class="section">
        <h2>5. Hors périmètre de la version initiale</h2>
        <ul>
            @foreach ($out_of_scope as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    <div class="section">
        <h2>6. Livrables attendus</h2>
        <ul>
            @foreach ($deliverables as $deliverable)
                <li>{{ $deliverable }}</li>
            @endforeach
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
                @foreach ($planning_phases as $phase)
                    <tr>
                        <td><strong>{{ $phase['phase'] }}</strong></td>
                        <td>{{ $phase['content'] }}</td>
                        <td>{{ $phase['duration'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>8. Évolutions possibles en phase 2</h2>
        <ul>
            @foreach ($future_evolutions as $evolution)
                <li>{{ $evolution }}</li>
            @endforeach
        </ul>
    </div>

    <div class="note">
        {{ $note }}
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
