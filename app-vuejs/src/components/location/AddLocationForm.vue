<template>
  <div class="add-location-form">
    <button @click="openForm" class="action" v-if="!showAddLocationForm">Add Location</button>
    <form @submit="addLocation" v-if="showAddLocationForm">
      <div class="form-group">
        <label for="city">City</label>
        <input type="text" v-model="city" placeholder="Enter city" />
      </div>
      <div class="form-group">
        <label for="state">State</label>
        <input type="text" v-model="state" placeholder="Enter state" />
      </div>
      <div class="form-submit-group">
        <button type="submit" :disabled="!canSave">Save</button>
        <span class="action" @click="closeForm">Close</span>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { computed, ComputedRef, Ref, ref } from "vue";
import { useLocationStore } from "@/stores/LocationStore";
import { useAlertStore } from "@/stores/AlertStore";

const alertStore = useAlertStore();
const locationStore = useLocationStore();

const city: Ref<string> = ref("");
const state: Ref<string> = ref("");
const showAddLocationForm: Ref<boolean> = ref(false);

const canSave: ComputedRef<boolean> = computed(() => !!city.value && !locationStore.loadingAdd);

const addLocation = async (e: Event) => {
  e.preventDefault();

  await locationStore.add({
    city: city.value,
    state: state.value,
  });

  clearFields();
};

const openForm = () => {
  showAddLocationForm.value = true;
};

const closeForm = () => {
  clearFields();
  alertStore.clear();
  showAddLocationForm.value = false;
};

const clearFields = () => {
  city.value = "";
  state.value = "";
}
</script>

<style scoped>
.add-location-form {
  margin-bottom: 30px;

  & .action {
    margin-left: 10px;
  }
}
</style>