import { queryKeys } from "@/shared/config/query-keys";
import { useQuery } from "@tanstack/react-query";
import { CategoryService } from "../services/category-service";

export function useCategories() {
  return useQuery({
    queryKey: queryKeys.categories.lists(),
    queryFn: () => CategoryService.getCategories(),
  });
}
