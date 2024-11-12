import { defineStore } from "pinia";
import { ref, Ref } from "vue";

export const useAlertStore = defineStore("alert", () => {
  const message: Ref<string> = ref("");
  const type: Ref<string> = ref("");

  const setError = (error: string) => {
    message.value = error;
    type.value = "error";
  };

  const clear = () => {
    message.value = "";
    type.value = "";
  };

  return { message, type, setError, clear };
});
