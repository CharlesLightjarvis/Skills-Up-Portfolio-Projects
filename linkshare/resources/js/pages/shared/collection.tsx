import { Head, router, setLayoutProps, usePage } from '@inertiajs/react';
import { ExternalLink, Globe, Import } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinner';
import SharedCollectionController from '@/actions/App/Http/Controllers/SharedCollectionController';
import type { Collection } from '@/types/collection';
import type { Link } from '@/types/link';

type Share = {
    id: number;
};

type Owner = {
    id: number;
    name: string;
};

type PageProps = {
    share: Share;
    collection: Collection & { links: Link[] };
    owner: Owner;
};

export default function SharedCollection() {
    const { share, collection, owner } = usePage<PageProps>().props;
    const [confirming, setConfirming] = useState(false);
    const [importing, setImporting] = useState(false);

    setLayoutProps({
        breadcrumbs: [
            { title: 'Partagé avec moi', href: '#' },
            { title: collection.name, href: '#' },
        ],
    });

    function handleImport() {
        setImporting(true);
        router.post(
            SharedCollectionController.importMethod.url(share.id),
            {},
            {
                onFinish: () => setImporting(false),
                onError: () => setConfirming(false),
            },
        );
    }

    return (
        <>
            <Head title={`${collection.name} — partagé par ${owner.name}`} />

            <div className="container mx-auto space-y-6 px-8 py-4">
                <div className="flex items-start justify-between gap-4">
                    <div className="flex items-center gap-3">
                        {collection.color && (
                            <span
                                className="mt-1 h-4 w-4 shrink-0 rounded-full"
                                style={{ backgroundColor: collection.color }}
                            />
                        )}
                        <div>
                            <h1 className="text-2xl font-semibold">
                                {collection.name}
                            </h1>
                            <p className="text-sm text-muted-foreground">
                                Partagée par{' '}
                                <span className="font-medium text-foreground">
                                    {owner.name}
                                </span>
                            </p>
                            {collection.description && (
                                <p className="mt-1 text-sm text-muted-foreground">
                                    {collection.description}
                                </p>
                            )}
                        </div>
                    </div>

                    <Button onClick={() => setConfirming(true)} size="sm">
                        <Import className="h-4 w-4" />
                        Ajouter à mes collections
                    </Button>
                </div>

                {collection.links.length === 0 ? (
                    <p className="py-8 text-center text-sm text-muted-foreground">
                        Cette collection ne contient aucun lien.
                    </p>
                ) : (
                    <ul className="divide-y rounded-lg border">
                        {collection.links.map((link) => (
                            <li
                                key={link.id}
                                className="flex items-center gap-3 px-4 py-3"
                            >
                                <div className="flex h-5 w-5 shrink-0 items-center justify-center">
                                    {link.domain ? (
                                        <img
                                            src={`https://www.google.com/s2/favicons?sz=32&domain=${link.domain}`}
                                            alt=""
                                            className="h-4 w-4"
                                        />
                                    ) : (
                                        <Globe className="h-4 w-4 text-muted-foreground" />
                                    )}
                                </div>

                                <div className="min-w-0 flex-1">
                                    <a
                                        href={link.url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="block truncate text-sm font-medium hover:underline"
                                    >
                                        {link.title || link.url}
                                    </a>
                                    <p className="truncate text-xs text-muted-foreground">
                                        {link.url}
                                    </p>
                                </div>

                                <a
                                    href={link.url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="shrink-0 text-muted-foreground hover:text-foreground"
                                    aria-label="Ouvrir le lien"
                                >
                                    <ExternalLink className="h-4 w-4" />
                                </a>
                            </li>
                        ))}
                    </ul>
                )}
            </div>

            <Dialog open={confirming} onOpenChange={(o) => !o && setConfirming(false)}>
                <DialogContent className="sm:max-w-sm">
                    <DialogHeader>
                        <DialogTitle>Ajouter à mes collections</DialogTitle>
                        <DialogDescription>
                            La collection{' '}
                            <span className="font-medium text-foreground">
                                {collection.name}
                            </span>{' '}
                            et ses {collection.links.length} lien
                            {collection.links.length !== 1 ? 's' : ''} seront copiés dans vos collections.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button
                            variant="outline"
                            onClick={() => setConfirming(false)}
                            disabled={importing}
                        >
                            Annuler
                        </Button>
                        <Button onClick={handleImport} disabled={importing}>
                            {importing && <Spinner />}
                            Importer
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
}
