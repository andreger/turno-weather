<template>
  <div v-for="location in locations" :key="location.id">
    <SingleLocation :location="location" />
  </div>
</template>

<script setup lang="ts">
import SingleLocation from "./SingleLocation.vue";
import { defineProps } from "vue";

defineProps(["locations"]);
</script>
