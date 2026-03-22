// shared/hooks/use-search.ts
import { useNavigation } from "expo-router";
import { useEffect, useState } from "react";

export function useSearch() {
  const [search, setSearch] = useState("");
  const [isActive, setIsActive] = useState(false);
  const navigation = useNavigation();

  useEffect(() => {
    navigation.setOptions({
      headerSearchBarOptions: {
        placeholder: "Rechercher...",
        autoCapitalize: "none",
        obscureBackground: false,
        placement: "automatic",
        onChangeText: (e: any) => setSearch(e.nativeEvent.text),
        onFocus: () => setIsActive(true),
        onCancelButtonPress: () => {
          setSearch("");
          setIsActive(false);
        },
      },
    });
  }, [navigation]);

  return { search, isActive };
}
