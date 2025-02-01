<script setup lang="ts">
import { RouterView } from 'vue-router'
import Header from './components/Header.vue'
</script>

<template>
  <main class="container">
    <Header />
    <RouterView />
  </main>  
</template>