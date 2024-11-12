<template>
  <div class="single-location">
    <div class="title-bar" @click="toggleForecasts">
      <span class="location">{{ location.city }}</span>
      <span class="action">
        {{ showForecasts ? "Hide" : "Show" }}
      </span>
    </div>
    <div v-if="showForecasts">
      <ForecastList :forecasts="location.forecasts" />
      <div class="action delete">
        <span @click="deleteLocation(location.id)">Delete</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useLocationStore } from "@/stores/LocationStore";
import { defineProps, ref, Ref } from "vue";
import ForecastList from "../forecast/ForecastList.vue";
import { Location } from "@/interfaces/Location";

defineProps<{ location: Location} >();

const locationStore = useLocationStore();
const showForecasts: Ref<boolean> = ref(false);

const deleteLocation = (id: number) => {
  locationStore.remove(id);
};

const toggleForecasts = () => {
  showForecasts.value = !showForecasts.value;
};
</script>

<style scoped>
.action.delete {
  text-align: right;
}

.location {
  font-size: 1.75rem;
}

.single-location {
  background: var(--green);
  border-radius: 10px;
  color: var(--white);
  margin: 10px auto;
  max-width: 420px;
  padding: 20px;
  text-align: left;
}

.title-bar {
  align-items: center;
  display: flex;
  justify-content: space-between;
}
</style>
