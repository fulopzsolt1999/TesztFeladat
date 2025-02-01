<template>
  <!-- Search form -->
  <form @submit.prevent="handleSubmit" class="container w-75 mt-5">
    <div class="row">
      <div class="col mb-3">
        <input type="text" v-model="localSearch.name" class="form-control" placeholder="Ügyfél név...">
      </div>
      <div class="col mb-3">
        <input type="text" v-model="localSearch.document_id" class="form-control" placeholder="Ügyfél okmányazonosító...">
      </div>
    </div>
    <div class="d-flex justify-content-center"><button type="submit" class="btn btn-primary mb-3 w-50">Keresés</button></div>
    <!-- Errors from searching -->
    <div v-if="error" class="alert alert-danger">{{ error }}</div>
  </form>
  <!-- Searched client data -->
  <div v-if="clientData">
    <h3 class="my-3 pb-2 w-25 border-bottom border-2 border-dark">Keresett ügyfél információ</h3>
    <table class="table table-striped table-hover table-bordered table-responsive table-dark">
      <thead>
        <tr class="text-center align-middle text-uppercase">
          <th scope="col">Ügyfél azonosító</th>
          <th scope="col">Ügyfél név</th>
          <th scope="col">Ügyfél okmányazonosító</th>
          <th scope="col">Autók száma</th>
          <th scope="col">Összes szervíznapló bejegyzések száma</th>
        </tr>
      </thead>
      <tbody>
        <tr class="text-center align-middle">
          <td>{{ clientData.id }}</td>
          <td>{{ clientData.name }}</td>
          <td>{{ clientData.document_id }}</td>
          <td>{{ clientData.numberOfCars }}</td>
          <td>{{ clientData.totalServiceLogs }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue';
import { getClientByName, getClientByDocumentId, getCars, getServices } from '../services/api';

interface Client {
  id: number;
  name: string;
  document_id: string;
}

interface ClientData {
  id: number;
  name: string;
  document_id: string;
  numberOfCars: number;
  totalServiceLogs: number;
}

export default defineComponent({
  props: {
    search: {
      type: String,
      required: true
    }
  },
  emits: ['update:search'],
  setup(props, { emit }) {
    const localSearch = ref({ name: '', document_id: '' });
    const error = ref<string | null>(null);
    const clientData = ref<ClientData | null>(null);

    watch(localSearch, (newValue) => {
      emit('update:search', newValue);
    });
    /* Input validation */
    const validateInput = () => {
      if (!localSearch.value.name && !localSearch.value.document_id) {
        error.value = 'Az egyik mezőt kötelező kitölteni.';
        return false;
      }
      if (localSearch.value.name && localSearch.value.document_id) {
        error.value = 'Csak az egyik mezőt töltse ki.';
        return false;
      }
      if (localSearch.value.document_id && !/^[a-zA-Z0-9]+$/.test(localSearch.value.document_id)) {
        error.value = 'Az okmányazonosító csak betűket és számokat tartalmazhat.';
        return false;
      }
      error.value = null;
      return true;
    };
    /* Submit form */
    const handleSubmit = async () => {

      if (!validateInput()) return;

      try {
        let client: Client | null = null;
        if (localSearch.value.name) {
          const response = await getClientByName(localSearch.value.name);
          if (response.data.length > 1) {
            error.value = 'Pontosítson a keresési feltételeken.';
            clientData.value = null;
            return;
          } else {
            client = response.data[0];
          }
        } else if (localSearch.value.document_id) {
          const response = await getClientByDocumentId(localSearch.value.document_id);
          client = response.data[0];
        }
        /* Fetch cars and services */
        if (client) {
          const carsResponse = await getCars(client.id);
          if (!Array.isArray(carsResponse.data.cars)) {
            throw new Error('carsResponse.data.cars is not an array');
          }

          const numberOfCars = carsResponse.data.cars.length;
          let totalServiceLogs = 0;

          for (const car of carsResponse.data.cars) {
            const servicesResponse = await getServices(client.id, car.car_id);
            if (!Array.isArray(servicesResponse.data.services)) {
              throw new Error('servicesResponse.data is not an array');
            }
            totalServiceLogs += servicesResponse.data.services.length;
          }

          clientData.value = {
            id: client.id,
            name: client.name,
            document_id: client.card_number,
            numberOfCars: numberOfCars,
            totalServiceLogs: totalServiceLogs
          };
        }
      } catch {
        error.value = 'Error fetching client data.';
        clientData.value = null;
      }
    };

    return {
      localSearch,
      error,
      clientData,
      handleSubmit
    };
  }
});
</script>

<style scoped>

  button {
    height: 3rem;
    background-color: #a0a0a0;
    border: none;
    color: black;
    transition: all 0.3s ease !important;
  }

  button:hover {
    background-color: #333333 !important;
    color: white !important;
  }

  input {
    height: 3rem;
    border: none;
    background-color: #a0a0a0;
    color: black;
    transition: all 0.3s ease !important;
  }
  
  input:focus {
    background-color: #333333 !important;
    color: white !important;
  }

  input:focus::placeholder {
    color: white;
  }
</style>