<template>
  <v-dialog max-width="500">
    <template v-slot:activator="{ props: activatorProps }">
      <v-btn
        v-bind="activatorProps"
        color="#F3797E"
        variant="flat"
      >
        <v-icon dark>mdi-plus</v-icon>Add Date
      </v-btn>
    </template>

    <template v-slot:default="{ isActive }">
      <v-card title="Add New Date">
        <row>
          <v-col class="d-flex justify-center">
            <v-date-picker v-model="date" color="#F3797E" show-adjacent-months></v-date-picker>
          </v-col>
        </row>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            text="Close"
            @click="isActive.value = false;"
          ></v-btn>
          <v-btn  @click="isActive.value = false; addDate()">Add</v-btn>
        </v-card-actions>
      </v-card>
    </template>
  </v-dialog>
</template>

<script>
 export default {
  data: () => ({
      date: null,
    }),
  methods: {
    addDate() {
      const dateString = `${this.date}`;
      const date = new Date(dateString);
      const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
      const formattedDate = date.toLocaleDateString('en-GB', options);
      this.$emit('get-date', formattedDate)
    }
  }
 }
</script>
