import { Form, Head, Link } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import products, { update } from '@/routes/products';

type Product = {
    id: number;
    name: string;
    description: string | null;
    price: number;
    image: string | null;
    status: 'active' | 'inactive';
};

type Props = {
    product: Product;
};

export default function EditProductPage({ product }: Props) {
    return (
        <>
            <Head title="Edit Product" />

            <div className="max-w-xl p-6">
                <Form {...update.form(product.id)} className="flex flex-col gap-6">
                    {({ processing, errors }) => (
                        <div className="grid gap-6">
                            <div className="grid gap-2">
                                <Label htmlFor="name">Name</Label>
                                <Input
                                    id="name"
                                    name="name"
                                    type="text"
                                    required
                                    autoFocus
                                    defaultValue={product.name}
                                    placeholder="Product name"
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="description">Description</Label>
                                <Input
                                    id="description"
                                    name="description"
                                    type="text"
                                    defaultValue={product.description ?? ''}
                                    placeholder="Optional description"
                                />
                                <InputError message={errors.description} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="price">Price</Label>
                                <Input
                                    id="price"
                                    name="price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    required
                                    defaultValue={product.price}
                                    placeholder="0.00"
                                />
                                <InputError message={errors.price} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="image">Image URL</Label>
                                <Input
                                    id="image"
                                    name="image"
                                    type="text"
                                    defaultValue={product.image ?? ''}
                                    placeholder="https://..."
                                />
                                <InputError message={errors.image} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="status">Status</Label>
                                <select
                                    id="status"
                                    name="status"
                                    defaultValue={product.status}
                                    className="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <InputError message={errors.status} />
                            </div>

                            <div className="flex gap-3">
                                <Button type="submit" disabled={processing}>
                                    {processing && <Spinner />}
                                    Save Changes
                                </Button>
                                <Button variant="outline" asChild>
                                    <Link href={products.index()}>Cancel</Link>
                                </Button>
                            </div>
                        </div>
                    )}
                </Form>
            </div>
        </>
    );
}

EditProductPage.layout = {
    breadcrumbs: [
        {
            title: 'Products',
            href: products.index(),
        },
        {
            title: 'Edit Product',
            href: '#',
        },
    ],
};
