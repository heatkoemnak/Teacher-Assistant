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
      <v-col cols="4">
        <v-card class="dashboardBoard">
          <v-card-text>
              Class attendances
          </v-card-text>
            <v-img  max-height="250" src="https://cdn-icons-png.flaticon.com/512/3589/3589863.png"></v-img>
      </v-card>
      </v-col>
      <v-col cols="8">
        <v-card class="dashboardBoard">
            <v-card-text>
                Students attend by day
            </v-card-text>
            <v-img max-height="250" src="https://cdn.corporatefinanceinstitute.com/assets/line-graph.jpg"></v-img>
        </v-card>
      </v-col>
    </v-row>
    <analysis-component />
  </v-container>
</template>
<script>
import { ref } from "vue";
// import AnalysisComponent from "../components/AnalysisComponent.vue";
export default {
  components: {
    // AnalysisComponent,
  },
  setup() {
    const cards = ref([
      {
        id: 1,
        title: "Total Students",
        icon: "mdi-account-group",
        backgroundGradient: "linear-gradient(45deg, #FF416C, #FF4B2F)",
        route: "/admin/management/teachers",
      },
      {
        id: 2,
        title: "Students Participation",
        icon: "mdi-percent-outline",
        backgroundGradient: "linear-gradient(45deg, #1D2B64, #1D2B64)",
        route: "/admin/management/students",
      },
      {
        id: 3,
        title: "Average Score",
        icon: "mdi-school",
        backgroundGradient: "linear-gradient(45deg, #FF416C, #FF416D)",
      },
      {
        id: 4,
        title: "Total Class",
        icon: "mdi-calendar-check",
        backgroundGradient: "linear-gradient(45deg, #1D2B64, #1D2B63)",
      }
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
