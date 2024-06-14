<template>
  <v-data-table-server
    v-model:items-per-page="itemsPerPage"
    :headers="headers"
    :items="serverItems"
    :items-length="totalItems"
    :loading="loading"
    :search="search"
    item-value="name"
    @update:options="loadItems"
    disable-pagination
    disable-sort
    hide-default-footer
  >
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
        <addDateDiol @get-date="onAddDate" />
      </div>
    </template>
    <template
      v-for="header in dynamicHeaders"
      v-slot:[`item.${header.key}`]="{ item }"
    >
      <div class="d-flex justify-start align-center">
        <v-autocomplete
          variant="underlined"
          :items="attendanceStatuses"
          v-model="item[header.key]"
          @change="updateAttendance(item)"
        ></v-autocomplete>
      </div>
    </template>
  </v-data-table-server>
</template>
<script>
import addDateDiol from "../components/addDateDiol.vue";

const desserts = [
  {
    id: 1,
    name: "Frozen Yogurt",
    student_id: "S001",
    gender: "Female",
    attendance: 92,
    attendanceDates: {},
  },
  {
    id: 2,
    name: "Jelly bean",
    student_id: "S002",
    gender: "Male",
    attendance: 85,
    attendanceDates: {},
  },
  {
    id: 3,
    name: "KitKat",
    student_id: "S003",
    gender: "Female",
    attendance: 78,
    attendanceDates: {},
  },
  {
    id: 4,
    name: "Eclair",
    student_id: "S004",
    gender: "Male",
    attendance: 88,
    attendanceDates: {},
  },
  {
    id: 5,
    name: "Gingerbread",
    student_id: "S005",
    gender: "Female",
    attendance: 95,
    attendanceDates: {},
  },
  {
    id: 6,
    name: "Ice cream sandwich",
    student_id: "S006",
    gender: "Male",
    attendance: 90,
    attendanceDates: {},
  },
  {
    id: 7,
    name: "Lollipop",
    student_id: "S007",
    gender: "Female",
    attendance: 82,
    attendanceDates: {},
  },
  {
    id: 8,
    name: "Cupcake",
    student_id: "S008",
    gender: "Male",
    attendance: 91,
    attendanceDates: {},
  },
  {
    id: 9,
    name: "Honeycomb",
    student_id: "S009",
    gender: "Female",
    attendance: 89,
    attendanceDates: {},
  },
  {
    id: 10,
    name: "Donut",
    student_id: "S010",
    gender: "Male",
    attendance: 87,
    attendanceDates: {},
  },
];

const FakeAPI = {
  async fetch({ page, itemsPerPage, sortBy, search }) {
    return new Promise((resolve) => {
      setTimeout(() => {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const items = desserts.slice().filter((item) => {
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
  components: { addDateDiol },
  data: () => ({
    itemsPerPage: 5,
    headers: [
      { title: "No.", key: "id" },
      { title: "Name", key: "name" },
      { title: "Student ID", key: "student_id" },
      { title: "Gender", key: "gender" },
      { title: "Attendance", key: "attendance" },
    ],
    serverItems: [],
    loading: true,
    totalItems: 0,
    name: "",
    search: "",
    attendStatus: null,
    attendanceStatuses: ["Present", "Permission", "Absent"],
    newDateKey: "", // To store the current new date key
    dynamicHeaders: [],
  }),
  watch: {
    name() {
      this.search = String(Date.now());
    },
  },
  created() {
    this.loadItems({ page: 1, itemsPerPage: this.itemsPerPage, sortBy: [] });
  },
  methods: {
    loadItems({ page, itemsPerPage, sortBy }) {
      this.loading = true;
      FakeAPI.fetch({
        page,
        itemsPerPage,
        sortBy,
        search: { name: this.name },
      }).then(({ items, total }) => {
        this.serverItems = items;
        this.totalItems = total;
        this.loading = false;
      });
    },
    onAddDate(val) {
      this.newDateKey = val;
      const newDate = { title: `${val}`, key: `${val}` };
      this.headers.push(newDate);
      this.dynamicHeaders.push(newDate);

      desserts.forEach((item) => {
        this.$set(item, newDate.key, "Present"); // Initialize with default value
        this.$set(item.attendanceDates, newDate.key, "Present");
      });

      this.updateAllAttendance();
      this.loadItems({ page: 1, itemsPerPage: this.itemsPerPage, sortBy: [] });
    },
    updateAttendance(item) {
      let presentCount = 0;
      let totalDays = 0;

      for (let date in item.attendanceDates) {
        if (item.attendanceDates[date] !== "Permission") {
          totalDays++;
          if (item.attendanceDates[date] === "Present") {
            presentCount++;
          }
        }
      }

      const attendancePercentage =
        totalDays > 0 ? (presentCount / totalDays) * 100 : 0;
      item.attendance = Math.round(attendancePercentage);

      this.loadItems({ page: 1, itemsPerPage: this.itemsPerPage, sortBy: [] });
    },
    updateAllAttendance() {
      desserts.forEach((item) => {
        this.updateAttendance(item);
      });
    },
  },
};
</script>
