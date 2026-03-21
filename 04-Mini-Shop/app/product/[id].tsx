import { useCart } from "@/features/cart/hooks/use-cart";
import ProductDetailError from "@/features/product/components/product-detail-error";
import ProductDetailSkeleton from "@/features/product/components/product-detail-skeleton";
import { useProduct } from "@/features/product/hooks/use-products";
import { Ionicons } from "@expo/vector-icons";
import { useLocalSearchParams } from "expo-router";
import { Button } from "heroui-native";
import React, { useState } from "react";
import {
  Dimensions,
  FlatList,
  Image,
  NativeScrollEvent,
  NativeSyntheticEvent,
  ScrollView,
  Text,
  View,
} from "react-native";

const { width } = Dimensions.get("window");

const ProductDetailScreen = () => {
  const { id } = useLocalSearchParams();
  const [quantity, setQuantity] = useState(1);
  const [activeIndex, setActiveIndex] = useState(0);
  const { addItem } = useCart();

  const { data: product, isLoading, error, refetch } = useProduct(Number(id));

  if (isLoading) return <ProductDetailSkeleton />;
  if (error)
    return <ProductDetailError message={error.message} onRetry={refetch} />;

  const handleScroll = (e: NativeSyntheticEvent<NativeScrollEvent>) => {
    const index = Math.round(e.nativeEvent.contentOffset.x / width);
    setActiveIndex(index);
  };

  return (
    <View className="flex-1 bg-background">
      <ScrollView showsVerticalScrollIndicator={false}>
        {/* Image carousel */}
        <View className="relative">
          <FlatList
            data={product.images}
            horizontal
            pagingEnabled
            showsHorizontalScrollIndicator={false}
            onScroll={handleScroll}
            scrollEventThrottle={16}
            keyExtractor={(_, i) => i.toString()}
            renderItem={({ item }) => (
              <Image
                source={{ uri: item }}
                style={{ width, height: 320 }}
                resizeMode="cover"
              />
            )}
          />
          {/* Dot indicators */}
          <View className="absolute bottom-3 w-full flex-row justify-center gap-1.5">
            {product.images.map((_: string, i: number) => (
              <View
                key={i}
                className={`h-1.5 rounded-full ${
                  i === activeIndex ? "w-5 bg-accent" : "w-1.5 bg-muted"
                }`}
              />
            ))}
          </View>
        </View>

        {/* Info Section */}
        <View className="px-5 pt-5">
          <Text className="text-[11px] font-bold text-accent tracking-widest mb-1.5">
            NEW COLLECTION
          </Text>
          <Text className="text-[26px] font-bold text-foreground leading-8 mb-3">
            {product.title}
          </Text>
          <View className="flex-row items-center gap-3 mb-5">
            <Text className="text-[22px] font-bold text-foreground">
              ${product.price.toFixed(2)}
            </Text>
            <View className="bg-success-soft rounded-full px-3 py-1">
              <Text className="text-success-soft-foreground text-[11px] font-bold tracking-wider">
                IN STOCK
              </Text>
            </View>
          </View>
          <Text className="text-[11px] font-bold text-muted tracking-widest mb-2">
            DESCRIPTION
          </Text>
          <Text className="text-sm text-muted leading-6 mb-5">
            {product.description}
          </Text>
          <View className="flex-row gap-3 mb-6">
            <View className="flex-1 bg-default rounded-xl p-3.5">
              <Text className="text-[10px] font-bold text-muted tracking-widest mb-1">
                MATERIAL
              </Text>
              <Text className="text-[13px] font-semibold text-foreground">
                100% Virgin Wool
              </Text>
            </View>
            <View className="flex-1 bg-default rounded-xl p-3.5">
              <Text className="text-[10px] font-bold text-muted tracking-widest mb-1">
                ORIGIN
              </Text>
              <Text className="text-[13px] font-semibold text-foreground">
                Made in Italy
              </Text>
            </View>
          </View>
          <Text className="text-[11px] font-bold text-muted tracking-widest mb-3">
            QUANTITY
          </Text>
          <View className="mb-6 flex justify-center items-center">
            <View className="flex-row items-center gap-5">
              <Button
                variant="tertiary"
                isIconOnly
                size="lg"
                onPress={() => setQuantity((q) => Math.max(1, q - 1))}
              >
                <Button.Label>−</Button.Label>
              </Button>
              <Text className="text-lg font-bold text-foreground min-w-6 text-center">
                {quantity}
              </Text>
              <Button
                variant="tertiary"
                isIconOnly
                size="lg"
                onPress={() => setQuantity((q) => q + 1)}
              >
                <Button.Label>+</Button.Label>
              </Button>
            </View>
          </View>
        </View>
      </ScrollView>

      {/* Add to Cart */}
      <View className="bg-background px-4 gap-4 border-t border-separator-tertiary">
        <Button className="mt-4" onPress={() => addItem(product, quantity)}>
          <Ionicons name="cart" size={20} color="white" />
          <Button.Label>Add Item</Button.Label>
        </Button>
        <Text className="text-center text-xs text-muted mb-5">
          Free Express Shipping & Lifetime Returns
        </Text>
      </View>
    </View>
  );
};

export default ProductDetailScreen;
