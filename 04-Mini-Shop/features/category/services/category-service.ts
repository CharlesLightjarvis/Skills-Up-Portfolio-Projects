import { api } from "@/shared/config/api";

export const CategoryService = {
  getCategories: async () => {
    const response = await api.get("/categories");
    return response.data;
  },
};
