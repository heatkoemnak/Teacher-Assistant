<template lang="">
  <v-container fluid density="compact" nav>
    <v-row justify="center">
      <v-col cols="12" sm="9" md="3" v-for="card in cards" :key="card.id">
        <v-card
          class="text-white pointer-card"
          :style="{ background: card.backgroundGradient }"
          :to="card.route"
        >
          <v-icon class="ml-3 mt-2" size="48">{{ card.icon }}</v-icon>
          <v-card-title>
            {{ card.title }}
          </v-card-title>
          <v-card-actions>
            <v-btn color="white" style="font-size: 25px">250</v-btn>
            <v-spacer></v-spacer>
            <v-btn color="secondary">More info</v-btn>
          </v-card-actions>
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
        route: "/admin/management/teachers",
      },
      {
        id: 2,
        title: "Students",
        icon: "mdi-account-group",
        backgroundGradient: "linear-gradient(45deg, #1D2B64, #1D2B64)",
        route: "/admin/management/students",
      },
      {
        id: 3,
        title: "Classes",
        icon: "mdi-school",
        backgroundGradient: "linear-gradient(45deg, #FF416C, #FF416D)",
      },
      {
        id: 4,
        title: "Attendance",
        icon: "mdi-calendar-check",
        backgroundGradient: "linear-gradient(45deg, #1D2B64, #1D2B63)",
      },
      {
        id: 5,
        title: "Departments",
        icon: "mdi-domain",
        backgroundGradient: "linear-gradient(45deg, #363795, #363795)",
      },
      {
        id: 6,
        title: "Grading",
        icon: "mdi-star",
        backgroundGradient: "linear-gradient(45deg, #D31027, #EA384D)",
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
        backgroundGradient: "linear-gradient(45deg, #16222A, #3A6073)",
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
