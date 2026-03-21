import { Stack } from "expo-router";

export default function HomeLayout() {
  return (
    <Stack>
      <Stack.Screen
        name="index"
        options={{
          headerLargeTitle: true,
          title: "Accueil",
          headerSearchBarOptions: {
            placeholder: "Rechercher...",
            onChangeText: (event) => console.log(event.nativeEvent.text),
            autoCapitalize: "none",
            obscureBackground: true,
            placement: "automatic",
          },
        }}
      />
    </Stack>
  );
}
