<template>
  <v-dialog v-model="show" max-width="500px">
    <v-card>
      <v-card-title>
        <span class="text-h5">Edit Student</span>
      </v-card-title>
      <v-card-text>
        <v-form ref="form">
          <v-text-field
            v-model="student.name"
            label="Name"
            required
          ></v-text-field>
          <v-text-field
            v-model="student.gender"
            label="Gender"
            required
          ></v-text-field>
          <v-text-field
            v-model="student.assignment"
            label="Assignment"
            required
            type="number"
          ></v-text-field>
          <v-text-field
            v-model="student.quiz"
            label="Quiz"
            required
            type="number"
          ></v-text-field>
          <v-text-field
            v-model="student.midterm"
            label="Midterm"
            required
            type="number"
          ></v-text-field>
          <v-text-field
            v-model="student.final"
            label="Final"
            required
            type="number"
          ></v-text-field>
          <v-text-field
            v-model="student.overall"
            label="Overall"
            required
            type="number"
          ></v-text-field>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
        <v-btn color="blue darken-1" text @click="save">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: {
    show: Boolean,
    student: Object,
  },
  methods: {
    close() {
      this.$emit("close");
    },
    save() {
      this.$emit("save", this.student);
      this.close();
    },
  },
};
</script>

<style scoped></style>
