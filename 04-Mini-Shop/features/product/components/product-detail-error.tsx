import { IconSymbol } from "@/components/ui/icon-symbol";
import { Button } from "heroui-native";
import { PlatformColor, Text, View } from "react-native";

interface ProductDetailErrorProps {
  message?: string;
  onRetry?: () => void;
}

const ProductDetailError = ({
  message = "Something went wrong. Please try again.",
  onRetry,
}: ProductDetailErrorProps) => {
  return (
    <View className="flex-1 bg-background items-center justify-center gap-4 p-6">
      <IconSymbol
        name="exclamationmark.triangle.fill"
        size={48}
        color={PlatformColor("systemRed") as unknown as string}
      />
      <View className="items-center gap-1">
        <Text className="text-base font-semibold text-foreground">Oops!</Text>
        <Text className="text-sm text-muted text-center">{message}</Text>
      </View>
      {onRetry && (
        <Button onPress={onRetry} variant="tertiary" size="sm">
          <Button.Label>Try again</Button.Label>
        </Button>
      )}
    </View>
  );
};

export default ProductDetailError;
