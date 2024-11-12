import { useApi } from "@/util/useApi";
import { User } from "@/interfaces/User";
import { defineStore } from "pinia";
import { ref, Ref } from "vue";
import { useAlertStore } from "./AlertStore";

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

  const logout = () => {
    user.value = null;
    localStorage.removeItem("user");
    localStorage.removeItem("token");
  };

  return { user, login, logout };
});
