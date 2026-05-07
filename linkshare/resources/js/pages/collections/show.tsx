import { useState } from 'react';
import { Head, Link, router, setLayoutProps, usePage } from '@inertiajs/react';
import type { Collection } from '@/types/collection';
import type { Link as LinkType } from '@/types/link';
import type { Paginated } from '@/types/pagination';
import collections from '@/routes/collections';
import collectionLinks from '@/routes/collections/links';
import CollectionController from '@/actions/App/Http/Controllers/CollectionController';
import LinkController from '@/actions/App/Http/Controllers/LinkController';
import { Button, buttonVariants } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
} from '@/components/ui/pagination';
import { cn } from '@/lib/utils';
import {
    ChevronLeft,
    ChevronRight,
    Heart,
    MoreHorizontal,
    Pencil,
    Share2,
    Trash2,
} from 'lucide-react';
import { ShareModal } from './components/share-modal';
import { User } from '@/types';

type PageProps = {
    collection: Collection;
    links: Paginated<LinkType>;
    userSearchResults: User[];
};

export default function ShowCollection() {
    const {
        collection,
        links: paginator,
        userSearchResults,
    } = usePage<PageProps>().props;

    const [deletingLink, setDeletingLink] = useState<LinkType | null>(null);
    const [deletingCollection, setDeletingCollection] = useState(false);
    const [sharingCollection, setSharingCollection] = useState(false);

    setLayoutProps({
        breadcrumbs: [
            { title: 'Collections', href: collections.index.url() },
            { title: collection.name, href: collections.show.url(collection) },
        ],
    });

    function toggleFavorite(link: LinkType) {
        router.patch(
            LinkController.update.url({
                collection: collection.id,
                link: link.id,
            }),
            { is_favorite: !link.is_favorite },
            { preserveScroll: true },
        );
    }

    function confirmDeleteLink() {
        if (!deletingLink) return;
        router.delete(
            LinkController.destroy.url({
                collection: collection.id,
                link: deletingLink.id,
            }),
            { preserveScroll: true, onSuccess: () => setDeletingLink(null) },
        );
    }

    function confirmDeleteCollection() {
        router.delete(CollectionController.destroy.url(collection), {
            onSuccess: () => router.visit(collections.index.url()),
        });
    }

    function shareLink(link: LinkType) {
        navigator.clipboard.writeText(link.url);
    }

    return (
        <>
            <Head title={collection.name} />
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
                            {collection.description && (
                                <p className="text-sm text-muted-foreground">
                                    {collection.description}
                                </p>
                            )}
                        </div>
                    </div>
                    <div className="flex shrink-0 items-center gap-2">
                        <Button asChild variant="outline" size="sm">
                            <Link href={collectionLinks.create.url(collection)}>
                                <span className="text-base leading-none">
                                    +
                                </span>
                                Ajouter un lien
                            </Link>
                        </Button>
                        <Button asChild variant="outline" size="sm">
                            <Link href={collections.edit.url(collection)}>
                                <Pencil className="h-4 w-4" />
                                Modifier
                            </Link>
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={() => setDeletingCollection(true)}
                            className="text-destructive hover:bg-destructive/10 hover:text-destructive"
                        >
                            <Trash2 className="h-4 w-4" />
                            Supprimer
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={() => setSharingCollection(true)}
                        >
                            <Share2 className="h-4 w-4" />
                            Partager
                        </Button>
                    </div>
                </div>

                {paginator.data.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-16 text-center">
                        <p className="text-muted-foreground">
                            Aucun lien dans cette collection.
                        </p>
                        <Button asChild className="mt-4">
                            <Link href={collections.edit.url(collection)}>
                                Ajouter des liens
                            </Link>
                        </Button>
                    </div>
                ) : (
                    <ul className="divide-y rounded-lg border">
                        {paginator.data.map((link) => (
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
                                        <div className="h-4 w-4 rounded bg-muted" />
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

                                <div className="flex shrink-0 items-center gap-1">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        onClick={() => toggleFavorite(link)}
                                        className={
                                            link.is_favorite
                                                ? 'text-red-500 hover:text-red-600'
                                                : 'text-muted-foreground hover:text-foreground'
                                        }
                                    >
                                        <Heart
                                            className={cn(
                                                'h-4 w-4',
                                                link.is_favorite &&
                                                    'fill-current',
                                            )}
                                        />
                                    </Button>

                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        onClick={() => shareLink(link)}
                                        className="text-muted-foreground hover:text-foreground"
                                    >
                                        <Share2 className="h-4 w-4" />
                                    </Button>

                                    <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                className="text-muted-foreground hover:text-foreground"
                                            >
                                                <MoreHorizontal className="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem asChild>
                                                <Link
                                                    href={collectionLinks.edit.url(
                                                        {
                                                            collection:
                                                                collection.id,
                                                            link: link.id,
                                                        },
                                                    )}
                                                >
                                                    Modifier
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                className="text-destructive focus:text-destructive"
                                                onClick={() =>
                                                    setDeletingLink(link)
                                                }
                                            >
                                                Supprimer
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </div>
                            </li>
                        ))}
                    </ul>
                )}

                {paginator.last_page > 1 && (
                    <Pagination>
                        <PaginationContent>
                            {paginator.links.map((link, i) => {
                                if (i === 0) {
                                    return (
                                        <PaginationItem key={i}>
                                            <Link
                                                href={link.url ?? '#'}
                                                className={cn(
                                                    buttonVariants({
                                                        variant: 'ghost',
                                                        size: 'default',
                                                    }),
                                                    'gap-1 px-2.5',
                                                    !link.url &&
                                                        'pointer-events-none opacity-50',
                                                )}
                                            >
                                                <ChevronLeft className="h-4 w-4" />
                                                <span className="hidden sm:block">
                                                    Précédent
                                                </span>
                                            </Link>
                                        </PaginationItem>
                                    );
                                }
                                if (i === paginator.links.length - 1) {
                                    return (
                                        <PaginationItem key={i}>
                                            <Link
                                                href={link.url ?? '#'}
                                                className={cn(
                                                    buttonVariants({
                                                        variant: 'ghost',
                                                        size: 'default',
                                                    }),
                                                    'gap-1 px-2.5',
                                                    !link.url &&
                                                        'pointer-events-none opacity-50',
                                                )}
                                            >
                                                <span className="hidden sm:block">
                                                    Suivant
                                                </span>
                                                <ChevronRight className="h-4 w-4" />
                                            </Link>
                                        </PaginationItem>
                                    );
                                }
                                if (link.label === '...') {
                                    return (
                                        <PaginationItem key={i}>
                                            <PaginationEllipsis />
                                        </PaginationItem>
                                    );
                                }
                                return (
                                    <PaginationItem key={i}>
                                        <Link
                                            href={link.url ?? '#'}
                                            className={cn(
                                                buttonVariants({
                                                    variant: link.active
                                                        ? 'outline'
                                                        : 'ghost',
                                                    size: 'icon',
                                                }),
                                            )}
                                        >
                                            {link.label}
                                        </Link>
                                    </PaginationItem>
                                );
                            })}
                        </PaginationContent>
                    </Pagination>
                )}
            </div>

            {/* Partage de la collection */}
            <ShareModal
                shareable={collection}
                shareableType="collection"
                open={sharingCollection}
                onClose={() => setSharingCollection(false)}
                userSearchResults={userSearchResults}
            />

            {/* Delete link confirmation */}
            <Dialog
                open={!!deletingLink}
                onOpenChange={(open) => !open && setDeletingLink(null)}
            >
                <DialogContent className="sm:max-w-sm">
                    <DialogHeader>
                        <DialogTitle>Supprimer le lien</DialogTitle>
                        <DialogDescription>
                            Voulez-vous vraiment supprimer{' '}
                            <span className="font-medium text-foreground">
                                {deletingLink?.title || deletingLink?.url}
                            </span>{' '}
                            ? Cette action est irréversible.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button
                            variant="outline"
                            onClick={() => setDeletingLink(null)}
                        >
                            Annuler
                        </Button>
                        <Button
                            variant="destructive"
                            onClick={confirmDeleteLink}
                        >
                            Supprimer
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            {/* Delete collection confirmation */}
            <Dialog
                open={deletingCollection}
                onOpenChange={setDeletingCollection}
            >
                <DialogContent className="sm:max-w-sm">
                    <DialogHeader>
                        <DialogTitle>Supprimer la collection</DialogTitle>
                        <DialogDescription>
                            Voulez-vous vraiment supprimer la collection{' '}
                            <span className="font-medium text-foreground">
                                {collection.name}
                            </span>{' '}
                            et tous ses liens ? Cette action est irréversible.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button
                            variant="outline"
                            onClick={() => setDeletingCollection(false)}
                        >
                            Annuler
                        </Button>
                        <Button
                            variant="destructive"
                            onClick={confirmDeleteCollection}
                        >
                            Supprimer
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
}
