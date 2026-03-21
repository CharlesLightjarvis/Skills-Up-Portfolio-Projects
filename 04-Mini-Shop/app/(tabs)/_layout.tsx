import { Icon, Label, NativeTabs } from "expo-router/unstable-native-tabs";
import { useCSSVariable } from "uniwind";

export default function TabLayout() {
  const tintColor = useCSSVariable("--color-tint") as string;
  return (
    <NativeTabs minimizeBehavior="onScrollDown" tintColor={tintColor}>
      <NativeTabs.Trigger name="(home)">
        <Icon
          sf={{ default: "square.grid.2x2", selected: "square.grid.2x2.fill" }}
        />
        <Label>Produits</Label>
      </NativeTabs.Trigger>

      <NativeTabs.Trigger name="search" role="search">
        <Icon
          sf={{
            default: "magnifyingglass",
            selected: "magnifyingglass",
          }}
        />
        <Label>Search</Label>
      </NativeTabs.Trigger>
    </NativeTabs>
  );
}
