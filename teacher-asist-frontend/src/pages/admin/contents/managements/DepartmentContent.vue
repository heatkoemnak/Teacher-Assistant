<template>
  <v-data-table-server
    :items-per-page="itemsPerPage"
    :headers="headers"
    :items="serverItems"
    :items-length="totalItems"
    :loading="loading"
    :search="search"
    item-value="name"
    @update:options="loadItems"
  >
    <template v-slot:tfoot>
      <tr>
        <td colspan="2">
          <v-text-field
            v-model="name"
            class="ma-2"
            density="compact"
            placeholder="Search name..."
            hide-details
          ></v-text-field>
        </td>
        <td colspan="2">
          <v-text-field
            v-model="student_id"
            class="ma-2"
            density="compact"
            placeholder="Search by studentID..."
            hide-details
          ></v-text-field>
        </td>
      </tr>
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
    const edit = "Edit";
    const del = "Delete";

    data.push({
      id: i,
      name,
      dept_id,
      dept_head,
      total_students,
      total_teachers,
      edit,
      delete: del,
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
      { title: "Edit", key: "edit", filterable: false, sortable: false },
      { title: "Delete", key: "delete", filterable: false, sortable: false },
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
