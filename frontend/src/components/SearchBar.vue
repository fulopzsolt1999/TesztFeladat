<script setup lang="ts">
import { defineProps, defineEmits, ref, watch } from 'vue';

const props = defineProps<{
  search: string;
}>();

const emit = defineEmits(['update:search']);
const localSearch = ref(props.search);

watch(localSearch, (newValue) => {
  emit('update:search', newValue);
});
</script>

<template>
  <div>
      <input type="text" v-model="localSearch" placeholder="Search..." />
      <button type="submit">Search</button>
  </div>
</template>
 
<script lang="ts">
   export default {
      name: 'SearchBar'
   }
</script>