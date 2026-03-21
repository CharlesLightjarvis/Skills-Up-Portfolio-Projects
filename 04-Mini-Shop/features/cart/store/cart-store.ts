import { Product } from "@/features/product/types/product";
import * as SecureStore from "expo-secure-store";
import { create } from "zustand";
import { createJSONStorage, persist } from "zustand/middleware";

export interface CartItem {
  product: Product;
  quantity: number;
}

interface CartStore {
  items: CartItem[];
  addItem(product: Product, quantity: number): void;
  removeItem(productId: number): void;
  updateQuantity(productId: number, quantity: number): void;
  totalItems(): number;
  totalPrice(): number;
  clearCart(): void;
}

const secureStorage = {
  getItem: (name: string) => SecureStore.getItemAsync(name),
  setItem: (name: string, value: string) =>
    SecureStore.setItemAsync(name, value),
  removeItem: (name: string) => SecureStore.deleteItemAsync(name),
};

export const useCartStore = create<CartStore>()(
  persist(
    (set, get) => ({
      items: [],

      addItem: (product, quantity) => {
        const existing = get().items.find((i) => i.product.id === product.id);
        if (existing) {
          set((state) => ({
            items: state.items.map((i) =>
              i.product.id === product.id
                ? { ...i, quantity: i.quantity + quantity }
                : i,
            ),
          }));
        } else {
          set((state) => ({ items: [...state.items, { product, quantity }] }));
        }
      },

      removeItem: (productId) =>
        set((state) => ({
          items: state.items.filter((i) => i.product.id !== productId),
        })),

      updateQuantity: (productId, quantity) =>
        set((state) => ({
          items: state.items.map((i) =>
            i.product.id === productId ? { ...i, quantity } : i,
          ),
        })),

      clearCart: () => set({ items: [] }),

      totalItems: () => get().items.reduce((acc, i) => acc + i.quantity, 0),

      totalPrice: () =>
        get().items.reduce((acc, i) => acc + i.product.price * i.quantity, 0),
    }),
    {
      name: "cart-storage",
      storage: createJSONStorage(() => secureStorage),
    },
  ),
);
