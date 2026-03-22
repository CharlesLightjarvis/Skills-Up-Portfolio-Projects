import { IconSymbol } from "@/components/ui/icon-symbol";
import { useSearchStore } from "@/features/search/store/search-store";
import { router } from "expo-router";
import { PlatformColor, ScrollView, Text, TouchableOpacity, View } from "react-native";

const RecentSearches = () => {
  const { recentSearches, removeSearch, clearSearches } = useSearchStore();
  const validSearches = recentSearches.filter(
    (p) => p && typeof p === "object" && typeof p.price === "number",
  );

  if (validSearches.length === 0) {
    return (
      <View className="flex-1 bg-background items-center justify-center gap-3 px-6">
        <IconSymbol
          name="magnifyingglass"
          size={48}
          color={PlatformColor("tertiaryLabel") as unknown as string}
        />
        <Text className="text-lg font-semibold text-foreground">
          Recherchez un produit
        </Text>
        <Text className="text-sm text-muted text-center">
          Tapez un nom ou une description pour trouver ce que vous cherchez.
        </Text>
      </View>
    );
  }

  return (
    <ScrollView
      contentInsetAdjustmentBehavior="automatic"
      showsVerticalScrollIndicator={false}
      className="flex-1 bg-background"
    >
      <View className="px-5 pt-4">
        <View className="flex-row justify-between items-center mb-3">
          <Text className="text-[11px] font-bold text-muted tracking-widest">
            RECHERCHES RÉCENTES
          </Text>
          <TouchableOpacity onPress={clearSearches}>
            <Text className="text-[11px] font-bold text-danger tracking-widest">
              EFFACER
            </Text>
          </TouchableOpacity>
        </View>

        {validSearches.map((product) => (
          <TouchableOpacity
            key={product.id}
            onPress={() => router.push(`/product/${product.id}`)}
            className="flex-row items-center justify-between py-3 border-b border-separator-tertiary"
          >
            <View className="flex-row items-center gap-3 flex-1">
              <IconSymbol
                name="clock"
                size={18}
                color={PlatformColor("tertiaryLabel") as unknown as string}
              />
              <View className="flex-1">
                <Text className="text-base text-foreground" numberOfLines={1}>
                  {product.title}
                </Text>
                <Text className="text-xs text-muted" numberOfLines={1}>
                  ${product.price.toFixed(2)}
                </Text>
              </View>
            </View>
            <TouchableOpacity
              onPress={(e) => {
                e.stopPropagation();
                removeSearch(product.id);
              }}
            >
              <IconSymbol
                name="xmark"
                size={14}
                color={PlatformColor("tertiaryLabel") as unknown as string}
              />
            </TouchableOpacity>
          </TouchableOpacity>
        ))}
      </View>
    </ScrollView>
  );
};

export default RecentSearches;
