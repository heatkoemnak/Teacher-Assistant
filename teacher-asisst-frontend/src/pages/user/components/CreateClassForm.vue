<template>
  <v-dialog v-model="dialog" persistent max-width="600px">
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        color="#4c00d5"
        elevation="8"
        icon="mdi-plus"
        class="ma-4"
        v-bind="attrs"
        v-on="on"
      ></v-btn>
    </template>
    <v-card>
      <v-card-title>
        <span class="headline">Create Class</span>
      </v-card-title>
      <v-card-text>
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-text-field
            v-model="classData.name"
            label="Class Name"
            :rules="[v => !!v || 'Name is required']"
            required
          ></v-text-field>
          <v-text-field
            v-model="classData.slug"
            label="Class Slug"
            :rules="[v => !!v || 'Slug is required']"
            required
          ></v-text-field>
          <v-textarea
            v-model="classData.desc"
            label="Class Description"
            :rules="[v => !!v || 'Description is required']"
            required
          ></v-textarea>
          <v-file-input
            v-model="classData.image"
            label="Class Image"
            accept="image/*"
          ></v-file-input>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="dialog = false">Cancel</v-btn>
        <v-btn color="blue darken-1" text @click="submit">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
      valid: false,
      classData: {
        name: "",
        slug: "",
        desc: "",
        image: null,
      },
    };
  },
  methods: {
    submit() {
      if (this.$refs.form.validate()) {
        this.$emit("create-class", this.classData);
        this.dialog = false;
        this.resetForm();
      }
    },
    resetForm() {
      this.classData = {
        name: "",
        slug: "",
        desc: "",
        image: null,
      };
    },
  },
};
</script>
