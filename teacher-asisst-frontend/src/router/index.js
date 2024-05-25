import HomeLayout from "@/pages/HomeLayout.vue";
import PageNotFound from "@/pages/PageNotFound.vue";
import AdminLayout from "@/pages/admin/AdminLayout.vue";
import UserLayout from "@/pages/user/UserLayout.vue";
import DashboardContent from "@/pages/admin/contents/DashboardContent.vue";
import TeacherContent from "@/pages/admin/contents/managements/TeacherContent.vue";
import StudentContent from "@/pages/admin/contents/managements/StudentContent.vue";
import ProfileContent from "@/pages/admin/contents/ProfileContent.vue";
import LoginViewAdmin from "@/pages/auth/adminScreen/LoginViewAdmin.vue";
import LoginViewUser from "@/pages/auth/userScreen/LoginViewUser.vue";
import SignupViewAdmin from "@/pages/auth/adminScreen/SignupViewAdmin.vue";
import { createRouter, createWebHistory } from "vue-router";
import ManagementLayout from "@/pages/admin/contents/managements/ManagementLayout.vue";
import DepartmentContent from "@/pages/admin/contents/managements/DepartmentContent.vue";
import ClassManagement from "@/pages/admin/contents/managements/ClassManagement.vue";
import AttendanceManagement from "@/pages/admin/contents/managements/AttendanceManagement.vue";

import UsersManagement from "@/pages/admin/contents/managements/user/UsersManagement.vue";
import RolesManagement from "@/pages/admin/contents/managements/roles/RolesManagement.vue";
import PermissionsManagement from "@/pages/admin/contents/managements/permissions/PermissionsManagement.vue";
import FakeComponent from "@/pages/admin/contents/managements/FakeComponent.vue";
import UserProfile from "@/pages/admin/contents/profile/UserProfile.vue";
import EditUserProfile from "@/pages/admin/contents/profile/EditUserProfile.vue";
import UserAccount from "@/pages/admin/contents/profile/account/UserAccount.vue";
import ChangeUserPassword from "@/pages/admin/contents/profile/account/ChangeUserPassword.vue";
import SampleTable from "@/pages/admin/contents/SampleTable.vue";
import DepartmentCards from "@/pages/admin/components/DepartmentCards.vue";

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home-layout",
      component: HomeLayout,
    },
    {
      path: "/admin/signup",
      name: "signup",
      component: SignupViewAdmin,
    },
    {
      path: "/admin/login",
      name: "loginAdmin",
      component: LoginViewAdmin,
    },
    {
      path: "/user/login",
      name: "loginUser",
      component: LoginViewUser,
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
              path: "department-cards",
              component: DepartmentCards,
            },
            {
              path: "teachers",
              component: TeacherContent,
            },
            {
              path: "teachers/department/:id",
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
            {
              path: "fake-data",
              component: FakeComponent,
            },
            {
              path: "user",
              component: UsersManagement,
            },
            {
              path: "role",
              component: RolesManagement,
            },
            {
              path: "permissions",
              component: PermissionsManagement,
            },
            {
              path: "sample",
              component: SampleTable,
            },
          ],
        },

        {
          path: "profile",
          component: ProfileContent,
          children: [
            {
              path: "baseinfo", //个人

              component: UserProfile,
            },
            {
              path: "baseinfo/:id", //个人

              component: UserProfile,
            },
            {
              path: "baseinfo/:id/account", //个人
              component: UserAccount,
            },
            {
              path: "edit", //个人
              component: EditUserProfile,
            },
            {
              path: "baseinfo/:id/change-password", //个人
              component: ChangeUserPassword,
            },
          ],
        },
      ],
    },
    {
      path: "/home",
      name: "home",
      component: import("../pages/user/AllClass.vue"),
    },
    {
      path: "/user",
      name: "dashboard",
      component: UserLayout,
      children: [
        {
          path: "dashboard",
          name: "dashboard",
          component: import("../pages/user/contents/DashboardContent.vue"),
          meta: {
            RouteName: "Dashboard",
          },
        },
        {
          path: "attendance",
          name: "attendance",
          component: import("../pages/user/contents/AttendanceManage.vue"),
          meta: {
            RouteName: "Attendance",
          },
        },
        {
          path: "score-grading",
          name: "Score Grading",
          component: import("../pages/user/contents/ScoreGrading.vue"),
          meta: {
            RouteName: "Score Grading",
          },
        },
        {
          path: "Generate-report",
          name: "Generate Report",
          component: ProfileContent,
          meta: {
            RouteName: "Generate Report",
          },
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
