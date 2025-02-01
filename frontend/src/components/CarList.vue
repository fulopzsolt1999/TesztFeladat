<template>
   <table class="table table-striped table-hover table-bordered table-responsive table-light">
     <thead>
       <tr class="text-center text-uppercase">
         <th>Car ID</th>
         <th>Type</th>
         <th>Registered</th>
         <th>Own Brand</th>
         <th>Accident</th>
         <th>Newest Event</th>
         <th>Event Date</th>
       </tr>
     </thead>
     <tbody>
       <template v-for="car in cars" :key="car.car_id">
         <tr>
           <td><button @click="selectCar(car.car_id, car.registered)">{{ car.car_id }}</button></td>
           <td>{{ car.type }}</td>
           <td>{{ car.registered }}</td>
           <td>{{ car.ownbrand }}</td>
           <td>{{ car.accidents }}</td>
           <td>{{ car.newest_event }}</td>
           <td>{{ car.newest_event_date }}</td>
         </tr>
         <tr v-if="selectedCarId === car.car_id">
           <td colspan="7">
             <ServiceList :client-id="clientId" :car-id="car.car_id" :car-registered="car.registered"/>
           </td>
         </tr>
       </template>
     </tbody>
   </table>
 </template>
 
 <script setup lang="ts">
 import { ref, watch } from 'vue';
 import { defineProps, defineEmits } from 'vue';
 import ServiceList from './ServiceList.vue';
 import { getCars } from '../services/api';
 
 const props = defineProps<{
   clientId: number;
 }>();
 
 const emit = defineEmits(['select-car']);
 
 const cars = ref([]);
 const selectedCarId = ref<number | null>(null);
 const selectedCarRegistered = ref<string | null>(null);
 
 const selectCar = async (carId: number, carRegistered: string) => {
   if (selectedCarId.value === carId) {
      selectedCarId.value = null;
      selectedCarRegistered.value = null;
   } else {
     selectedCarId.value = carId;
     selectedCarRegistered.value = carRegistered;
     emit('select-car', carId);
   }
 };
 
 watch(() => props.clientId, async (newClientId) => {
   if (newClientId !== null) {
     try {
       const response = await getCars(newClientId);
       if (response.data && Array.isArray(response.data.cars)) {
         cars.value = response.data.cars;
       } else {
         console.error('Invalid response format:', response.data);
         cars.value = [];
       }
     } catch (error) {
       console.error('Error fetching client cars:', error);
       cars.value = [];
     }
   }
 }, { immediate: true });
 </script>