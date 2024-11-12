<template>
  <div v-if="message" :class="`alert-${type}`">
    {{ message }}
  </div>
</template>

<script setup lang="ts">
import { useAlertStore } from "@/stores/AlertStore";
import { storeToRefs } from "pinia";

const alertStore = useAlertStore();
const { message, type } = storeToRefs(alertStore);
</script>

<style scoped>
.alert-error {
  color: var(--dark-red);
}
</style>
