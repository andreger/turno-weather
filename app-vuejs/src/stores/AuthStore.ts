import { useApi } from "@/util/useApi";
import { User } from "@/interfaces/User";
import { defineStore } from "pinia";
import { ref, Ref } from "vue";
import { useAlertStore } from "./AlertStore";
import router from "@/router";

const userKey = "user";
const tokenKey = "token";

export const useAuthStore = defineStore("auth", () => {
  const { requestPost } = useApi();
  const alertStore = useAlertStore();

  const user: Ref<User | null> = ref(
    JSON.parse(localStorage.getItem("user") || "null")
  );

  const login = async (email: string, password: string) => {
    try {
      const data = await requestPost("/login", { email, password });

      user.value = data.data.user;
      localStorage.setItem(tokenKey, data.data.token);     
      localStorage.setItem(userKey, JSON.stringify(data.data.user));

      alertStore.clear();
    } catch (err) {
      alertStore.setError((err as Error).message);
    }
  };

  const logout = async () => {
    try {
      await requestPost("/logout");

      clean();

      alertStore.clear();
    } catch (err) {
      alertStore.setError((err as Error).message);
    }
    
  };

  const clean = () => {
    user.value = null;
    localStorage.removeItem(userKey);
    localStorage.removeItem(tokenKey);
    router.push({ name: "login" });
  };

  return { user, login, logout, clean };
});
