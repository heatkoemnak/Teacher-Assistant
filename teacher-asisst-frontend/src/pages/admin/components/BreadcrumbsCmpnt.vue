<template>
  <v-banner color="deep-purple-accent-4" icon="mdi-lock" lines="one">
    <v-banner-text>
      {{ formattedRoutePath }}
    </v-banner-text>
  </v-banner>
</template>
<script>
import { ref, watch, computed } from "vue";
import { useRoute } from "vue-router";

//formatting route
function formatRoutePath(path) {
  const parts = path.split("/admin/");

  const formattedParts = parts.map((part) => {
    return part.charAt(0).toUpperCase() + part.slice(1);
  });
  if (formattedParts[0] === "") {
    formattedParts.shift();
  }
  return " " + formattedParts.join(" / ");
}
// get last route path

//close formatting route

export default {
  setup() {
    const route = useRoute();
    let currentRouteUrl = ref(route.path);
    watch(
      () => route.path,
      (val) => {
        currentRouteUrl.value = val;
        console.log(val);
      }
    );
    // const updateCurrentRoutePath = () => {
    //   console.log(currentRouteUrl.value);
    //   currentRouteUrl.value = window.location.pathname;
    // };

    const formattedRoutePath = computed(() => {
      return formatRoutePath(currentRouteUrl.value);
    });

    const items = ref([
      {
        title: "Dashboard",
        disabled: false,
      },
      {
        title: "Link 1",
        disabled: false,
        href: "breadcrumbs_link_1",
      },
      {
        title: "Link 2",
        disabled: true,
      },
    ]);
    // Return the reactive items
    return {
      items,
      formattedRoutePath,
    };
  },
};
</script>
