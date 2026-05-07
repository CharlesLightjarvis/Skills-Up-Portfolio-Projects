import { Head, Link, usePage } from '@inertiajs/react';
import type { Collection } from '@/types/collection';
import type { Paginated } from '@/types/pagination';
import collections from '@/routes/collections';
import { Button, buttonVariants } from '@/components/ui/button';
import { CardBackground } from '@/components/ui/card-background';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
} from '@/components/ui/pagination';
import { cn } from '@/lib/utils';
import { ChevronLeft, ChevronRight, Plus } from 'lucide-react';

export default function CollectionsIndex() {
    const { collections: paginator } = usePage<{
        collections: Paginated<Collection>;
    }>().props;

    return (
        <>
            <Head title="Collections" />
            <div className="container mx-auto space-y-6 px-8 py-4">
                <div className="flex items-center justify-between">
                    <div className="space-y-1">
                        <h1 className="text-2xl font-semibold">Mes collections</h1>
                        <p className="text-sm text-muted-foreground">
                            {paginator.total} collection{paginator.total !== 1 ? 's' : ''}
                        </p>
                    </div>
                    <Button asChild>
                        <Link href={collections.create.url()}>
                            <Plus className="h-4 w-4" />
                            Nouvelle collection
                        </Link>
                    </Button>
                </div>

                {paginator.data.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-16 text-center">
                        <p className="text-muted-foreground">Aucune collection pour le moment.</p>
                        <Button asChild className="mt-4">
                            <Link href={collections.create.url()}>
                                Créer ma première collection
                            </Link>
                        </Button>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        {paginator.data.map((collection) => (
                            <Link
                                key={collection.id}
                                href={collections.show.url(collection)}
                                className="group block"
                            >
                                <CardBackground className="h-full">
                                    {/* Image / color hero */}
                                    <div className="relative overflow-hidden rounded-t-2xl" style={{ aspectRatio: '16/9' }}>
                                        {collection.image_url ? (
                                            <img
                                                src={collection.image_url}
                                                alt={collection.name}
                                                className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                            />
                                        ) : (
                                            <div
                                                className="h-full w-full"
                                                style={{ backgroundColor: collection.color ?? '#6366f1' }}
                                            />
                                        )}
                                        {/* Gradient fade into card background */}
                                        <div className="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-background to-transparent" />
                                    </div>

                                    {/* Content */}
                                    <div className="flex flex-col gap-1 px-4 pb-4 pt-2">
                                        <div className="flex items-center gap-2">
                                            {collection.color && !collection.image_url && (
                                                <span
                                                    className="h-2.5 w-2.5 shrink-0 rounded-full"
                                                    style={{ backgroundColor: collection.color }}
                                                />
                                            )}
                                            <h3 className="truncate font-semibold transition-colors group-hover:text-primary">
                                                {collection.name}
                                            </h3>
                                        </div>
                                        {collection.description && (
                                            <p className="line-clamp-2 text-sm text-muted-foreground">
                                                {collection.description}
                                            </p>
                                        )}
                                    </div>
                                </CardBackground>
                            </Link>
                        ))}
                    </div>
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
                                                    buttonVariants({ variant: 'ghost', size: 'default' }),
                                                    'gap-1 px-2.5',
                                                    !link.url && 'pointer-events-none opacity-50',
                                                )}
                                            >
                                                <ChevronLeft className="h-4 w-4" />
                                                <span className="hidden sm:block">Précédent</span>
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
                                                    buttonVariants({ variant: 'ghost', size: 'default' }),
                                                    'gap-1 px-2.5',
                                                    !link.url && 'pointer-events-none opacity-50',
                                                )}
                                            >
                                                <span className="hidden sm:block">Suivant</span>
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
                                                    variant: link.active ? 'outline' : 'ghost',
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
        </>
    );
}

CollectionsIndex.layout = {
    breadcrumbs: [{ title: 'Collections', href: collections.index.url() }],
};
