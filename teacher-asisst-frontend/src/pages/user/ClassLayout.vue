<template>
  <v-layout>
    <SidebarCompo :items="sidebarItems" :pathID="pathID" />
    <v-main style="height: 100vh">
      <v-content app>
        <v-container fluid style="height: 84%">
          <router-view></router-view>
        </v-container>
      </v-content>
    </v-main>
  </v-layout>
</template>

<script>
import { ref } from "vue";
import SidebarCompo from './components/SidebarCompo.vue';

export default {
  components: {
    SidebarCompo,
  },
  data() {
    return {
      pathID: this.$route.params.id,
      sidebarItems: [
        {
          title: "Assignments",
          icon: "mdi-view-dashboard",
          route: `/class-teacher/${this.$route.params.slug}/${ this.$route.params.id}/add-student`,
        },
        {
          title: "Attendance",
          icon: "mdi-list-status",
          route: `/class-teacher/${this.$route.params.slug}/${ this.$route.params.id}/class-attendance`,
        },
        {
          title: "Grades",
          icon: "mdi-school",
          route: `/class-teacher/${this.$route.params.slug}/${ this.$route.params.id}/score-grading`,
        },
        {
          title: "Students",
          icon: "mdi-account-group",
          route: `/class-teacher/${this.$route.params.slug}/${ this.$route.params.id}/class-students`,
        },
        {
          title: "Generate Report",
          icon: "mdi-text-box-search-outline",
          route: `/class/${this.$route.params.slug}/${ this.$route.params.id}/generate-report`,
        },
        {
          title: "Back to all Class",
          icon: "mdi-chevron-left",
          route: "/home",
        },
      ],
    };
  },
  setup() {
    const drawer = ref(true);
    const rail = ref(false);

    return { drawer, rail };
  },
};
</script>

<style scoped></style>
