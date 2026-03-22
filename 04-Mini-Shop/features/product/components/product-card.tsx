import { router } from "expo-router";
import { PressableFeedback } from "heroui-native";
import { memo } from "react";
import { Image, Text, View } from "react-native";
import { Product } from "../types/product";

interface ProductCardProps {
  product: Product;
  onPress?: () => void;
  onAddToCart?: (product: Product) => void;
}

const ProductCard = memo(({ product, onPress, onAddToCart }: ProductCardProps) => {
  return (
    <PressableFeedback
      className="flex-1 m-2"
      onPress={onPress ?? (() => router.push(`/product/${product.id}`))}
    >
      {/* Image card with overlaid + button */}
      <View className="rounded-2xl overflow-hidden">
        <Image
          source={{ uri: product.images[0] }}
          className="w-full aspect-square"
          resizeMode="cover"
        />
      </View>

      {/* Info below card */}
      <View className="mt-2 px-0.5">
        <Text
          className="text-sm text-foreground font-semibold "
          numberOfLines={1}
        >
          {product.title}
        </Text>
        <Text className="text-xs text-foreground  mt-0.5" numberOfLines={1}>
          {product.description}
        </Text>
        <Text className="text-sm font-medium text-foreground  mt-1">
          ${product.price.toFixed(2)}
        </Text>
      </View>
    </PressableFeedback>
  );
});

export default ProductCard;
