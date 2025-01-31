<template>
  <main class="container">
    <div>
    <h1>Car Services</h1>
    <table class="table table-striped table-hover table-bordered table-responsive table-dark">
      <thead>
        <tr class="text-center text-uppercase">
          <th>Ügyfél azonosító</th>
          <th>Név</th>
          <th>Okmányazonosító</th>
        </tr>
      </thead>
      <tbody v-for="client in clients" :key="client.id">
        <tr>
          <td>{{ client.id }}</td>
          <td><button @click="showClientCars(client.id)">{{ client.name }}</button></td>
          <td>{{ client.card_number }}</td>
        </tr>
        <tr v-if="selectedClientId === client.id">
          <td colspan="3">
            <table class="table table-striped table-hover table-bordered table-responsive table-light">
              <thead>
                <tr class="text-center text-uppercase">
                  <th>Autó sorszáma</th>
                  <th>Autó típusa</th>
                  <th>Regisztrálás időpontja</th>
                  <th>Saját márkás-e</th>
                  <th>Balesetek száma</th>
                  <th>Legfrisebb esemény</th>
                  <th>Legfrisebb esemény dátuma</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="car in cars" :key="car.car_id">
                  <td>{{ car.car_id }}</td>
                  <td>{{ car.type }}</td>
                  <td>{{ car.registered }}</td>
                  <td>{{ car.ownbrand }}</td>
                  <td>{{ car.accidents }}</td>
                  <td>{{ car.newest_event }}</td>
                  <td>{{ car.newest_event_date }}</td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  </main>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

interface Client {
  id: number;
  name: string;
  card_number: string;
}

interface Car {
  car_id: number;
  type: string;
  registered: string;
  ownbrand: string;
  accident: string;
  newest_event?: string;
  newest_event_date?: string;
}

const clients = ref<Client[]>([]);
const cars = ref<Car[]>([]);
const selectedClientId = ref<number | null>(null);

onMounted(async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/clients');
    clients.value = response.data;
  } catch (error) {
    console.error('Error fetching clients:', error);
  }
});

const showClientCars = async (clientId: number) => {
  if (selectedClientId.value === clientId) {
    selectedClientId.value = null;
    cars.value = [];
  } else {
    try {
      const response = await axios.get(`http://127.0.0.1:8000/api/clients/${clientId}/get-cars`);
      cars.value = response.data.cars;
      selectedClientId.value = clientId;
    } catch (error) {
      console.error('Error fetching client cars:', error);
    }
  }
};
</script>