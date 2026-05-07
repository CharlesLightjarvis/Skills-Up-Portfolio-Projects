import { useState } from 'react';
import { Form, Head, Link } from '@inertiajs/react';
import CollectionController from '@/actions/App/Http/Controllers/CollectionController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { X } from 'lucide-react';
import collections from '@/routes/collections';
import { LinkUrlFetcher, type FetchedLink } from './components/link-url-fetcher';

export default function CreateCollection() {
    const [links, setLinks] = useState<FetchedLink[]>([]);

    function removeLink(index: number) {
        setLinks((prev) => prev.filter((_, i) => i !== index));
    }

    return (
        <>
            <Head title="Nouvelle collection" />
            <div className="container mx-auto max-w-xl space-y-6 px-8 py-4">
                <h1 className="text-2xl font-semibold">Nouvelle collection</h1>

                <Form
                    action={CollectionController.store.url()}
                    method="post"
                    className="space-y-5"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Nom</Label>
                                <Input
                                    id="name"
                                    name="name"
                                    required
                                    autoFocus
                                    placeholder="Mon blog"
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="description">Description</Label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows={2}
                                    className="flex w-full min-w-0 rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-xs outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:opacity-50 md:text-sm dark:bg-input/30"
                                    placeholder="Description optionnelle..."
                                />
                                <InputError message={errors.description} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="color">Couleur</Label>
                                <div className="flex items-center gap-3">
                                    <input
                                        id="color"
                                        name="color"
                                        type="color"
                                        defaultValue="#6366f1"
                                        className="h-9 w-16 cursor-pointer rounded-md border border-input bg-transparent p-1"
                                    />
                                    <span className="text-sm text-muted-foreground">
                                        Couleur d'identification
                                    </span>
                                </div>
                                <InputError message={errors.color} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="image">Image de couverture</Label>
                                <input
                                    id="image"
                                    name="image"
                                    type="file"
                                    accept="image/jpeg,image/png"
                                    className="flex w-full cursor-pointer rounded-md border border-input bg-transparent px-3 py-2 text-sm text-muted-foreground file:mr-3 file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground"
                                />
                                <p className="text-xs text-muted-foreground">PNG ou JPEG, max 2 Mo</p>
                                <InputError message={errors.image} />
                            </div>

                            <div className="border-t pt-4">
                                <p className="mb-3 text-sm font-medium">
                                    Liens à ajouter{' '}
                                    <span className="font-normal text-muted-foreground">
                                        (optionnel)
                                    </span>
                                </p>

                                <LinkUrlFetcher
                                    onAdd={(link) =>
                                        setLinks((prev) => [...prev, link])
                                    }
                                />

                                {links.length > 0 && (
                                    <ul className="mt-3 space-y-2">
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

                                                <input
                                                    type="hidden"
                                                    name={`links[${i}][url]`}
                                                    value={link.url}
                                                />
                                                <input
                                                    type="hidden"
                                                    name={`links[${i}][title]`}
                                                    value={link.title}
                                                />
                                                {link.description && (
                                                    <input
                                                        type="hidden"
                                                        name={`links[${i}][description]`}
                                                        value={link.description}
                                                    />
                                                )}
                                                {link.image_url && (
                                                    <input
                                                        type="hidden"
                                                        name={`links[${i}][image_url]`}
                                                        value={link.image_url}
                                                    />
                                                )}
                                                {link.site_name && (
                                                    <input
                                                        type="hidden"
                                                        name={`links[${i}][site_name]`}
                                                        value={link.site_name}
                                                    />
                                                )}
                                                {link.domain && (
                                                    <input
                                                        type="hidden"
                                                        name={`links[${i}][domain]`}
                                                        value={link.domain}
                                                    />
                                                )}
                                            </li>
                                        ))}
                                    </ul>
                                )}
                            </div>

                            <div className="flex gap-2 pt-2">
                                <Button type="submit" disabled={processing}>
                                    {processing && <Spinner />}
                                    Créer la collection
                                </Button>
                                <Button variant="outline" asChild>
                                    <Link href={collections.index.url()}>
                                        Annuler
                                    </Link>
                                </Button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}

CreateCollection.layout = {
    breadcrumbs: [
        { title: 'Collections', href: collections.index.url() },
        { title: 'Nouvelle collection', href: collections.create.url() },
    ],
};
