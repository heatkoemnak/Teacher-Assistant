<template>
  <div>
    <breadcrumbs-cmpnt />
    <router-view></router-view>
  </div>
</template>
<script>
import BreadcrumbsCmpnt from "../../components/BreadcrumbsCmpnt.vue";
export default {
  components: { BreadcrumbsCmpnt },
};
</script>
<style lang=""></style>
