import { createRouter, createWebHistory } from "vue-router";
import HomeLayout from "@/pages/HomeLayout.vue";
import PageNotFound from "@/pages/PageNotFound.vue";
import AdminLayout from "@/pages/admin/AdminLayout.vue";
import UserLayout from "@/pages/user/UserLayout.vue";
// import AttendanceManage from "../pages/user/contents/AttendanceManage2.vue";
import DashboardContent from "@/pages/admin/contents/DashboardContent.vue";
import TeacherContent from "@/pages/admin/contents/managements/TeacherContent.vue";
import StudentContent from "@/pages/admin/contents/managements/StudentContent.vue";
import ProfileContent from "@/pages/admin/contents/ProfileContent.vue";
import ManagementLayout from "@/pages/admin/contents/managements/ManagementLayout.vue";
import DepartmentContent from "@/pages/admin/contents/managements/DepartmentContent.vue";
import ClassManagement from "@/pages/admin/contents/managements/ClassManagement.vue";
import AttendanceManagement from "@/pages/admin/contents/managements/AttendanceManagement.vue";

import UsersManagement from "@/pages/admin/contents/managements/user/UsersManagement.vue";
import RolesManagement from "@/pages/admin/contents/managements/roles/RolesManagement.vue";
import PermissionsManagement from "@/pages/admin/contents/managements/permissions/PermissionsManagement.vue";
import FakeComponent from "@/pages/admin/contents/managements/FakeComponent.vue";
import SubjectContent from "@/pages/admin/contents/managements/SubjectContent.vue";
import UserProfile from "@/pages/admin/contents/profile/UserProfile.vue";
// import EditUserProfile from "@/pages/admin/contents/profile/EditUserProfile.vue";
import UserAccount from "@/pages/admin/contents/profile/account/UserAccount.vue";

import PersonalDetails from "@/pages/admin/contents/profile/personal_info/PersonalDetails.vue";
import SubjectBelong from "@/pages/admin/contents/profile/personal_info/SubjectBelong.vue";

import ChangeUserPassword from "@/pages/admin/contents/profile/account/ChangeUserPassword.vue";
import DepartmentCards from "@/pages/admin/components/DepartmentCards.vue";
import Notification from "@/pages/admin/components/Notification.vue";

import LoginPage from "@/pages/auth/LoginView.vue";
import RegisterView from "@/pages/auth/RegisterView.vue";

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home-layout",
      redirect: "/login",
      component: HomeLayout,
      meta: { requiresAuth: true },
    },
    {
      path: "/login",
      name: "login",
      component: LoginPage,
      meta: { guest: true },
    },
    {
      path: "/register",
      name: "register",
      component: RegisterView,
    },
    {
      path: "/admin",
      component: AdminLayout,
      meta: { requiresAuth: true, role: 1 },

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
              path: "subjects",
              component: SubjectContent,
            },
          ],
        },

        {
          path: "profile",
          component: ProfileContent,
          children: [
            {
              path: "basic-info/:id", //个人
              component: UserProfile,
              children: [
                {
                  path: "personal-details",
                  component: PersonalDetails,
                },
                {
                  path: "subject",
                  component: SubjectBelong,
                },
              ],
            },
            {
              path: "basic-info/:id/account",
              component: UserAccount,
            },
            {
              path: "basic-info/:id/change-password",
              component: ChangeUserPassword,
            },
          ],
        },
        {
          path: "notification",
          component: Notification,
        },
      ],
    },
    {
      path: "/home",
      name: "home",
      component: import("../pages/user/AllClass.vue"),
      meta: { requiresAuth: true, role: 2 },
    },
    {
      path: "/lay-teacher",
      name: "layout-teacher",
      component: import("../pages/user/LayoutTeacher.vue"),
      meta: { requiresAuth: true, role: 2 },
      redirect: '/board',
      children: [
        {
          path: "/board",
          name: "board",
          component: import("../pages/user/contents/DashboardContent.vue"),

        },
        {
          path: "/cal",
          name: "calendar",
          component: import("../pages/user/components/CalendarView.vue"),
          meta: {
            RouteName: "cal",
          },
        },
        {
          path: "/classes",
          name: "classes",
          component: import("../pages/user/AllClass.vue"),
        },
        {
          path: "/class-teacher/:slug/:id",
          name: "class",
          component: import("../pages/user/ClassLayout.vue"),
          children:[
            {
              path: "add-student",
              name: "add-student",
              component: import("../pages/user/contents/AddStudent.vue"),
            },
            {
              path: "class-attendance",
              name: "class-attendance",
              component: import("../pages/user/contents/AttendanceManage.vue"),

            },
            {
              path: "score-grading",
              name: "score-grading",
              component: import("../pages/user/contents/ScoreGrading.vue"),

            },
            {
              path: "class-students",
              name: "class-students",
              component: import("../pages/user/contents/StudentManage.vue"),

            },
            {
              path: "class-report",
              name: "class-report",
              component: import("../pages/user/contents/GenerateReport.vue")
            },
          ]
        },
        {
          path: "/classes/class/:class_id",
          name: "user",
          component: UserLayout,

        }
      ],
    },
    {
      path: "/class/:class_id",
      name: "dashboard",
      meta: { requiresAuth: true, role: 2 },
      component: UserLayout,
      redirect: '/dashboard',
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
          component: import("../pages/user/contents/GenerateReport.vue"),
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
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem("token");
  const user = JSON.parse(localStorage.getItem("user"));

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next({ path: "/login" });
    } else if (to.meta.role && user.role_id !== to.meta.role) {
      if (user.role.name === 'admin') {
        next({ path: "/admin/dashboard" });
      } else {
        next({ path: "/lay-teacher" });
      }
    } else {
      next();
    }
  } else if (to.matched.some((record) => record.meta.guest)) {
    if (isAuthenticated) {
      if (user.role.name === 'admin') {
        next({ path: "/admin/dashboard" });
      } else {
        next({ path: "/lay-teacher" });
      }
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
