<template>
  <div v-for="location in locations" :key="location.id">
    <SingleLocation :location="location" />
  </div>
</template>

<script setup lang="ts">
import { Location } from "@/types/Location";
import SingleLocation from "./SingleLocation.vue";
import { defineProps } from "vue";

defineProps<{ locations: Location[] }>();
</script>
