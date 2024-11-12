import { defineStore } from "pinia";
import { CreateLocation, Location, LocationListResponse, LocationResponse } from "@/types/Location";
import { Ref, ref } from "vue";
import { useApi } from "@/util/useApi";
import { useAlertStore } from "./AlertStore";

export const useLocationStore = defineStore("location", () => {
  const { requestGet, requestPost, requestDelete } = useApi();
  const alertStore = useAlertStore();

  const locations: Ref<Location[]> = ref([]);
  const loadingList: Ref<boolean> = ref(false);
  const loadingAdd: Ref<boolean> = ref(false);
  const loadingRemove: Ref<boolean> = ref(false);

  const list = async () => {
    try {
      loadingList.value = true;
      const data: LocationListResponse = await requestGet("/locations");
      locations.value = data.data;

      alertStore.clear();
    } catch (err) {
      alertStore.setError((err as Error).message);
    } finally {
      loadingList.value = false;
    }
  };

  const add = async (location: CreateLocation) => {
    try {
      loadingAdd.value = true;
      const data: LocationResponse = await requestPost("/locations", location);
      locations.value.push(data.data);

      alertStore.clear();
    } catch (err) {
      alertStore.setError((err as Error).message);
    } finally {
      loadingAdd.value = false;
    }
  };

  const remove = async (id: number) => {
    try {
      loadingRemove.value = true;

      locations.value = locations.value.filter(
        (location) => location.id !== id
      );

      await requestDelete(`/locations/${id}`);

      alertStore.clear();
    } catch (err) {
      alertStore.setError((err as Error).message);
    } finally {
      loadingRemove.value = false;
    }
  };

  return { locations, loadingList, loadingAdd, loadingRemove, list, add, remove };
});
