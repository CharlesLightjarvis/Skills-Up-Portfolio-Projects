import { SkeletonGroup } from "heroui-native";
import { View } from "react-native";

const ProductSkeleton = () => {
  return (
    <SkeletonGroup isLoading={true} className="flex-1 m-2">
      {/* Image */}
      <SkeletonGroup.Item className="w-full aspect-square rounded-2xl" />

      {/* Info */}
      <View className="mt-2 px-0.5 gap-1.5">
        <SkeletonGroup.Item className="h-4 w-3/4 rounded-md" />
        <SkeletonGroup.Item className="h-3 w-full rounded-md" />
        <SkeletonGroup.Item className="h-4 w-1/4 rounded-md mt-0.5" />
      </View>
    </SkeletonGroup>
  );
};

export default ProductSkeleton;
