import { useCart } from "@/features/cart/hooks/use-cart";
import { Ionicons } from "@expo/vector-icons";
import { useRouter } from "expo-router";
import { Button, useThemeColor } from "heroui-native";
import { useEffect, useState } from "react";
import { ScrollView, Text, TouchableOpacity, View } from "react-native";

export default function OrderConfirmationScreen() {
  const router = useRouter();
  const [showEmailNotif, setShowEmailNotif] = useState(true);
  const [accent, accentForeground, success] = useThemeColor([
    "accent",
    "accent-foreground",
    "success",
  ] as const);

  const { totalPrice, clearCart } = useCart();
  const [confirmedTotal] = useState(totalPrice); // snapshot avant clear

  useEffect(() => {
    clearCart();
  }, []);

  return (
    <ScrollView
      className="flex-1 bg-background"
      contentInsetAdjustmentBehavior="automatic"
      showsVerticalScrollIndicator={false}
    >
      <View className="flex-1 px-5 pt-12 pb-10 items-center">
        {/* Success icon */}
        <View className="mb-8 items-center justify-center">
          <View className="w-28 h-28 rounded-full bg-success-soft items-center justify-center">
            <View className="w-20 h-20 rounded-full bg-success items-center justify-center">
              <Ionicons name="checkmark" size={36} color="white" />
            </View>
          </View>
        </View>

        <Text className="text-[34px] font-black text-foreground text-center leading-10 mb-4">
          Commande{"\n"}Confirmée !
        </Text>

        <Text className="text-base text-muted text-center leading-6 mb-8">
          Merci pour votre achat. Votre commande{" "}
          <Text className="font-bold text-foreground">#1234</Text> a été
          enregistrée avec succès.
        </Text>

        <View className="w-full gap-4 mb-8">
          {/* Montant total */}
          <View className="bg-surface rounded-2xl p-5 w-full shadow-surface">
            <Text className="text-[11px] font-bold text-muted tracking-widest mb-2">
              MONTANT TOTAL
            </Text>
            <Text className="text-[32px] font-black text-foreground mb-2">
              {confirmedTotal.toFixed(2).replace(".", ",")} €
            </Text>
            <View className="flex-row items-center gap-1.5">
              <Ionicons name="shield-checkmark" size={16} color={success} />
              <Text className="text-sm font-semibold text-success">
                Payé par carte
              </Text>
            </View>
          </View>

          {/* Livraison estimée */}
          <View className="bg-surface rounded-2xl p-5 w-full shadow-surface">
            <Text className="text-[11px] font-bold text-muted tracking-widest mb-2">
              LIVRAISON ESTIMÉE
            </Text>
            <Text className="text-[28px] font-black text-foreground mb-2">
              14 — 16 Oct.
            </Text>
            <View className="flex-row items-center gap-1.5">
              <Ionicons name="car" size={16} color={accent} />
              <Text className="text-sm text-muted">Standard Eco</Text>
            </View>
          </View>
        </View>

        <View className="w-full border-t border-separator mb-8" />

        <Button
          size="lg"
          className="w-full mb-6"
          onPress={() => router.dismissAll()}
        >
          <Button.Label className="tracking-widest">
            CONTINUER MES ACHATS
          </Button.Label>
          <Ionicons name="arrow-forward" size={18} color={accentForeground} />
        </Button>

        <TouchableOpacity className="flex-row items-center gap-2 mb-6">
          <Ionicons name="receipt-outline" size={18} color={accent} />
          <Text className="text-sm font-bold text-accent tracking-widest">
            VOIR LA FACTURE PDF
          </Text>
        </TouchableOpacity>

        {showEmailNotif && (
          <View className="bg-surface rounded-2xl p-4 w-full flex-row items-start gap-3 shadow-surface">
            <View className="w-9 h-9 rounded-full bg-success-soft items-center justify-center mt-0.5">
              <Ionicons name="mail" size={18} color={success} />
            </View>
            <View className="flex-1">
              <Text className="text-sm font-bold text-foreground mb-0.5">
                Email de confirmation envoyé
              </Text>
              <Text className="text-sm text-muted">
                Vérifiez votre boîte de réception
              </Text>
            </View>
            <TouchableOpacity onPress={() => setShowEmailNotif(false)}>
              <Ionicons name="close" size={18} color={success} />
            </TouchableOpacity>
          </View>
        )}
      </View>
    </ScrollView>
  );
}
