import { useCartStore } from "../store/cart-store";

export const useCart = () => {
  const items = useCartStore((state) => state.items);
  const addItem = useCartStore((state) => state.addItem);
  const removeItem = useCartStore((state) => state.removeItem);
  const updateQuantity = useCartStore((state) => state.updateQuantity);
  const clearCart = useCartStore((state) => state.clearCart);
  const totalItems = useCartStore((state) =>
    state.items.reduce((acc, i) => acc + i.quantity, 0),
  );
  const totalPrice = useCartStore((state) =>
    state.items.reduce((acc, i) => acc + i.product.price * i.quantity, 0),
  );

  return {
    items,
    addItem,
    removeItem,
    updateQuantity,
    clearCart,
    totalItems,
    totalPrice,
  };
};
