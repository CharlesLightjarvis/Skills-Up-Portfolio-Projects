import CategoryFilter from "@/features/category/components/category-filter";
import ProductCard from "@/features/product/components/product-card";
import ProductError from "@/features/product/components/product-error";
import ProductSkeleton from "@/features/product/components/product-skeleton";
import { useProducts } from "@/features/product/hooks/use-products";
import { Product } from "@/features/product/types/product";
import { useState } from "react";
import { FlatList } from "react-native";

export default function ProductHomeScreen() {
  const [selectedCategory, setSelectedCategory] = useState<
    string | undefined
  >();
  const {
    data: products,
    isLoading,
    error,
    refetch,
  } = useProducts(selectedCategory);

  if (error) return <ProductError message={error.message} onRetry={refetch} />;

  return (
    <FlatList
      className="bg-background flex-1 px-4"
      showsVerticalScrollIndicator={false}
      contentInsetAdjustmentBehavior="automatic"
      data={
        isLoading ? (Array.from({ length: 6 }) as Product[]) : (products ?? [])
      }
      numColumns={2}
      keyExtractor={(_, i) => i.toString()}
      ListHeaderComponent={
        <CategoryFilter
          selected={selectedCategory}
          onSelect={setSelectedCategory}
        />
      }
      renderItem={({ item, index }) =>
        isLoading ? (
          <ProductSkeleton key={index} />
        ) : (
          <ProductCard product={item} />
        )
      }
    />
  );
}
