import { Form, Head, Link, router } from '@inertiajs/react';
import { CardBackground } from '@/components/card-background';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinner';
import products, { create, destroy, edit, index } from '@/routes/products';
import { Input } from '@/components/ui/input';
import { useState } from 'react';
import { useDebouncedCallback } from 'use-debounce';

type Product = {
    id: number;
    name: string;
    description: string;
    price: number;
    image: string | null;
    status: 'active' | 'inactive';
};

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type Props = {
    products: {
        data: Product[];
        links: PaginationLink[];
        total: number;
    };

    filters: {
        search?: string;
    };
};

export default function HomeProductsPage({ products, filters }: Props) {
    const [search, setSearch] = useState(filters.search ?? '');

    const debouncedSearch = useDebouncedCallback(
        (value: string) => {
            router.get(
                index.url(),
                {
                    search: value || undefined,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                    only: ['products', 'filters'],
                },
            );
        },
        300,
        {
            maxWait: 1000,
        },
    );

    function handleSearch(event: React.ChangeEvent<HTMLInputElement>) {
        const value = event.target.value;

        setSearch(value);
        debouncedSearch(value);
    }

    return (
        <>
            <Head title="Products" />

            <div className="p-6">
                <div className="flex items-center justify-between">
                    <div className="mb-6 min-w-32">
                        <h1 className="text-2xl font-semibold">Products</h1>

                        <p className="text-sm text-muted-foreground">
                            Total : {products.total} produits
                        </p>
                    </div>

                    <Input
                        type="text"
                        name="search"
                        placeholder="Search..."
                        value={search}
                        onChange={handleSearch}
                        className="mb-6 w-full"
                        autoComplete="off"
                    />

                    <Button variant="card" asChild>
                        <Link href={create()}>Create Product</Link>
                    </Button>
                </div>

                <div className="grid gap-4 md:grid-cols-3">
                    {products.data.map((product) => (
                        <CardBackground key={product.id}>
                            {product.image && (
                                <img
                                    src={product.image}
                                    alt={product.name}
                                    className="h-48 w-full object-cover"
                                />
                            )}

                            <div className="flex flex-1 flex-col gap-3 p-4">
                                <div className="flex items-start justify-between gap-4">
                                    <h2 className="font-semibold">
                                        {product.name}
                                    </h2>

                                    <span
                                        className={
                                            product.status === 'active'
                                                ? 'rounded-full bg-green-100 px-2 py-1 text-xs text-green-700'
                                                : 'rounded-full bg-red-100 px-2 py-1 text-xs text-red-700'
                                        }
                                    >
                                        {product.status}
                                    </span>
                                </div>

                                <p className="line-clamp-2 text-sm text-muted-foreground">
                                    {product.description}
                                </p>

                                <p className="mt-auto font-medium">
                                    {product.price} $
                                </p>

                                <div className="flex gap-2 pt-2">
                                    <Button variant="outline" size="sm" asChild>
                                        <Link href={edit(product.id)}>
                                            Edit
                                        </Link>
                                    </Button>

                                    <Dialog>
                                        <DialogTrigger asChild>
                                            <Button
                                                variant="destructive"
                                                size="sm"
                                            >
                                                Delete
                                            </Button>
                                        </DialogTrigger>
                                        <DialogContent>
                                            <DialogHeader>
                                                <DialogTitle>
                                                    Delete "{product.name}"?
                                                </DialogTitle>
                                                <DialogDescription>
                                                    This action cannot be
                                                    undone.
                                                </DialogDescription>
                                            </DialogHeader>
                                            <DialogFooter>
                                                <Form
                                                    {...destroy.form(
                                                        product.id,
                                                    )}
                                                >
                                                    {({ processing }) => (
                                                        <Button
                                                            type="submit"
                                                            variant="destructive"
                                                            disabled={
                                                                processing
                                                            }
                                                        >
                                                            {processing && (
                                                                <Spinner />
                                                            )}
                                                            Yes, delete
                                                        </Button>
                                                    )}
                                                </Form>
                                            </DialogFooter>
                                        </DialogContent>
                                    </Dialog>
                                </div>
                            </div>
                        </CardBackground>
                    ))}
                </div>

                <div className="mt-6 flex flex-wrap gap-2">
                    {products.links.map((link, index) => (
                        <Link
                            key={index}
                            href={link.url ?? '#'}
                            className={
                                link.active
                                    ? 'rounded bg-black px-3 py-2 text-sm text-white'
                                    : link.url
                                      ? 'rounded border px-3 py-2 text-sm'
                                      : 'pointer-events-none rounded border px-3 py-2 text-sm opacity-50'
                            }
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    ))}
                </div>
            </div>
        </>
    );
}

HomeProductsPage.layout = {
    breadcrumbs: [
        {
            title: 'Products',
            href: products.index(),
        },
    ],
};
