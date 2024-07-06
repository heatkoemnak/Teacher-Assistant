<template>
  <v-data-table
    :headers="combinedHeaders"
    :items="filteredStudents"
    class="cursor-pointer"
    item-value="name"
    show-select
    :loading="loading"
  >
    <template v-slot:top>
      <v-card-title>Students</v-card-title>

      <v-toolbar color="grey-lighten-5">
        <v-text-field
          v-model="search"
          density="compact"
          label="Search by Name or ID"
          prepend-inner-icon="mdi-magnify"
          variant="outlined"
          hide-details
          class="ml-4"
        ></v-text-field>
        <v-spacer></v-spacer>

        <v-select
          v-model="selectedGender"
          :items="genderOptions"
          label="Filter by Gender"
          class="md-6 mr-4 w-15 grey-lighten-2"
          density="compact"
          hide-details="auto"
          variant="solo"
        ></v-select>
        <addDateDiol @get-date="onAddDate" />
        <v-btn
          class="text-none text-subtitle-1 mr-2"
          color="#F3797E"
          variant="flat"
          @click="ImpPDF"
        >
          PDF
        </v-btn>
        <v-btn
          class="text-none text-subtitle-1 mr-2"
          color="#F3797E"
          variant="flat"
          @click="ExpCSV()"
        >
          EXCEL
        </v-btn>
      </v-toolbar>
    </template>

    <template v-slot:progress>
      <v-overlay :value="loading">
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </template>

    <template v-slot:item.attendance="{ item }">
      {{ item.attendance }}
    </template>

    <template v-for="header in dynamicHeaders" v-slot:[`item.${header.key}`]="{ item }">
      <div class="d-flex justify-start align-center">
        <v-autocomplete
          variant="underlined"
          :items="attendanceStatuses"
          v-model="item.attendanceDates[header.key]"
          @update:model-value="updateAttendance(item)"
        ></v-autocomplete>
      </div>
    </template>
  </v-data-table>
</template>

<script>
import axios from "@/axios";
import { toRaw } from 'vue';
import addDateDiol from "../components/addDateDiol.vue";
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

export default {
  components: { addDateDiol },
  data: () => ({
    headers: [
      { title: "Name", value: "fullname" },
      { title: "ID", value: "id" },
      { title: "Gender", value: "gender" },
      { title: "Attendance", value: "attendance" }, // Ensure attendance is part of headers
    ],
    attendanceStatuses: ["Present", "Absent"],
    dynamicHeaders: [],
    loading: true,
    students: [],
    search: "",
    selectedGender: "",
    genderOptions: [
      { title: "All", value: "" },
      { title: "Male", value: "male" },
      { title: "Female", value: "female" },
      { title: "Other", value: "other" },
    ],
  }),

  created() {
    this.fetchStudentsFromClassId();
  },

  computed: {
    filteredStudents() {
      return this.students.filter((student) => {
        const matchesGender = this.selectedGender
          ? student.user.profile.gender === this.selectedGender
          : true;

        const matchesSearch =
          student.fullname.toLowerCase().includes(this.search.toLowerCase()) ||
          student.id.toString().includes(this.search);
        return matchesGender && matchesSearch;
      });
    },
    combinedHeaders() {
      return [...this.headers, ...this.dynamicHeaders];
    },
  },

  methods: {
    ExpCSV() {
      const studentList = toRaw(this.students);
      const simplifiedStudents = studentList.map(student => {
        const studentData = {
          id: student.id,
          fullname: student.fullname,
          email: student.email,
          gender: student.gender,
          attendance: this.calculateAttendance(student),
        };
        this.dynamicHeaders.forEach(header => {
          studentData[header.title] = student.attendanceDates[header.key];
        });
        return studentData;
      });

      const csvContent = this.convertToCSV(simplifiedStudents);
      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8' });
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', 'export_data.csv');
      link.click();
    },

    ImpPDF() {
      const studentList = toRaw(this.students);
      const simplifiedStudents = studentList.map(student => {
        const studentData = {
          id: student.id,
          fullname: student.fullname,
          email: student.email,
          gender: student.gender,
          attendance: this.calculateAttendance(student),
        };
        this.dynamicHeaders.forEach(header => {
          studentData[header.title] = student.attendanceDates[header.key];
        });
        return studentData;
      });

      let info = [];
      simplifiedStudents.forEach(element => {
        const row = [
          element.id,
          element.fullname,
          element.email,
          element.gender,
          element.attendance,
        ];
        this.dynamicHeaders.forEach(header => {
          row.push(element[header.title]);
        });
        info.push(row);
      });

      let doc = new jsPDF();
      doc.setFontSize(18);
      doc.text('Student list', 14, 22);
      const tableHeader = [
        'id', 'Fullname', 'email', 'gender', 'attendance',
        ...this.dynamicHeaders.map(header => header.title),
      ];
      doc.autoTable({
        startY: 30,
        head: [tableHeader],
        body: info,
      });
      doc.save('export_Data.pdf');
    },

    convertToCSV(data) {
      const headers = Object.keys(data[0]);
      const rows = data.map(obj => headers.map(header => obj[header]));
      const headerRow = headers.join(',');
      const csvRows = [headerRow, ...rows.map(row => row.join(','))];
      return csvRows.join('\n');
    },

    calculateAttendance(student) {
      const totalDates = this.dynamicHeaders.length;
      if (totalDates === 0) return "0%";
      const presentCount = Object.values(student.attendanceDates).filter(status => status === "Present").length;
      return `${((presentCount / totalDates) * 100).toFixed(2)}%`;
    },

    async fetchStudentsFromClassId() {
      try {
        this.loading = true;
        const response = await axios.get(`classes/${this.$route.params.id}`);
        this.students = response.data.students.map(student => {
          const attendanceDates = this.initializeAttendanceDates();
          return {
            ...student,
            fullname: student.user.name,
            email: student.user.email,
            gender: student.user.profile.gender || "N/A",
            attendanceDates: attendanceDates,
            attendance: this.calculateAttendance({
              ...student,
              attendanceDates
            })
          };
        });
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        this.loading = false;
      }
    },

    initializeAttendanceDates() {
      const attendanceDates = {};
      this.dynamicHeaders.forEach(header => {
        attendanceDates[header.key] = "Absent";
      });
      return attendanceDates;
    },
    
    updateAttendance(student) {
      student.attendance = this.calculateAttendance(student);
    },
    
    onAddDate(date) {
      const key = date.replace(/\//g, "_");
      this.dynamicHeaders.push({ title: date, key: key });
      this.students.forEach(student => {
        this.$set(student.attendanceDates, key, "Absent");
        student.attendance = this.calculateAttendance(student); // Recalculate attendance
      });
    },
    
    openDeleteDialog(item) {
      console.log("Deleting student:", item);
      // Implement delete logic as needed
    },
  },
};
</script>
