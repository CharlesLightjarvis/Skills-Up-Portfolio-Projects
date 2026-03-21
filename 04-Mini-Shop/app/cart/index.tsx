import { useCart } from "@/features/cart/hooks/use-cart";
import { Ionicons } from "@expo/vector-icons";
import { useRouter } from "expo-router";
import { Button, useThemeColor } from "heroui-native";
import React from "react";
import { Image, ScrollView, Text, TouchableOpacity, View } from "react-native";

const CartScreen = () => {
  const router = useRouter();
  const { items, removeItem, updateQuantity, clearCart, totalPrice } =
    useCart();
  const [muted, accentForeground] = useThemeColor([
    "muted",
    "accent-foreground",
  ] as const);

  return (
    <>
      <ScrollView
        showsVerticalScrollIndicator={false}
        contentInsetAdjustmentBehavior="automatic"
      >
        {/* Header */}
        <View className="px-5 pt-4 pb-2">
          <View className="flex-row items-center justify-between">
            <Text className="text-sm text-muted">
              {items.length} article{items.length > 1 ? "s" : ""} sélectionné
              {items.length > 1 ? "s" : ""}
            </Text>
            <TouchableOpacity onPress={clearCart}>
              <Text className="text-[11px] font-bold tracking-widest text-danger">
                SUPPRIMER TOUT
              </Text>
            </TouchableOpacity>
          </View>
        </View>

        {/* Cart items */}
        <View className="px-5 pt-4 gap-5">
          {items.map(({ product, quantity }) => (
            <View key={product.id} className="flex-row gap-4">
              {/* Image */}
              <View className="w-28 h-28 rounded-2xl overflow-hidden bg-default">
                <Image
                  source={{ uri: product.images[0] }}
                  className="w-full h-full"
                  resizeMode="cover"
                />
              </View>

              {/* Info */}
              <View className="flex-1 justify-between py-1">
                <View className="flex-row justify-between items-start">
                  <Text className="text-[16px] font-bold text-foreground flex-1 leading-5 mr-2">
                    {product.title}
                  </Text>
                  <Text className="text-[16px] font-bold text-foreground">
                    {(product.price * quantity).toFixed(2).replace(".", ",")} €
                  </Text>
                </View>

                <Text className="text-sm text-muted" numberOfLines={1}>
                  {product.description}
                </Text>

                {/* Qty + delete */}
                <View className="flex-row items-center justify-between mt-1">
                  <View className="flex-row items-center bg-default rounded-full px-1 py-1 gap-2">
                    <TouchableOpacity
                      onPress={() =>
                        updateQuantity(product.id, Math.max(1, quantity - 1))
                      }
                      className="w-8 h-8 rounded-full items-center justify-center"
                    >
                      <Text className="text-lg text-foreground leading-none">
                        −
                      </Text>
                    </TouchableOpacity>
                    <Text className="text-sm font-bold text-foreground w-4 text-center">
                      {quantity}
                    </Text>
                    <TouchableOpacity
                      onPress={() => updateQuantity(product.id, quantity + 1)}
                      className="w-8 h-8 rounded-full items-center justify-center"
                    >
                      <Text className="text-lg text-foreground leading-none">
                        +
                      </Text>
                    </TouchableOpacity>
                  </View>

                  <TouchableOpacity
                    onPress={() => removeItem(product.id)}
                    className="w-9 h-9 items-center justify-center"
                  >
                    <Ionicons name="trash-outline" size={20} color={muted} />
                  </TouchableOpacity>
                </View>
              </View>
            </View>
          ))}
        </View>

        {/* Summary */}
        <View className="px-5 mt-10 mb-4 gap-3">
          <View className="flex-row justify-between items-center">
            <Text className="text-base text-muted">Sous-total</Text>
            <Text className="text-base text-foreground">
              {totalPrice.toFixed(2).replace(".", ",")} €
            </Text>
          </View>
          <View className="flex-row justify-between items-center">
            <Text className="text-base text-muted">Livraison</Text>
            <Text className="text-base font-semibold text-success">
              Gratuit
            </Text>
          </View>
        </View>

        {/* Separator */}
        <View className="mx-5 border-t border-separator" />

        {/* Total */}
        <View className="px-5 mt-4 flex-row justify-between items-center mb-8">
          <Text className="text-base text-muted">Total à régler</Text>
          <Text className="text-[28px] font-black text-foreground">
            {totalPrice.toFixed(2).replace(".", ",")} €
          </Text>
        </View>
      </ScrollView>

      {/* Checkout button */}
      <View className="px-5 pb-8 pt-2 bg-background border-t border-separator">
        <Button
          size="lg"
          className="w-full"
          onPress={() => router.push("/order/confirmation")}
        >
          <Button.Label>Valider la commande</Button.Label>
          <Ionicons name="arrow-forward" size={18} color={accentForeground} />
        </Button>
      </View>
    </>
  );
};

export default CartScreen;
