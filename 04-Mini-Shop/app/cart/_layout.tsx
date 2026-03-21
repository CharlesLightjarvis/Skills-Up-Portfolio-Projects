import { Stack, useRouter } from "expo-router";
import { SymbolView } from "expo-symbols";
import { useThemeColor } from "heroui-native";
import { TouchableOpacity } from "react-native";

export default function CartLayout() {
  const router = useRouter();
  const [defaultForeground] = useThemeColor(["default-foreground"] as const);

  return (
    <Stack>
      <Stack.Screen
        name="index"
        options={{
          headerLargeTitle: true,
          title: "Mon Panier",
          headerLeft: () => (
            <TouchableOpacity onPress={() => router.back()} className="ml-1">
              <SymbolView
                name="chevron.left"
                tintColor={defaultForeground}
                weight="semibold"
              />
            </TouchableOpacity>
          ),
        }}
      />
    </Stack>
  );
}
