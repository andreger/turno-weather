<template>
  <div>
    <div v-for="forecast in forecasts" :key="forecast.id">
      <SingleForecast :forecast="forecast" />
    </div>
  </div>
</template>

<script setup lang="ts">
import SingleForecast from "./SingleForecast.vue";
import { defineProps } from "vue";

defineProps(["forecasts"]);
</script>
