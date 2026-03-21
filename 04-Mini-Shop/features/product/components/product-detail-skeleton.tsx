import { SkeletonGroup } from "heroui-native";
import { ScrollView, View } from "react-native";

const ProductDetailSkeleton = () => {
  return (
    <View className="flex-1 bg-background">
      <ScrollView showsVerticalScrollIndicator={false}>
        {/* Image */}
        <SkeletonGroup isLoading className="w-full h-80" />

        <View className="px-5 pt-5 gap-3">
          {/* Collection label */}
          <SkeletonGroup isLoading>
            <SkeletonGroup.Item className="h-3 w-28 rounded-md" />
          </SkeletonGroup>

          {/* Title */}
          <SkeletonGroup isLoading className="gap-2">
            <SkeletonGroup.Item className="h-7 w-full rounded-md" />
            <SkeletonGroup.Item className="h-7 w-2/3 rounded-md" />
          </SkeletonGroup>

          {/* Price + badge */}
          <SkeletonGroup isLoading className="flex-row items-center gap-3">
            <SkeletonGroup.Item className="h-7 w-24 rounded-md" />
            <SkeletonGroup.Item className="h-6 w-20 rounded-full" />
          </SkeletonGroup>

          {/* Description label */}
          <SkeletonGroup isLoading className="gap-2 mt-2">
            <SkeletonGroup.Item className="h-3 w-24 rounded-md" />
            <SkeletonGroup.Item className="h-4 w-full rounded-md" />
            <SkeletonGroup.Item className="h-4 w-full rounded-md" />
            <SkeletonGroup.Item className="h-4 w-3/4 rounded-md" />
          </SkeletonGroup>

          {/* Spec cards */}
          <SkeletonGroup isLoading className="flex-row gap-3 mt-2">
            <SkeletonGroup.Item className="flex-1 h-16 rounded-xl" />
            <SkeletonGroup.Item className="flex-1 h-16 rounded-xl" />
          </SkeletonGroup>

          {/* Quantity */}
          <SkeletonGroup isLoading className="items-center gap-3 mt-2">
            <SkeletonGroup.Item className="h-3 w-20 rounded-md" />
            <SkeletonGroup.Item className="h-12 w-36 rounded-xl" />
          </SkeletonGroup>
        </View>
      </ScrollView>

      {/* Bottom bar */}
      <SkeletonGroup
        isLoading
        className="bg-background px-4 gap-4 border-t border-separator-tertiary pt-4 pb-8"
      >
        <SkeletonGroup.Item className="h-12 w-full rounded-xl" />
        <SkeletonGroup.Item className="h-3 w-48 rounded-md self-center" />
      </SkeletonGroup>
    </View>
  );
};

export default ProductDetailSkeleton;
