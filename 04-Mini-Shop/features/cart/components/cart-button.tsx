import { Ionicons } from "@expo/vector-icons";
import { useRouter } from "expo-router";
import { useThemeColor } from "heroui-native";
import { Pressable, Text, View } from "react-native";
import { useCartStore } from "../store/cart-store";

const CartButton = () => {
  const router = useRouter();
  const [accent] = useThemeColor(["accent"]);
  const totalItems = useCartStore((state) => state.totalItems());

  return (
    <Pressable
      onPress={() => router.push("/cart")}
      hitSlop={8}
      className="relative pr-2 pt-1 pl-1 pb-1"
    >
      <Ionicons name="bag-handle-outline" size={24} color={accent} />

      {totalItems >= 0 && (
        <View
          className="absolute top-0 right-0 bg-accent rounded-full items-center justify-center"
          style={{ width: 16, height: 16 }}
        >
          <Text
            className="text-accent-foreground font-bold"
            style={{ fontSize: 9 }}
          >
            {totalItems}
          </Text>
        </View>
      )}
    </Pressable>
  );
};

export default CartButton;
