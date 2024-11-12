<template>
  <div class="home">
    <div v-if="showAddLocationForm" class="mt-4">
      <AddLocationForm />
    </div>
    <template v-if="loadingList">
      <LoadingSpinner />
    </template>
    <template v-else>
      <LocationList :locations="locations" :loading="loadingList" />
    </template>
  </div>
</template>

<script setup lang="ts">
import LoadingSpinner from "@/components/common/LoadingSpinner.vue";
import AddLocationForm from "@/components/location/AddLocationForm.vue";
import LocationList from "@/components/location/LocationList.vue";
import { useLocationStore } from "@/stores/LocationStore";
import { storeToRefs } from "pinia";
import { computed } from "vue";

const locationStore = useLocationStore();
const { locations, loadingList } = storeToRefs(locationStore);
const showAddLocationForm = computed(() => locations.value.length < 3);

locationStore.list();
</script>
