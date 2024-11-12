<template>
  <div>
    <form @submit="login">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" v-model="email" placeholder="Enter email" />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input
          type="password"
          v-model="password"
          placeholder="Enter password"
        />
      </div>
      <div class="form-submit-group">
        <button type="submit" :disabled="!canClick">Submit</button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import router from "@/router";
import { useAuthStore } from "@/stores/AuthStore";
import { computed, Ref, ref } from "vue";

const authStore = useAuthStore();
const email: Ref<string> = ref("");
const password: Ref<string> = ref("");
const proccessing: Ref<boolean> = ref(false);

const canClick = computed(() => email.value && password.value && !proccessing.value);

const login = async (e: Event) => {
  e.preventDefault();
  proccessing.value = true;
  await authStore.login(email.value, password.value);
  router.push({ name: "home" });
  proccessing.value = false;
};
</script>

<style>
.form-submit-group {
  text-align: center;
  margin-top: 20px;
}
</style>
