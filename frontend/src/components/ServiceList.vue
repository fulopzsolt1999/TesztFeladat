<template>
  <!-- Services list -->
   <table class="table table-striped table-hover table-bordered table-responsive table-light">
     <thead>
       <tr class="text-center align-middle text-uppercase">
         <th>Alkalom sorszáma</th>
         <th>Esemény neve</th>
         <th>Esemény dátuma</th>
         <th>Munkalap azonosító</th>
       </tr>
     </thead>
     <tbody>
       <tr v-for="service in services" :key="service.id" class="text-center align-middle">
         <td>{{ service.log_number }}</td>
         <td>{{ service.event }}</td>
         <td>{{ service.event_time }}</td>
         <td>{{ service.document_id }}</td>
       </tr>
     </tbody>
   </table>
 </template>
 
 <script setup lang="ts">
 import { ref, watch } from 'vue';
 import { defineProps } from 'vue';
 import { getServices } from '../services/api';
 
 const props = defineProps<{
   clientId: number;
   carId: number;
   carRegistered: string;
 }>();
 
 const services = ref([]);
 
 /* Watch for changes in clientId and carId */
 watch(() => [props.clientId, props.carId], async ([newClientId, newCarId]) => {
   if (newClientId !== null && newCarId !== null) {
      try {
         const response = await getServices(newClientId, newCarId);
         if (response.data && Array.isArray(response.data.services)) {
            services.value = response.data.services.map(service => {
            if (service.event === 'regisztralt') {
               service.event_time = props.carRegistered;
            }
            return service;
         });
         } else {
            console.error('Invalid response format:', response.data);
            services.value = [];
         }
      } catch (error) {
         console.error('Error fetching car services:', error);
         services.value = [];
      }
   }
 }, { immediate: true });
 </script>