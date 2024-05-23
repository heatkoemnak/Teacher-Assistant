<!-- <template>
    <v-row align="center" justify="center" dense>
      <v-col
        cols="12"
        md="2"
        v-for="department in departments"
        :key="department.id"
      >
        <v-card
          :key="department.id"
          class="mx-auto deep-purple-accent-4"
          :title="department.name"
          width="200"
          height="100"
          hover
        >
          <v-card-text>{{ department.fullname }}</v-card-text>
        </v-card>
      </v-col>
    </v-row>
</template>
<script>
import fakeDataAPI from "../fakedata";
export default {
  data: () => ({
    departments: "",
    loading: false,
  }),
  created() {
    this.loadItems({});
  },
  methods: {
    async loadItems() {
      this.loading = true;
      try {
        const response = await fakeDataAPI.get("/api/departments", {});
        this.departments = response.data.items;
        console.log(this.departments);
      } catch (error) {
        alert(error);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script> -->
<template>
  <v-card class="mx-auto">
    <v-toolbar color="primary" height="60" flat>
      <template v-slot:prepend>
        <v-btn icon="mdi-arrow-left"> </v-btn>
        <div class="mx-4">Choose Department</div>
      </template>

      <v-text-field
        v-model="search"
        class="ma-2 hidden-sm-and-down"
        density="compact"
        placeholder="Search by name or keywords"
        hide-details
        width="20"
        variant="outlined"
        prepend-inner-icon="mdi-magnify"
      ></v-text-field>
      <template v-slot:append>
        <v-btn icon="mdi-dots-vertical"></v-btn>
      </template>
    </v-toolbar>

    <div v-if="keywords.length > 0" class="py-3 px-4">
      <v-chip v-for="(keyword, i) in keywords" :key="i" class="me-2">
        {{ keyword }}
      </v-chip>
    </div>

    <v-divider></v-divider>

    <v-list lines="three" max-height="510" style="overflow-y: auto">
      <v-list-item
        v-for="(item, i) in searching"
        :key="i"
        :to="`/admin/management/teachers/department/${item.id}`"
      >
        <template v-slot:prepend>
          <v-avatar class="me-4 mt-2" rounded="0">
            <v-img :src="item.image" cover></v-img>
          </v-avatar>
        </template>
        <div v-text="item.title"></div>
      </v-list-item>
    </v-list>
  </v-card>
</template>
<script>
import fakeDataAPI from "../fakedata";

export default {
  data: () => ({
    items: "",
    search: "",
  }),
  created() {
    this.loadItems({
      page: 1,
      itemsPerPage: 10,
      sortBy: [],
      sortDesc: [],
      search: "",
    });
  },

  computed: {
    keywords() {
      if (!this.search) return [];

      const keywords = [];

      for (const search of this.searching) {
        keywords.push(search.keyword);
      }
      return keywords;
    },
    searching() {
      if (!this.search) return this.items;

      const search = this.search.toLowerCase();

      return this.items.filter((item) => {
        const text = item.title.toLowerCase();

        return text.indexOf(search) > -1;
      });
    },
  },
  methods: {
    async loadItems({ page, itemsPerPage, sortBy }) {
      try {
        const response = await fakeDataAPI.get("/api/departments", {
          params: { page, itemsPerPage, sortBy },
        });
        this.items = response.data.items;
        console.log(this.items);
      } catch (error) {
        alert(error);
      }
    },
  },
};
</script>
