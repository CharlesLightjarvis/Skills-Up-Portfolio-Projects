import { useState } from 'react';
import { Head, Link, router, setLayoutProps, usePage } from '@inertiajs/react';
import type { Collection } from '@/types/collection';
import collections from '@/routes/collections';
import collectionLinks from '@/routes/collections/links';
import LinkController from '@/actions/App/Http/Controllers/LinkController';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { X } from 'lucide-react';
import { LinkUrlFetcher, type FetchedLink } from '../components/link-url-fetcher';

export default function CreateLink() {
    const { collection } = usePage<{ collection: Collection }>().props;

    setLayoutProps({
        breadcrumbs: [
            { title: 'Collections', href: collections.index.url() },
            { title: collection.name, href: collections.show.url(collection) },
            { title: 'Ajouter des liens', href: collectionLinks.create.url(collection) },
        ],
    });

    const [links, setLinks] = useState<FetchedLink[]>([]);
    const [submitting, setSubmitting] = useState(false);

    function removeLink(index: number) {
        setLinks((prev) => prev.filter((_, i) => i !== index));
    }

    function handleSubmit() {
        if (links.length === 0) return;
        setSubmitting(true);
        router.post(
            LinkController.store.url(collection),
            { links },
            {
                onSuccess: () => router.visit(collections.show.url(collection)),
                onFinish: () => setSubmitting(false),
            },
        );
    }

    return (
        <>
            <Head title="Ajouter des liens" />
            <div className="container mx-auto max-w-xl space-y-6 px-8 py-4">
                <h1 className="text-2xl font-semibold">Ajouter des liens</h1>

                <div className="space-y-5">
                    <LinkUrlFetcher
                        onAdd={(link) => setLinks((prev) => [...prev, link])}
                    />

                    {links.length > 0 && (
                        <ul className="space-y-2">
                            {links.map((link, i) => (
                                <li
                                    key={i}
                                    className="flex items-center gap-2 rounded-md border px-3 py-2"
                                >
                                    {link.domain && (
                                        <img
                                            src={`https://www.google.com/s2/favicons?sz=32&domain=${link.domain}`}
                                            alt=""
                                            className="h-4 w-4 shrink-0"
                                        />
                                    )}
                                    <span className="min-w-0 flex-1 truncate text-sm">
                                        {link.title}
                                    </span>
                                    <span className="shrink-0 text-xs text-muted-foreground">
                                        {link.domain}
                                    </span>
                                    <button
                                        type="button"
                                        onClick={() => removeLink(i)}
                                        className="shrink-0 text-muted-foreground hover:text-destructive"
                                    >
                                        <X className="h-3.5 w-3.5" />
                                    </button>
                                </li>
                            ))}
                        </ul>
                    )}

                    <div className="flex gap-2 pt-2">
                        <Button
                            onClick={handleSubmit}
                            disabled={links.length === 0 || submitting}
                        >
                            {submitting && <Spinner />}
                            Enregistrer{links.length > 1 ? ` (${links.length})` : ''}
                        </Button>
                        <Button variant="outline" asChild>
                            <Link href={collections.show.url(collection)}>
                                Annuler
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>
        </>
    );
}
