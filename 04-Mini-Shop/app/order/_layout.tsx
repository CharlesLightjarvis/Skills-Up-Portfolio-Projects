import { Stack } from "expo-router";

export default function OrderLayout() {
  return (
    <Stack>
      <Stack.Screen
        name="confirmation"
        options={{
          headerShown: false,
        }}
      />
    </Stack>
  );
}
