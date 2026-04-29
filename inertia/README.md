# Wayfinder

Wayfinder génère des fonctions TypeScript typées à partir des routes Laravel. Il évite les URLs hardcodées et fournit l'autocomplétion.

## Générer les routes

```bash
php artisan wayfinder:generate --with-form --no-interaction
```

> Relancer après chaque modification de `routes/web.php`.

## Imports

```ts
// Depuis un controller (tree-shaking)
import { store, update } from '@/actions/App/Http/Controllers/PostController';

// Depuis les routes nommées
import products, { store } from '@/routes/products';
```

## Méthodes disponibles

```ts
products.index(); // { url: "/products", method: "get" }
products.index.url(); // "/products"
store.post(); // { url: "/products", method: "post" }
store.form(); // { action: "/products", method: "post" }
```

## Utilisation avec `<Form>` Inertia

```tsx
import { Form } from '@inertiajs/react';
import { store } from '@/routes/products';

<Form {...store.form()}>
    {({ processing, errors }) => (
        <>
            <input name="name" /> {/* le `name` = clé dans $request */}
            <input name="price" />
            <button disabled={processing}>Submit</button>
        </>
    )}
</Form>;
```

## Flux complet

| Étape                  | Qui              | Quoi                                          |
| ---------------------- | ---------------- | --------------------------------------------- |
| `store.form()`         | Wayfinder        | Génère `action="/products" method="post"`     |
| Submit                 | `<Form>` Inertia | Collecte les inputs, envoie la requête XHR    |
| `$request->validate()` | Laravel          | Valide, renvoie les erreurs dans `{ errors }` |
| `name=""` HTML         | HTML             | C'est la clé dans `$request->input('name')`   |
| `to_route(...)`        | Laravel          | Redirige via Inertia sans reload              |

## Côté Laravel (`web.php`)

```php
Route::post('products', function (Request $request) {
    $validated = $request->validate([
        'name'  => ['required', 'string', 'max:255'],
        'price' => ['required', 'numeric', 'min:0'],
    ]);

    $request->user()->products()->create($validated);

    return to_route('products.index');
})->name('products.store');
```

query filters : avec un seul parametre search

1- ca marche mais quand on switch la page on perd le query

// avant : inertia.test/products?search=cosmetic
// apres : inertia.test/products?page=2

->withQueryString()
Ça garde ?search=... quand tu changes de page avec la pagination.

// deployment avec vercel
vercel.json
{
"$schema": "https://openapi.vercel.sh/vercel.json",
"outputDirectory": "public",
"functions": {
"api/index.php": {
"runtime": "vercel-php@0.7.4"
}
},
"rewrites": [
{
"source": "/(.*)",
"destination": "/api/index.php"
}
],
"buildCommand": "echo skip..."

}

api/index.php a la racine

<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());


//.vercelignore
public/index.php

//composer.json
 "vercel": [
            "npm run build",
            "mkdir -p /vercel/output/static",
            "cp -r public/build /vercel/output/static/",
            "php artisan migrate --force"
        ]


// bootstrap/app
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
        );
