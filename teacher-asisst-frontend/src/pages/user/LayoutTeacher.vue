<template>
  <v-layout>
    <v-navigation-drawer
      v-model="drawer"
      :rail="rail"
      permanent
      color="blue-darken-3"
      @click="rail = true"
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
      <v-list  >
        <div >
          <v-list-item
            prepend-icon="mdi-view-dashboard"
            title="Dashboard"
            value="board"
            to="/board"
          ></v-list-item>
        </div>
        <v-list-item
          prepend-icon="mdi-school"
          title="Classes"
          value="class"
          to="/classes"
        ></v-list-item>
        <v-list-item
          prepend-icon="mdi-calendar"
          title="Calendar"
          value="calendar"
          to="/cal"
        ></v-list-item>

      </v-list>
    </v-navigation-drawer>
    <v-main style="height: 100vh">
      <v-toolbar color="white h-10">
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
        <h4 class="mx-5 text-gray">Welcome to Dashboard</h4>

        <v-spacer></v-spacer>

        <v-btn class="text-none" stacked>
          <v-icon>mdi-account-multiple-outline</v-icon>
        </v-btn>

        <v-btn class="text-none" stacked>
          <v-badge color="error" content="2">
            <v-btn to="/admin/notification" variant="text"
              ><v-icon>mdi-bell-outline</v-icon>
            </v-btn>
          </v-badge>
        </v-btn>
        <ProfileDropDown :user="user" />
      </v-toolbar>
      <v-content app>
        <v-container fluid style="height: 84%">
          <router-view></router-view>
        </v-container>
      </v-content>
    </v-main>
  </v-layout>
</template>
<script>
import ProfileDropDown from "../../components/ProfileDropDown.vue";
import { ref } from "vue";
// import BreadcrumbsCmpnt from "../";
export default {
  components: { ProfileDropDown },
  data() {
    return {
      pathID : this.$route.params.class_id
    }
  },
  setup() {
    const drawer = ref(true);
    const rail = ref(true);
    const expend = ref(true);

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
    ]);
    return { drawer, rail, managements, expend };
  },
};
</script>
<style scoped></style>
