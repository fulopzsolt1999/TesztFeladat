<template>
  <SearchBar :search="search" />
  <div>
    <ClientList :clients="clients" />
    <CarList v-if="selectedClientId" :client-id="selectedClientId" />
    <ServiceList v-if="selectedCarId" :client-id="selectedClientId" :car-id="selectedCarId" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import SearchBar from '../components/SearchBar.vue';
import ClientList from '../components/ClientList.vue';
import CarList from '../components/CarList.vue';
import ServiceList from '../components/ServiceList.vue';
import { getClients } from '../services/api';

const search = ref('');
const clients = ref([]);
const selectedClientId = ref<number | null>(null);
const selectedCarId = ref<number | null>(null);

onMounted(async () => {
  try {
    const response = await getClients();
    if (response.data && Array.isArray(response.data)) {
      clients.value = response.data;
    } else {
      console.error('Invalid response format:', response.data);
      clients.value = [];
    }
  } catch (error) {
    console.error('Error fetching clients:', error);
    clients.value = [];
  }
});
</script>