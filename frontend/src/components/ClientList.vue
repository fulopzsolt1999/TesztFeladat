<template>
  <!-- Clients list -->
  <h3 class="mb-3 mt-5 pb-2 w-25 border-bottom border-2 border-dark">Összes ügyfél</h3>
  <table class="table table-striped table-hover table-bordered table-responsive table-dark">
    <thead>
      <tr class="text-center align-middle text-uppercase">
        <th>Ügyfél azonosító</th>
        <th>Ügyfél név</th>
        <th>Ügyfél okmányazonosító</th>
      </tr>
    </thead>
    <tbody>
      <template v-for="client in props.clients" :key="client.id">
        <tr class="text-center align-middle">
          <td>{{ client.id }}</td>
          <td><button @click="selectClient(client.id)" class="btn w-100 text-white">{{ client.name }}</button></td>
          <td>{{ client.card_number }}</td>
        </tr>
        <!-- Car list for active client -->
        <tr v-if="selectedClientId === client.id">
          <td colspan="3">
            <CarList :client-id="client.id" />
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { defineProps, defineEmits } from 'vue';
import CarList from './CarList.vue';

const props = defineProps<{
  clients: Array<{ id: number; name: string; card_number: string }>;
}>();

const emit = defineEmits(['select-client']);

const selectedClientId = ref<number | null>(null);

/* Select and unselect client */
const selectClient = (clientId: number) => {
  if (selectedClientId.value === clientId) {
    selectedClientId.value = null; // Unselect if the same client is clicked
  } else {
    selectedClientId.value = clientId;
    emit('select-client', clientId);
  }
};
</script>