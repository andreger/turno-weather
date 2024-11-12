<template>
  <div class="single-forecast">
    <div>
      <img :src="forecast.icon" />
    </div>
    <div>
      <div class="condition-at">{{ conditionAt }}</div>
      <div>{{ temperature }}&deg; {{ forecast.description }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ComputedRef, defineProps } from "vue";
const props = defineProps(["forecast"]);

const temperature: ComputedRef<number> = computed(() => Math.round(props.forecast.temperature));

const conditionAt: ComputedRef<string> = computed(() => {
  const date = new Date(props.forecast.condition_at);
  return date.toLocaleString("en-US", {
    month: "short",
    day: "numeric",
    hour: "numeric",
  });
});
</script>

<style scoped>
.condition-at {
  font-weight: bold;
}

.single-forecast {
  align-items: center;
  display: flex;
  font-size: 0.8rem;
  margin: 5px 0;
}
</style>
