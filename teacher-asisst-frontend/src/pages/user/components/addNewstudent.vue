<template>
  <div>
    <v-dialog v-model="dialog" max-width="600">
      <template v-slot:activator="{ props: activatorProps }">
        <v-btn
          class="text-none font-weight-regular"
          prepend-icon="mdi-account"
          text="Add New Student"
          variant="tonal"
          v-bind="activatorProps"
        ></v-btn>
      </template>

      <v-card prepend-icon="mdi-account" title="Edit Student">
        <v-card-text>
          <v-row dense>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="profileData.name"
                label="Name*"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="profileData.gender"
                :items="['Male', 'Female', 'Other']"
                label="Gender"
              ></v-select>
            </v-col>

            <v-col cols="12" sm="4">
              <v-text-field
                v-model="profileData.assignmentScore"
                label="Assignment Score"
              ></v-text-field>
            </v-col>

            <v-col cols="12" sm="4">
              <v-text-field
                v-model="profileData.quizScore"
                label="Quiz Score"
              ></v-text-field>
            </v-col>

            <v-col cols="12" sm="4">
              <v-text-field
                v-model="profileData.midtermScore"
                label="Midterm Score"
              ></v-text-field>
            </v-col>

            <v-col cols="12" sm="4">
              <v-text-field
                v-model="profileData.finalScore"
                label="Final Score"
              ></v-text-field>
            </v-col>

            <v-col cols="12" sm="4">
              <v-text-field
                v-model="profileData.overallScore"
                label="Overall Score"
              ></v-text-field>
            </v-col>
          </v-row>

          <small class="text-caption text-medium-emphasis"
            >*indicates required field</small
          >
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn text="Close" variant="plain" @click="closeDialog"></v-btn>

          <v-btn color="primary" text="Save" @click="saveProfile"></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
      profileData: {
        name: "",
        gender: "",
        assignmentScore: "",
        quizScore: "",
        midtermScore: "",
        finalScore: "",
        overallScore: "",
      },
    };
  },
  methods: {
    closeDialog() {
      this.dialog = false;
    },
    saveProfile() {
      // Emit 'save' event with profile data
      this.$emit("save", this.profileData);

      // Close dialog after saving
      this.dialog = false;
    },
  },
};
</script>

<style scoped>
.v-dialog-title {
  background-color: #1976d2; /* Example background color for dialog title */
  color: white;
}

.v-card {
  border: 1px solid #ccc; /* Example border styling for card */
  border-radius: 8px;
}

.v-card-text {
  padding: 16px; /* Example padding for card content */
}

.v-card-actions {
  justify-content: flex-end; /* Example aligning actions to right */
}

.text-caption {
  font-size: 12px; /* Example custom font size */
}

.text-medium-emphasis {
  color: #888; /* Example custom text color */
}
</style>
