import { api } from "@/shared/config/api";
import { Product } from "../types/product";

export const ProductService = {
  getProducts: async (slug?: string): Promise<Product[]> => {
    const response = await api.get<Product[]>("products", {
      params: slug ? { categorySlug: slug } : undefined,
    });
    return response.data;
  },
  getProduct: async (id: number) => {
    const response = await api.get(`/products/${id}`);
    return response.data;
  },
};
