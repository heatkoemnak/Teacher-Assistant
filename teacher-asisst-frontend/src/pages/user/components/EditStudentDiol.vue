<template>
  <v-dialog v-model="dialog" max-width="600">
    <template v-slot:activator="{ on, attrs }">
      <v-icon class="me-2" size="small" v-bind="attrs" v-on="on"
        >mdi-pencil</v-icon
      >
    </template>
    <v-card>
      <v-card-title>
        <span class="headline">Edit Student</span>
      </v-card-title>
      <v-card-text>
        <v-form ref="form" v-model="valid">
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
        <v-btn color="blue darken-1" text @click="dialog = false">Cancel</v-btn>
        <v-btn color="blue darken-1" text @click="saveStudent">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: ["student"],
  data() {
    return {
      dialog: false,
      valid: true,
    };
  },
  methods: {
    saveStudent() {
      if (this.$refs.form.validate()) {
        this.$emit("save-student", this.student);
        this.dialog = false;
      }
    },
  },
};
</script>
