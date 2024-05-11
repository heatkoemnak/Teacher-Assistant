<template>
  <div>
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
    <template v-slot:item.March="{ item }">
      <div class="d-flex justify-start align-center">
        <v-autocomplete
          variant="underlined"
          :items="['Present', 'Permission', 'Absent']"
          v-model="attendStatus"
        ></v-autocomplete>
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
        <addDateDiol @get-date="onAddDate"/>
      </div>
    </template>
  </v-data-table-server>
  </div>

</template>
<script>
import addDateDiol from '../components/addDateDiol.vue'

function generateData() {
  const data = [];
  const genders = [
    "",
    "Male",
    "Female",
    "Male",
    "Female",
    "Male",
    "Female",
    "Male",
    "Female",
    "Male",
    "Female",
  ];
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
    "Honeycomb",
    "Donut",
  ];


  for (let i = 1; i <= 12; i++) {
    // const randomGenderIndex = Math.floor(Math.random() * genders.length);
    const student_id = i * 12;
    const name = desserts[i];
    const gender = genders[i];
    const edit = "Edit";
    const del = "Delete";

    data.push({
      id: i,
      name,
      student_id,
      gender,
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

            if (
              search.student_id &&
              !(item.student_id >= Number(search.student_id))
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
  components: {
    addDateDiol,
  },
  data: () => ({
    itemsPerPage: 6,
    headers: [
      { title: "No.", key: "id" },
      { title: "Name", key: "name" },
      { title: "Student ID", key: "student_id" },
      { title: "Gender", key: "gender" },
      { title: "Attendance", key: 'attendance'}
    ],
    generatedData: [],
    serverItems: [],
    loading: true,
    totalItems: 0,
    name: "",
    student_id: 0,
    search: "",
    attendStatus: null
  }),
  watch: {
    name() {
      this.search = String(Date.now());
    },
    student_id() {
      this.search = String(Date.now());
    },
    attendStatus(newVal) {
      console.log(newVal)
    }
  },

  mounted() {
    console.log(this.student_id);
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
    onAddDate(val) {
      const newDate = {
        title: `${val}`,
        key: 'March'
      }
      this.headers.push(newDate)
    }
  },
};
</script>
