import { Category } from "@/features/category/types/category";

export interface Product {
  id: number;
  title: string;
  slug: string;
  price: number;
  description: string;
  category: Category;
  images: string[];
  createdAt: string;
  updatedAt: string;
}
