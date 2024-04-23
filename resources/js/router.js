import { createWebHistory, createRouter } from "vue-router";
const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../js/components/Home')
    },
    {
        path: '/product',
        name: 'product',
        component: () => import('../js/components/Product')
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
  });

export default router;
