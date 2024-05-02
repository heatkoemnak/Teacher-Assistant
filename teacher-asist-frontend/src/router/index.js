import HomeLayout from "@/pages/HomeLayout.vue";
import DashboardLayout from "@/pages/admin/DashboardLayout.vue";
import DashboardContent from "@/pages/admin/contents/DashboardContent.vue";
import ProfileContent from "@/pages/admin/contents/ProfileContent.vue";
import LoginView from "@/pages/auth/LoginView.vue";
import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home-layout",
      component: HomeLayout,
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
    },
    {
      path: "/admin",
      name: "dashboard",
      component: DashboardLayout,
      children: [
        {
          path: "dashboard",
          name: "dashboard",
          component: DashboardContent,
        },
        {
          path: "profile",
          name: "profile",
          component: ProfileContent,
        },
      ],
    },
  ],
});

export default router;
