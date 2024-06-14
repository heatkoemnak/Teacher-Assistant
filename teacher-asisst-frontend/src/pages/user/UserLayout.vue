<template>
  <v-layout>
    <v-navigation-drawer
      v-model="drawer"
      :rail="rail"
      permanent
      color="blue-darken-2"
      @click="rail = false"
      class="overflow"
    >
      <v-list-item
        class="pa-5"
        prepend-avatar="https://randomuser.me/api/portraits/men/86.jpg"
        title="Mr. Koemnak"
        nav
      >
        <template v-slot:append>
          <v-btn
            icon="mdi-chevron-left"
            variant="text"
            @click.stop="rail = !rail"
          ></v-btn>
        </template>
      </v-list-item>

      <v-divider></v-divider>
      <v-list density="compact" nav>
        <v-list-item
          prepend-icon="mdi-view-dashboard"
          title="Dashboard"
          value="home"
          :to="`/class/${pathID}/dashboard`"
        ></v-list-item>
        <v-list-item
          prepend-icon="mdi-list-status"
          title="Attendance"
          value="password"
          :to="`/class/${pathID}/attendance`"
        ></v-list-item>
        <v-list-item
          prepend-icon="mdi-school"
          title="Score Grading"
          value="profile"
          :to="`/class/${pathID}/score-grading`"
        ></v-list-item>
        <v-list-item
          prepend-icon="mdi-text-box-search-outline"
          title="Generate Report"
          value="notifications"
          :to="`/class/${pathID}/generate-report`"
        ></v-list-item>
        <v-list-item
          prepend-icon="mdi-chevron-left"
          title="Back to all Class"
          value="hey"
          to="/home"
        ></v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-main style="height: 100vh">
      <v-toolbar color="blue-blue-darken-3">
        <v-btn
          v-if="rail"
          class="text-none"
          stacked
          v-model="drawer"
          :rail="rail"
          permanent
          @click="rail = false"
        >
          <v-icon>mdi-menu</v-icon>
        </v-btn>
        <span class="mx-5">Teacher Assistant Dashboard</span>

        <v-spacer></v-spacer>

        <v-btn class="text-none" stacked>
          <v-badge color="error" content="2">
            <v-icon>mdi-bell-outline</v-icon>
          </v-badge>
        </v-btn>

        <v-btn class="text-none" stacked>
          Import
        </v-btn>
      </v-toolbar>
      <breadcrumbs-cmpnt />
      <v-content app>
        <v-container fluid style="height: 84%">
          <router-view></router-view>
        </v-container>
        <v-divider></v-divider>
      </v-content>
      <!-- <v-card-text class="bg-red white--text">
        Copyright 1993 - {{ new Date().getFullYear() }}
        <a class="white--text" href="https://codepen.io/jettaz">Jettaz</a> | It
        is free for use
      </v-card-text> -->
    </v-main>
  </v-layout>
</template>
<script>
import { ref } from "vue";
// import BreadcrumbsCmpnt from "../";
export default {
  // components: { BreadcrumbsCmpnt },
  data() {
    return {
      pathID : this.$route.params.class_id
    }
  },
  setup() {
    const drawer = ref(true);
    const rail = ref(false);
    const expend = ref(false);

    const managements = ref([
      {
        id: 1,
        title: "Teachers",
        icon: "mdi-account",
        route: "teachers",
      },
      {
        id: 2,
        title: "Students",
        icon: "mdi-account-group",
        route: "students",
      },
      {
        id: 3,
        title: "Classes",
        icon: "mdi-school",
        route: "classes",
      },
      {
        id: 4,
        title: "Attendance",
        icon: "mdi-calendar-check",
        route: "attendance",
      },
      {
        id: 5,
        title: "Departments",
        icon: "mdi-domain",
        route: "departments",
      },
      // {
      //   id: 6,
      //   title: "Grading",
      //   icon: "mdi-star",
      //   route: "/attendance",
      // },
      // {
      //   id: 1,
      //   title: "Users",
      //   icon: "mdi-account-multiple-outline",
      // },
      // {
      //   id: 2,
      //   title: "Role",
      //   icon: "mdi-briefcase-account",
      // },
      // {
      //   id: 3,
      //   title: "Permissions",
      //   icon: "mdi-key",
      // },
    ]);
    return { drawer, rail, managements, expend };
  },
};
</script>
<style scoped></style>
