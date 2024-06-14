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
                  v-model="student.name"
                  label="Name"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="student.student_id"
                  label="Student ID"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="student.gender"
                  label="Gender"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="student.assignment"
                  label="Assignment"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="student.quiz"
                  label="Quiz"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="student.midterm"
                  label="Midterm"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="student.final"
                  label="Final"
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="student.overall"
                  label="Overall"
                  readonly
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="dialogVisible = false">Close</v-btn>
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
    };
  },
};
</script>
