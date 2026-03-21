import { queryKeys } from "@/shared/config/query-keys";
import { useQuery } from "@tanstack/react-query";
import { ProductService } from "../services/product-service";

// hooks/use-products.ts
export const useProducts = (categorySlug?: string) => {
  return useQuery({
    queryKey: ["products", categorySlug],
    queryFn: () => ProductService.getProducts(categorySlug),
  });
};

export function useProduct(id: number) {
  return useQuery({
    queryKey: queryKeys.products.detail(id),
    queryFn: () => ProductService.getProduct(id),
    enabled: !!id,
  });
}
