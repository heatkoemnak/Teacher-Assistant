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
          v-model="item.attendanceDates[header.key]"
          @update:model-value="updateAttendance(item)"
        ></v-autocomplete>
      </div>
    </template>
  </v-data-table-server>
</template>

<script>
import axios from 'axios';
import addDateDiol from "../components/addDateDiol.vue";

let studentsData = [
// {
//     id: 1,
//     name: "Frozen Yogurt",
//     student_id: "S001",
//     gender: "Female",
//     attendance: 0,
//     assignment: 85,
//     quiz: 80,
//     midterm: 88,
//     final: 90,
//     overall: 86, // Adding a fixed value for overall score
//     attendanceDates: {},
//   },
//   {
//     id: 2,
//     name: "Jelly bean",
//     student_id: "S002",
//     gender: "Male",
//     attendance: 0,
//     assignment: 80,
//     quiz: 75,
//     midterm: 83,
//     final: 87,
//     overall: 81, // Adding a fixed value for overall score
//     attendanceDates: {},
//   },
//   {
//     id: 3,
//     name: "KitKat",
//     student_id: "S003",
//     gender: "Female",
//     attendance: 0,
//     assignment: 70,
//     quiz: 68,
//     midterm: 72,
//     final: 74,
//     overall: 71, // Adding a fixed value for overall score
//     attendanceDates: {},
//   },
//   {
//     id: 4,
//     name: "Eclair",
//     student_id: "S004",
//     gender: "Male",
//     attendance: 0,
//     assignment: 85,
//     quiz: 82,
//     midterm: 86,
//     final: 89,
//     overall: 85, // Adding a fixed value for overall score
//     attendanceDates: {},
//   },
//   {
//     id: 5,
//     name: "Gingerbread",
//     student_id: "S005",
//     gender: "Female",
//     attendance: 0,
//     assignment: 92,
//     quiz: 91,
//     midterm: 94,
//     final: 96,
//     overall: 93, // Adding a fixed value for overall score
//     attendanceDates: {},
//   },
];

const FakeAPI = {
  async fetch({ page, itemsPerPage, sortBy, search }) {
    return new Promise((resolve) => {
      setTimeout(() => {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const items = studentsData.slice().filter((item) => {
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
    attendanceStatuses: ["Present", "Absent"],
    newDateKey: "", // To store the current new date key
    dynamicHeaders: [],
    // pathID: this.$route.params.class_id
  }),
  watch: {
    name() {
      this.search = String(Date.now());
    },
  },
  async created() {
    const getClassData = await axios.get(`http://localhost:4000/classes/${this.$route.params.class_id}`)
    studentsData = getClassData.data.students
    console.log(studentsData)
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
    },
    async updateAttendance(item) {
      let presentCount = 0;
      let totalDays = 0;

      for (let date in item.attendanceDates) {
        if (item.attendanceDates[date] !== "Permission") {
          totalDays++;
          if (item.attendanceDates[date] === "Present" || item.attendanceDates[date] === "Permission") {
            presentCount++;
          }
        }
      }

      const attendancePercentage =
        totalDays > 0 ? (presentCount / totalDays) * 100 : 0;
      item.attendance = Math.round(attendancePercentage);

      // Instead of calling loadItems, update serverItems directly
      // const index = this.serverItems.findIndex((i) => i.id === item.id);
      // if (index !== -1) {
      //   this.$set(this.serverItems, index, item);
      // }
      console.log(item.id)
      try {
        await axios.put(`http://localhost:4000/classes/${this.$route.params.class_id}/students/${item.id}`, item);
        console.log("Attendance updated successfully");
      } catch (error) {
        console.error("Failed to update attendance:", error);
      }
    },
  },
};
</script>
