<template>
  <div class="logged-user" v-if="user">
    <div>Hi, {{ userName }}</div>
    <div class="action" @click="logout">Logout</div>
  </div>
</template>

<script setup lang="ts">
import router from "@/router";
import { useAuthStore } from "@/stores/AuthStore";
import { storeToRefs } from "pinia";
import { computed, ComputedRef } from "vue";

const authStore = useAuthStore();
const { user } = storeToRefs(authStore);

const userName: ComputedRef<string> = computed(() => user?.value?.name || "");

const logout = () => {
  authStore.logout();
  router.push({ name: "login" });
};
</script>

<style scoped>
.logged-user {
  text-align: right;
}
</style>
