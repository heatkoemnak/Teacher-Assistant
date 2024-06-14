<template>
  <v-dialog max-width="500" persistent v-model="dialogVisible">
    <template v-slot:activator="{ props: activatorProps }">
      <v-btn v-bind="activatorProps" color="#F3797E" variant="flat">
        <v-icon dark>mdi-pencil</v-icon>
      </v-btn>
    </template>

    <template v-slot:default>
      <v-card>
        <v-card-title>Edit Student</v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="editedStudent.name"
                  label="Name"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editedStudent.student_id"
                  label="Student ID"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editedStudent.gender"
                  label="Gender"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editedStudent.assignment"
                  label="Assignment"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editedStudent.quiz"
                  label="Quiz"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editedStudent.midterm"
                  label="Midterm"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editedStudent.final"
                  label="Final"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editedStudent.overall"
                  label="Overall"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="dialogVisible = false">Close</v-btn>
          <v-btn color="primary" @click="saveChanges">Save</v-btn>
        </v-card-actions>
      </v-card>
    </template>
  </v-dialog>
</template>

<script>
export default {
  props: {
    student: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      dialogVisible: false,
      editedStudent: { ...this.student }, // Make a copy of student prop for editing
    };
  },
  methods: {
    saveChanges() {
      // Emit 'save' event with edited student data
      this.$emit("save", this.editedStudent);

      // Close dialog after saving
      this.dialogVisible = false;
    },
  },
};
</script>
