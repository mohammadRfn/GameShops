// src/router.js
import { createRouter, createWebHistory } from "vue-router";
import Login from "./pages/login.vue";
import Dashboard from "./pages/Dashboard.vue";
import CustomerManagement from "./pages/CustomerManagement.vue";
import CustomersShow from "./pages/CustomerShow.vue";
import CustomerEdit from "./pages/CustomerEdit.vue";
import CustomerCreate from "./pages/CustomerCreate.vue";
import Inventory from "./pages/Inventory.vue";

const routes = [
  {
    path: "/",
    name: "Home",
    component: Login,
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
  },
  {
    path: "/customers",
    name: "CustomerManagement",
    component: CustomerManagement,
  },
  {
    path: "/customers/:id",
    name: "CustomerShow",
    component: CustomersShow,
  },
  {
    path: "/customers/:id/edit",
    name: "CustomerEdit",
    component: () => import("./pages/CustomerEdit.vue"),
  },
  {
    path: "/customers/create",
    name: "CustomerCreate",
    component: CustomerCreate,
  },
  {
    path: "/inventory",
    name: "Inventory",
    component: Inventory,
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
