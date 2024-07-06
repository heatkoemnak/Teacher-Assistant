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
      <!-- <v-list-item class="pa-5" nav>
        <h2>T . Assistant</h2>
        <template v-slot:append>
          <v-btn
            icon="mdi-chevron-left"
            variant="text"
            @click.stop="rail = !rail"
          ></v-btn>
        </template>
      </v-list-item> -->
      <v-list-item
        class="pa-5"
        prepend-avatar="https://randomuser.me/api/portraits/men/86.jpg"
        title="Admin Panel"
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
          to="/admin/dashboard"
        ></v-list-item>
        <v-list-group value="Management">
          <template v-slot:activator="{ props }">
            <v-list-item
              prepend-icon="mdi-cogs"
              v-bind="props"
              title="School Management"
            ></v-list-item>
          </template>
          <v-list-item
            v-for="(management, i) in managements"
            :key="i"
            :prepend-icon="management.icon"
            :title="management.title"
            :value="management.title"
            :to="`/admin/management/${management.route}`"
          ></v-list-item>
        </v-list-group>
        <v-list-group value="Management User">
          <template v-slot:activator="{ props }">
            <v-list-item
              prepend-icon="mdi-cogs"
              v-bind="props"
              title="User Management"
            ></v-list-item>
          </template>
          <v-list-item
            v-for="(management, i) in user_managements"
            :key="i"
            :prepend-icon="management.icon"
            :title="management.title"
            :value="management.title"
            :to="`/admin/management/${management.route}`"
          ></v-list-item>
        </v-list-group>
        <v-list-item
          prepend-icon="mdi-lock"
          title="Change Password"
          value="password"
          :to="`/admin/profile/basic-info/${user.id}/change-password`"
        ></v-list-item>
        <v-list-item
          prepend-icon="mdi-account-circle"
          title="Profile"
          value="profile"
          :to="`/admin/profile/basic-info/${user.id}/personal-details`"
        ></v-list-item>
        <v-list-item
          prepend-icon="mdi-bell"
          title="Notifications"
          value="notifications"
          to="/admin/notification"
        ></v-list-item>
      </v-list>
      <v-chip elevated class="ma-2 bg-orange" color="white" label>
        LOGOUT
        <v-icon @click="logout" icon="mdi-logout" end></v-icon>
      </v-chip>
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
          <v-badge color="error" content="2">
            <v-btn to="/admin/notification" variant="text"
              ><v-icon>mdi-bell-outline</v-icon>
            </v-btn>
          </v-badge>
        </v-btn>
        <!-- <profile-drop-down :user="user" /> -->
        <drop-down-menu :user="user" />

      </v-toolbar>
      <!-- <breadcrumbs-cmpnt /> -->
      <v-content app>
        <v-container fluid style="height: 100%; background-color: #eeeeee">
          <router-view></router-view>
        </v-container>
      </v-content>
    </v-main>
  </v-layout>
</template>
<script>
// import ProfileDropDown from "./components/ProfileDropDown.vue";
import DropDownMenu from "./components/DropDownMenu.vue";
// import axios from "axios";
import axios from "@/axios";
export default {
  components: { DropDownMenu },
  data() {
    return {
      drawer: true,
      rail: false,
      expend: false,
      sheet: false,
      user: "",
      tiles: [
        { img: "keep.png", title: "Keep" },
        { img: "inbox.png", title: "Inbox" },
        { img: "hangouts.png", title: "Hangouts" },
        { img: "messenger.png", title: "Messenger" },
        { img: "google.png", title: "Google+" },
      ],
      managements: [
        // {
        //   id: 1,
        //   title: "Teachers",
        //   icon: "mdi-account",
        //   route: "department-cards",
        // },
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
        // {
        //   id: 4,
        //   title: "Attendance",
        //   icon: "mdi-calendar-check",
        //   route: "attendance",
        // },
        // {
        //   id: 5,
        //   title: "Departments",
        //   icon: "mdi-domain",
        //   route: "departments",
        // },
        // {
        //   id: 6,
        //   title: "Subjects",
        //   icon: "mdi-book-open-variant",
        //   route: "subjects",
        // },
        {
          id: 7,
          title: "Teachers",
          icon: "mdi-account",
          route: "teachers",
        },
      ],
      user_managements: [
        {
          id: 1,
          title: "Users",
          icon: "mdi-account-multiple-outline",
          route: "user",
        },
        {
          id: 2,
          title: "Roles",
          icon: "mdi-briefcase-account",
          route: "role",
        },
        {
          id: 3,
          title: "Permissions",
          icon: "mdi-key",
          route: "permissions",
        },
      ],
    };
  },

  mounted() {
    this.fetchUserDetails();
  },
  methods: {
    async fetchUserDetails() {
      try {
        const response = await axios.get("/auth/user");
        this.user = response.data;
        console.log(this.user);
      } catch (error) {
        console.error("Failed to fetch user details:", error);
      }
    },
  },
};
</script>
