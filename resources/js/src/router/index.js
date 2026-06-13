import axios from 'axios'
import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '@/views/LoginPage.vue'
import SettingsPage from '@/views/SettingsPage.vue'
import ReviewsPage from '@/views/ReviewsPage.vue'

const routes = [
  {
    path: '/',
    redirect: '/reviews'
  },
  {
    path: '/login',
    component: LoginPage,
  },
  {
    path: '/settings',
    component: SettingsPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/reviews',
    component: ReviewsPage,
    meta: { requiresAuth: true },
  },
]

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
})

router.beforeEach(async (to) => {
    if (!to.meta.requiresAuth) {
        return true
    }

    try {
        await axios.get('/api/user')
        return true
    } catch {
        return '/login'
    }
})

export default router