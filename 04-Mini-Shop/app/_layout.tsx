import CartButton from "@/features/cart/components/cart-button";
import { queryClient } from "@/shared/config/query-client";
import {
  SpaceGrotesk_300Light,
  SpaceGrotesk_400Regular,
  SpaceGrotesk_500Medium,
  SpaceGrotesk_600SemiBold,
  SpaceGrotesk_700Bold,
} from "@expo-google-fonts/space-grotesk";
import {
  DarkTheme,
  DefaultTheme,
  ThemeProvider,
} from "@react-navigation/native";
import { QueryClientProvider } from "@tanstack/react-query";
import { useFonts } from "expo-font";
import { Stack } from "expo-router";
import * as SplashScreen from "expo-splash-screen";
import { StatusBar } from "expo-status-bar";
import { HeroUINativeProvider } from "heroui-native";
import { useEffect } from "react";
import { View } from "react-native";
import { GestureHandlerRootView } from "react-native-gesture-handler";
import "react-native-reanimated";
import { SafeAreaProvider } from "react-native-safe-area-context";
import { Uniwind, useUniwind } from "uniwind";
import "../global.css";

Uniwind.setTheme("system");

SplashScreen.preventAutoHideAsync();

export default function RootLayout() {
  const { theme } = useUniwind();

  const [fontsLoaded, fontError] = useFonts({
    "SpaceGrotesk-Light": SpaceGrotesk_300Light,
    "SpaceGrotesk-Regular": SpaceGrotesk_400Regular,
    "SpaceGrotesk-Medium": SpaceGrotesk_500Medium,
    "SpaceGrotesk-SemiBold": SpaceGrotesk_600SemiBold,
    "SpaceGrotesk-Bold": SpaceGrotesk_700Bold,
  });

  useEffect(() => {
    if (fontsLoaded || fontError) {
      SplashScreen.hideAsync();
    }
  }, [fontsLoaded, fontError]);

  if (!fontsLoaded && !fontError) return null;

  return (
    <ThemeProvider value={theme === "dark" ? DarkTheme : DefaultTheme}>
      <GestureHandlerRootView style={{ flex: 1 }}>
        <HeroUINativeProvider>
          <SafeAreaProvider>
            <QueryClientProvider client={queryClient}>
              <Stack>
                <Stack.Screen name="(tabs)" options={{ headerShown: false }} />
                <Stack.Screen name="order" options={{ headerShown: false }} />
                <Stack.Screen name="cart" options={{ headerShown: false }} />
                <Stack.Screen
                  name="product/[id]"
                  options={{
                    headerShown: true,
                    title: "",
                    headerBackButtonDisplayMode: "minimal",
                    headerShadowVisible: false,
                    headerTransparent: true,
                    headerBackground: () => <View className="bg-background" />,
                    headerRight: () => <CartButton />,
                  }}
                />
              </Stack>
            </QueryClientProvider>
          </SafeAreaProvider>
        </HeroUINativeProvider>
      </GestureHandlerRootView>
      <StatusBar style="auto" />
    </ThemeProvider>
  );
}
