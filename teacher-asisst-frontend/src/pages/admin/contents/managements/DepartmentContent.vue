<template>
  <v-data-table-server
    :headers="headers"
    :items="serverItems"
    :items-length="totalItems"
    :loading="loading"
    :search="search"
    item-value="name"
    @update:options="loadItems"
    fixed-header
    height="420"
    disable-pagination
    disable-sort
    hide-default-footer
  >
    <template #item.actions="{ item }">
      <div class="d-flex justify-start align-center">
        <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
      </div>
    </template>
    <template v-slot:top>
      <div class="d-flex align-center">
        <v-text-field
          v-model="name"
          class="ma-2"
          density="compact"
          placeholder="Search name..."
          hide-details
          width="20"
          variant="outlined"
        ></v-text-field>
        <v-spacer></v-spacer>
        <v-btn
          color="white"
          class="bg-primary ml-2 white--text"
          @click="addNew"
        >
          <v-icon dark>mdi-plus</v-icon>Add
        </v-btn>
      </div>
    </template>
  </v-data-table-server>
</template>
<script>
function generateData() {
  const data = [];

  const desserts = [
    "",
    "Frozen Yogurt",
    "Jelly bean",
    "KitKat",
    "Eclair",
    "Gingerbread",
    "Ice cream sandwich",
    "Lollipop",
    "Cupcake",
    "Honeycomb",
    "Donut",
  ];

  for (let i = 1; i <= 10; i++) {
    // const randomGenderIndex = Math.floor(Math.random() * genders.length);
    const dept_id = `DP00${i}`;
    const name = desserts[i];
    const dept_head = `Head Name${i}`;
    const total_students = Math.floor(Math.random() * desserts.length + 1240);
    const total_teachers = Math.floor(Math.random() * desserts.length + 270);

    data.push({
      id: i,
      name,
      dept_id,
      dept_head,
      total_students,
      total_teachers,
    });
  }
  return data;
}

const FakeAPI = {
  async fetch({ page, itemsPerPage, sortBy, search }) {
    return new Promise((resolve) => {
      setTimeout(() => {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const items = generateData()
          .slice()
          .filter((item) => {
            if (
              search.name &&
              !item.name.toLowerCase().includes(search.name.toLowerCase())
            ) {
              return false;
            }
            return true;
          });

        if (sortBy.length) {
          const sortKey = sortBy[0].key;
          const sortOrder = sortBy[0].order;
          items.sort((a, b) => {
            const aValue = a[sortKey];
            const bValue = b[sortKey];
            return sortOrder === "desc" ? bValue - aValue : aValue - bValue;
          });
        }

        const paginated = items.slice(start, end);

        resolve({ items: paginated, total: items.length });
      }, 500);
    });
  },
};

export default {
  data: () => ({
    itemsPerPage: 5,
    headers: [
      { title: "No.", key: "id" },
      { title: "Dept.Name", key: "name" },
      { title: "Dept.ID", key: "dept_id" },
      {
        title: "Department Head",
        key: "dept_head",
      },
      {
        title: "Total Students",
        key: "total_students",
      },
      {
        title: "Total Teacher",
        key: "total_teachers",
      },
      { title: "Action", key: "actions", filterable: false, sortable: false },
    ],
    generatedData: [],
    serverItems: [],
    loading: true,
    totalItems: 0,
    name: "",
    student_id: "",
    search: "",
  }),
  watch: {
    name() {
      this.search = String(Date.now());
    },
    student_id() {
      this.search = String(Date.now());
    },
  },

  methods: {
    loadItems({ page, itemsPerPage, sortBy }) {
      this.loading = true;
      FakeAPI.fetch({
        page,
        itemsPerPage,
        sortBy,
        search: { name: this.name, student_id: this.student_id },
      }).then(({ items, total }) => {
        this.serverItems = items;
        this.totalItems = total;
        this.loading = false;
      });
    },
  },
};
</script>
