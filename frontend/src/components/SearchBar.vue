<template>
  <form @submit.prevent="handleSubmit">
    <div class="row">
      <div class="col mb-3">
        <input type="text" v-model="localSearch.name" class="form-control" placeholder="Ügyfél név...">
      </div>
      <div class="col mb-3">
        <input type="text" v-model="localSearch.document_id" class="form-control" placeholder="Ügyfél okmányazonosító...">
      </div>
    </div>
    <button type="submit" class="btn btn-primary mb-3">Keresés</button>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-if="clientData">
      <h3>Client Information</h3>
      <table class="table table-striped table-hover table-bordered table-responsive table-dark">
        <thead>
          <tr class="text-center text-uppercase">
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
  </form>
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

    const validateInput = () => {
      if (!localSearch.value.name && !localSearch.value.document_id) {
        error.value = 'Either customer name or document ID is required.';
        return false;
      }
      if (localSearch.value.name && localSearch.value.document_id) {
        error.value = 'Only one field should be filled.';
        return false;
      }
      if (localSearch.value.document_id && !/^[a-zA-Z0-9]+$/.test(localSearch.value.document_id)) {
        error.value = 'Document ID can only contain letters and numbers.';
        return false;
      }
      error.value = null;
      return true;
    };

    const handleSubmit = async () => {
      if (!validateInput()) return;

      try {
        let client: Client | null = null;
        if (localSearch.value.name) {
          const response = await getClientByName(localSearch.value.name);
          if (response.data.length > 1) {
            error.value = 'Multiple clients found with the same name.';
            clientData.value = null;
            return;
          } else {
            client = response.data[0];
          }
        } else if (localSearch.value.document_id) {
          const response = await getClientByDocumentId(localSearch.value.document_id);
          client = response.data[0];
        }

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
      } catch (err) {
        console.error('Error fetching client data:', err);
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