<template>
  <table class="table table-striped table-hover table-bordered table-responsive table-dark">
    <thead>
      <tr class="text-center text-uppercase">
        <th>Ügyfél azonosító</th>
        <th>Név</th>
        <th>Okmányazonosító</th>
      </tr>
    </thead>
    <tbody>
      <template v-for="client in props.clients" :key="client.id">
        <tr>
          <td>{{ client.id }}</td>
          <td><button @click="selectClient(client.id)">{{ client.name }}</button></td>
          <td>{{ client.card_number }}</td>
        </tr>
        <tr v-if="selectedClientId === client.id">
          <td colspan="3">
            <!-- Show results -->
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

const selectClient = (clientId: number) => {
  if (selectedClientId.value === clientId) {
    selectedClientId.value = null; // Unselect if the same client is clicked
  } else {
    selectedClientId.value = clientId;
    emit('select-client', clientId);
  }
};
</script>