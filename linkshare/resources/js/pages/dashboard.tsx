import { Head, Link, usePage } from '@inertiajs/react';
import { Folder, Globe, Heart, Link2, Plus, Sparkles } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import collections from '@/routes/collections';
import { dashboard } from '@/routes';
import type { Collection } from '@/types/collection';

type RecentLink = {
    id: number;
    collection_id: number;
    url: string;
    title: string | null;
    domain: string | null;
    is_favorite: boolean;
    created_at: string;
    collection: Pick<Collection, 'id' | 'name' | 'color'>;
};

type Stats = {
    links: number;
    collections: number;
    favorites: number;
    this_month: number;
};

type PageProps = {
    stats: Stats;
    recent_links: RecentLink[];
};

const statCards = (stats: Stats) => [
    {
        label: 'Liens sauvegardés',
        value: stats.links,
        icon: Link2,
        description: 'au total',
    },
    {
        label: 'Collections',
        value: stats.collections,
        icon: Folder,
        description: 'créées',
    },
    {
        label: 'Favoris',
        value: stats.favorites,
        icon: Heart,
        description: 'liens favoris',
    },
    {
        label: 'Ce mois-ci',
        value: stats.this_month,
        icon: Sparkles,
        description: 'liens ajoutés',
    },
];

export default function Dashboard() {
    const { stats, recent_links } = usePage<PageProps>().props;

    return (
        <>
            <Head title="Dashboard" />
            <div className="container mx-auto space-y-8 px-8 py-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">Dashboard</h1>
                        <p className="text-sm text-muted-foreground">
                            Vue d'ensemble de votre espace
                        </p>
                    </div>
                </div>

                {/* Stats */}
                <div className="grid grid-cols-2 gap-4 lg:grid-cols-4">
                    {statCards(stats).map((card) => (
                        <Card key={card.label}>
                            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle className="text-sm font-medium text-muted-foreground">
                                    {card.label}
                                </CardTitle>
                                <card.icon className="h-4 w-4 text-muted-foreground" />
                            </CardHeader>
                            <CardContent>
                                <p className="text-3xl font-bold">
                                    {card.value}
                                </p>
                                <p className="mt-1 text-xs text-muted-foreground">
                                    {card.description}
                                </p>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {/* Recent links */}
                <div>
                    <div className="mb-4 flex items-center justify-between">
                        <h2 className="text-base font-semibold">
                            Liens récents
                        </h2>
                        <Button asChild variant="ghost" size="sm">
                            <Link href={collections.index.url()}>
                                Voir les collections
                            </Link>
                        </Button>
                    </div>

                    {recent_links.length === 0 ? (
                        <div className="flex flex-col items-center justify-center rounded-lg border border-dashed py-16 text-center">
                            <Link2 className="mb-3 h-8 w-8 text-muted-foreground" />
                            <p className="text-sm text-muted-foreground">
                                Aucun lien sauvegardé pour l'instant.
                            </p>
                            <Button asChild className="mt-4" size="sm">
                                <Link href={collections.create.url()}>
                                    Créer une collection
                                </Link>
                            </Button>
                        </div>
                    ) : (
                        <ul className="divide-y rounded-lg border">
                            {recent_links.map((link) => (
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
                                            {link.domain}
                                        </p>
                                    </div>

                                    <Link
                                        href={collections.show.url(
                                            link.collection,
                                        )}
                                        className="flex shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs text-muted-foreground transition hover:text-foreground"
                                    >
                                        {link.collection.color && (
                                            <span
                                                className="h-2 w-2 rounded-full"
                                                style={{
                                                    backgroundColor:
                                                        link.collection.color,
                                                }}
                                            />
                                        )}
                                        {link.collection.name}
                                    </Link>

                                    {link.is_favorite && (
                                        <Heart className="h-3.5 w-3.5 shrink-0 fill-current text-red-500" />
                                    )}
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [{ title: 'Dashboard', href: dashboard.url() }],
};
