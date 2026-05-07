import { useState } from 'react';
import { Head, Link, router, setLayoutProps, usePage } from '@inertiajs/react';
import type { Collection } from '@/types/collection';
import type { Link as LinkType } from '@/types/link';
import collections from '@/routes/collections';
import links from '@/routes/collections/links';
import LinkController from '@/actions/App/Http/Controllers/LinkController';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { X } from 'lucide-react';

function getXsrfToken(): string {
    return decodeURIComponent(
        document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '',
    );
}

type FetchedMeta = {
    url: string;
    title: string;
    description: string | null;
    image_url: string | null;
    site_name: string | null;
    domain: string | null;
};

export default function EditLink() {
    const { collection, link } = usePage<{
        collection: Collection;
        link: LinkType;
    }>().props;

    setLayoutProps({
        breadcrumbs: [
            { title: 'Collections', href: collections.index.url() },
            { title: collection.name, href: collections.show.url(collection) },
            { title: 'Modifier le lien', href: links.edit.url({ collection: collection.id, link: link.id }) },
        ],
    });

    const [url, setUrl] = useState(link.url);
    const [preview, setPreview] = useState<FetchedMeta>({
        url: link.url,
        title: link.title ?? link.url,
        description: link.description,
        image_url: link.image_url,
        site_name: link.site_name,
        domain: link.domain,
    });
    const [loading, setLoading] = useState(false);
    const [fetched, setFetched] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [submitting, setSubmitting] = useState(false);

    function handleUrlChange(value: string) {
        setUrl(value);
        setFetched(value === link.url);
        setError(null);
    }

    async function fetchPreview() {
        if (!url) return;
        setLoading(true);
        setError(null);

        try {
            const res = await fetch(LinkController.preview.url(), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-XSRF-TOKEN': getXsrfToken(),
                },
                body: JSON.stringify({ url }),
            });

            const data = await res.json();

            if (!res.ok) {
                setError(data.message ?? 'Impossible de récupérer cette page.');
                return;
            }

            setPreview({
                url: data.url ?? url,
                title: data.title ?? url,
                description: data.description ?? null,
                image_url: data.image ?? null,
                site_name: data.site_name ?? null,
                domain: data.domain ?? null,
            });
            setFetched(true);
        } catch {
            setError('Une erreur réseau est survenue.');
        } finally {
            setLoading(false);
        }
    }

    function handleSubmit() {
        if (!fetched) return;
        setSubmitting(true);
        router.patch(
            LinkController.update.url({ collection: collection.id, link: link.id }),
            {
                url: preview.url,
                title: preview.title,
                description: preview.description,
                image_url: preview.image_url,
                site_name: preview.site_name,
                domain: preview.domain,
            },
            {
                onSuccess: () => router.visit(collections.show.url(collection)),
                onFinish: () => setSubmitting(false),
            },
        );
    }

    const canSubmit = fetched && !loading && !submitting;

    return (
        <>
            <Head title="Modifier le lien" />
            <div className="container mx-auto max-w-xl space-y-6 px-8 py-4">
                <h1 className="text-2xl font-semibold">Modifier le lien</h1>

                <div className="space-y-5">
                    {/* URL input with verify */}
                    <div className="grid gap-2">
                        <Label>URL du lien</Label>
                        <div className="flex gap-2">
                            <Input
                                type="url"
                                placeholder="https://example.com"
                                value={url}
                                onChange={(e) => handleUrlChange(e.target.value)}
                                onKeyDown={(e) => e.key === 'Enter' && e.preventDefault()}
                            />
                            <Button
                                type="button"
                                variant="secondary"
                                onClick={fetchPreview}
                                disabled={!url || loading || url === link.url && fetched}
                                className="shrink-0"
                            >
                                {loading ? <Spinner /> : 'Vérifier'}
                            </Button>
                        </div>
                        {!fetched && url && (
                            <p className="text-sm text-amber-600 dark:text-amber-400">
                                L'URL a changé — vérifiez avant d'enregistrer.
                            </p>
                        )}
                        {error && <p className="text-sm text-destructive">{error}</p>}
                    </div>

                    {/* Preview card */}
                    {fetched && (
                        <div className="space-y-4">
                            <div className="flex items-start gap-3 rounded-md border p-3">
                                {preview.image_url && (
                                    <img
                                        src={preview.image_url}
                                        alt=""
                                        className="h-12 w-20 shrink-0 rounded object-cover"
                                    />
                                )}
                                <div className="min-w-0 flex-1">
                                    <p className="truncate text-sm font-medium">{preview.title}</p>
                                    <p className="truncate text-xs text-muted-foreground">
                                        {preview.domain}
                                    </p>
                                </div>
                            </div>

                            {/* Editable title */}
                            <div className="grid gap-2">
                                <Label htmlFor="title">Titre</Label>
                                <div className="flex gap-2">
                                    <Input
                                        id="title"
                                        value={preview.title}
                                        onChange={(e) =>
                                            setPreview((p) => ({ ...p, title: e.target.value }))
                                        }
                                    />
                                    <button
                                        type="button"
                                        onClick={() =>
                                            setPreview((p) => ({ ...p, title: link.title ?? link.url }))
                                        }
                                        className="shrink-0 text-muted-foreground hover:text-foreground"
                                        title="Réinitialiser"
                                    >
                                        <X className="h-4 w-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}

                    <div className="flex gap-2 pt-2">
                        <Button onClick={handleSubmit} disabled={!canSubmit}>
                            {submitting && <Spinner />}
                            Enregistrer
                        </Button>
                        <Button variant="outline" asChild>
                            <Link href={collections.show.url(collection)}>Annuler</Link>
                        </Button>
                    </div>
                </div>
            </div>
        </>
    );
}
