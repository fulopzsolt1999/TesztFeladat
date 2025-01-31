import { createRouter, createWebHistory } from 'vue-router'
import CarServicesView from '../views/CarServicesView.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'carservice',
      component: CarServicesView,
    }
  ],
})

export default router
