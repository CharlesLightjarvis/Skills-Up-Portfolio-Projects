import { IconSymbol } from "@/components/ui/icon-symbol";
import ProductCard from "@/features/product/components/product-card";
import ProductError from "@/features/product/components/product-error";
import ProductSkeleton from "@/features/product/components/product-skeleton";
import { useProducts } from "@/features/product/hooks/use-products";
import RecentSearches from "@/features/search/components/recent-searches";
import { useSearchStore } from "@/features/search/store/search-store";
import { useDebounce } from "@/shared/hook/use-debounce";
import { useSearch } from "@/shared/hook/use-search";
import { router } from "expo-router";
import { useCallback, useMemo } from "react";
import { FlatList, PlatformColor, Text, View } from "react-native";

export default function SearchScreen() {
  const { search, isActive } = useSearch();
  const debouncedSearch = useDebounce(search, 300);
  const { addSearch } = useSearchStore();
  const { data: products, isLoading, error, refetch } = useProducts();

  const filtered = useMemo(
    () =>
      (products ?? []).filter((p) =>
        [p.title, p.description].some((field) =>
          field.toLowerCase().includes(debouncedSearch.toLowerCase()),
        ),
      ),
    [products, debouncedSearch],
  );

  const keyExtractor = useCallback((_: any, i: number) => i.toString(), []);

  const renderItem = useCallback(
    ({ item }: { item: any }) =>
      isLoading ? (
        <ProductSkeleton />
      ) : (
        <ProductCard
          product={item}
          onPress={() => {
            addSearch(item);
            router.push(`/product/${item.id}`);
          }}
        />
      ),
    [isLoading],
  );

  if (error) return <ProductError message={error.message} onRetry={refetch} />;

  if (!isActive && !debouncedSearch) {
    return <RecentSearches />;
  }

  return (
    <FlatList
      className="bg-background flex-1 px-4"
      showsVerticalScrollIndicator={false}
      contentInsetAdjustmentBehavior="automatic"
      keyboardDismissMode="on-drag"
      keyboardShouldPersistTaps="handled"
      data={isLoading ? (Array.from({ length: 6 }) as any[]) : filtered}
      numColumns={2}
      keyExtractor={keyExtractor}
      renderItem={renderItem}
      ListEmptyComponent={
        !isLoading ? (
          <View className="flex-1 items-center justify-center pt-20 gap-3">
            <IconSymbol
              name="magnifyingglass"
              size={36}
              color={PlatformColor("tertiaryLabel") as unknown as string}
            />
            <Text className="text-muted text-base text-center">
              Aucun résultat pour "{debouncedSearch}"
            </Text>
          </View>
        ) : null
      }
    />
  );
}
