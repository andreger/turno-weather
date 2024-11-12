import { useAuthStore } from "@/stores/AuthStore";

export const useApi = () => {
  const baseUrl = "http://localhost:8080/api";

  const request = async (endpoint: string, options: RequestInit = {}) => {
    try {
      const response = await fetch(`${baseUrl}${endpoint}`, {
        ...options,
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          ...options.headers,
        },
      });

      if (response.status === 401) {
        const authStore = useAuthStore();
        authStore.clean();
      }

      let data = null;
      if (response.status !== 204) {
        data = await response.json();
      }

      if (!response.ok) {
        const errorMessage =
          data && data.message ? data.message : response.statusText;
        throw new Error(errorMessage);
      }

      return data;
    } catch (err) {
      throw new Error((err as Error).message);
    }
  };

  const requestGet = async (endpoint: string) => {
    return await request(endpoint, { method: "GET" });
  };

  const requestPost = async (endpoint: string, body: object | null = null) => {
    return await request(endpoint, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: body ? JSON.stringify(body) : '',
    });
  };

  const requestDelete = async (endpoint: string) => {
    return await request(endpoint, { method: "DELETE" });
  };

  return { requestGet, requestPost, requestDelete };
};
