import { goeyToast } from "goey-toast";
import type { GoeyToastOptions } from "goey-toast";

const COLORS: Record<string, { fillColor: string; borderColor: string }> = {
  success: {
    fillColor: "rgba(34, 197, 94, 0.1)",
    borderColor: "rgba(34, 197, 94, 0.2)",
  },
  warning: {
    fillColor: "rgba(245, 158, 11, 0.1)",
    borderColor: "rgba(245, 158, 11, 0.2)",
  },
  error: {
    fillColor: "rgba(239, 68, 68, 0.1)",
    borderColor: "rgba(239, 68, 68, 0.2)",
  },
  info: {
    fillColor: "rgba(59, 130, 246, 0.1)",
    borderColor: "rgba(59, 130, 246, 0.2)",
  },
};

type ToastType = "success" | "warning" | "error" | "info";

export const myGoeyToast = (
  type: ToastType,
  title: string,
  options?: GoeyToastOptions
) => {
  const color = COLORS[type] || COLORS.info;

  switch (type) {
    case "success":
      return goeyToast.success(title, { ...color, borderWidth: 1.5, ...options });
    case "warning":
      return goeyToast.warning(title, { ...color, borderWidth: 1.5, ...options });
    case "error":
      return goeyToast.error(title, { ...color, borderWidth: 1.5, ...options });
    case "info":
    default:
      return goeyToast.info(title, { ...color, borderWidth: 1.5, ...options });
  }
};
