// features/search/store/search-store.ts
import { Product } from "@/features/product/types/product";
import * as SecureStore from "expo-secure-store";
import { create } from "zustand";
import { createJSONStorage, persist } from "zustand/middleware";

interface SearchStore {
  recentSearches: Product[];
  addSearch: (product: Product) => void;
  removeSearch: (productId: number) => void;
  clearSearches: () => void;
}

const secureStorage = {
  getItem: (name: string) => SecureStore.getItemAsync(name),
  setItem: (name: string, value: string) =>
    SecureStore.setItemAsync(name, value),
  removeItem: (name: string) => SecureStore.deleteItemAsync(name),
};

export const useSearchStore = create<SearchStore>()(
  persist(
    (set) => ({
      recentSearches: [],
      addSearch: (product) =>
        set((state) => ({
          recentSearches: [
            product,
            ...state.recentSearches.filter((p) => p.id !== product.id),
          ].slice(0, 10),
        })),
      removeSearch: (productId) =>
        set((state) => ({
          recentSearches: state.recentSearches.filter(
            (p) => p.id !== productId,
          ),
        })),
      clearSearches: () => set({ recentSearches: [] }),
    }),
    {
      name: "search-storage",
      storage: createJSONStorage(() => secureStorage),
    },
  ),
);
