<template lang="">
  <v-container fluid density="compact" nav>
    <v-row justify="center">
      <v-col cols="12" sm="9" md="3" v-for="card in cards" :key="card.id">
        <v-card
          class="text-white d-flex pointer-card"
          :style="{ background: card.backgroundGradient }"
          :to="card.route"
          max-width="260"
          height="130"
          hover
        >
          <div>
            <v-icon class="ml-3 mt-2" size="48">{{ card.icon }}</v-icon>
            <v-card-title>
              {{ card.title }}
            </v-card-title>
          </div>
          <v-spacer></v-spacer>
          <v-card-title> 250 </v-card-title>
        </v-card>
      </v-col>
    </v-row>
    <analysis-component />
  </v-container>
</template>
<script>
import { ref } from "vue";
import AnalysisComponent from "../components/AnalysisComponent.vue";
export default {
  components: {
    "analysis-component": AnalysisComponent,
  },
  setup() {
    const cards = ref([
      {
        id: 1,
        title: "Teachers",
        icon: "mdi-account",
        backgroundGradient: "linear-gradient(45deg, #FF416C, #FF4B2F)",
        route: "/admin/management/sample",
      },
      {
        id: 2,
        title: "Students",
        icon: "mdi-account-group",
        backgroundGradient: "linear-gradient(45deg, #00bca1,#00bca1)",
        route: "/admin/management/students",
      },
      {
        id: 3,
        title: "Classes",
        icon: "mdi-school",
        backgroundGradient: "linear-gradient(45deg, #cfad00, #cfad00)",
        route: "/admin/management/classes",
      },
      {
        id: 4,
        title: "Attendance",
        icon: "mdi-calendar-check",
        backgroundGradient: "linear-gradient(45deg, #4CC9F0, #4CC9F0)",
        route: "/admin/management/attendance",
      },
      {
        id: 5,
        title: "Departments",
        icon: "mdi-domain",
        backgroundGradient: "linear-gradient(45deg, #007498, #007498)",
        route: "/admin/management/departments",
      },
      {
        id: 6,
        title: "Grading",
        icon: "mdi-star",
        backgroundGradient: "linear-gradient(45deg, #1e4572, #1e4572)",
      },
      {
        id: 7,
        title: "Users",
        icon: "mdi-cogs",
        backgroundGradient: "linear-gradient(45deg, #FF6B6B, #FF6B6B)",
      },
      {
        id: 8,
        title: "Notifications",
        icon: "mdi-bell",
        backgroundGradient: "linear-gradient(45deg, #00bca1,#00a3a4)",
      },
    ]);
    return { cards };
  },
};
</script>
<style scoped>
.pointer-card {
  cursor: pointer;
}
</style>
