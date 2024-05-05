import HomeLayout from "@/pages/HomeLayout.vue";
import PageNotFound from "@/pages/PageNotFound.vue";
import AdminLayout from "@/pages/admin/AdminLayout.vue";
import DashboardContent from "@/pages/admin/contents/DashboardContent.vue";
import TeacherContent from "@/pages/admin/contents/TeacherContent.vue";
import StudentContent from "@/pages/admin/contents/StudentContent.vue";
import ProfileContent from "@/pages/admin/contents/ProfileContent.vue";
import LoginView from "@/pages/auth/LoginView.vue";
import { createRouter, createWebHistory } from "vue-router";
import ManagementLayout from "@/pages/admin/contents/managements/ManagementLayout.vue";
import DepartmentContent from "@/pages/admin/contents/managements/DepartmentContent.vue";
import ClassManagement from "@/pages/admin/contents/managements/ClassManagement.vue";
import AttendanceManagement from "@/pages/admin/contents/managements/AttendanceManagement.vue";

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
      component: AdminLayout,
      children: [
        {
          path: "dashboard",
          component: DashboardContent,
        },
        {
          path: "management",
          component: ManagementLayout,
          children: [
            {
              path: "teachers",
              component: TeacherContent,
            },
            {
              path: "students",
              component: StudentContent,
            },
            {
              path: "classes",
              component: ClassManagement,
            },
            {
              path: "attendance",
              component: AttendanceManagement,
            },
            {
              path: "departments",
              component: DepartmentContent,
            },
          ],
        },

        {
          path: "profile",
          component: ProfileContent,
        },
      ],
    },
    {
      path: "/:pathMatch(.*)",
      component: PageNotFound,
    },
  ],
});

export default router;
