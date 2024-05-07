<template>
  <div>
    <v-app>
      <v-toolbar flat color="white">
        <v-btn @click="addNew">Add New</v-btn>
      </v-toolbar>

      <v-data-table
        :headers="headers"
        :items="desserts"
        :search="search"
        class="elevation-1"
      >
        <!-- <template #item="{ item }">
          <td>{{ item.name }}</td>
          <td>{{ item.calories }}</td>
          <td class="justify-center layout px-0">
            <v-icon small class="mr-2" @click="editItem(item)"
              >mdi-pencil</v-icon
            >
            <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
          </td>
        </template> -->
      </v-data-table>
    </v-app>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  setup() {
    const search = ref("");
    const headers = [
      { text: "Dessert (100g serving)", value: "name", sortable: false },
      { text: "Calories", value: "calories", sortable: false },
      { text: "Actions", value: "actions", sortable: false, width: "100px" },
    ];
    const desserts = ref([]);
    const editedIndex = ref(-1);
    const editedItem = ref({
      id: 0,
      name: "",
      calories: 0,
    });
    const defaultItem = {
      id: 0,
      name: "New Item",
      calories: 0,
    };

    const initialize = () => {
      desserts.value = [
        { id: 1, name: "Frozen Yogurt", calories: 120 },
        { id: 2, name: "Ice cream sandwich", calories: 200 },
        { id: 3, name: "Eclair", calories: 128 },
        { id: 4, name: "Cupcake", calories: 140 },
        { id: 5, name: "Gingerbread", calories: 159 },
        { id: 6, name: "Jelly bean", calories: 110 },
        { id: 7, name: "Lollipop", calories: 132 },
        { id: 8, name: "Honeycomb", calories: 45 },
      ];
    };

    const editItem = (item) => {
      editedIndex.value = desserts.value.indexOf(item);
      editedItem.value = { ...item };
    };

    const deleteItem = (item) => {
      const index = desserts.value.indexOf(item);
      if (confirm("Are you sure you want to delete this item?")) {
        desserts.value.splice(index, 1);
      }
    };

    const close = () => {
      setTimeout(() => {
        editedItem.value = { ...defaultItem };
        editedIndex.value = -1;
      }, 300);
    };

    const addNew = () => {
      const addObj = { ...defaultItem, id: desserts.value.length + 1 };
      desserts.value.unshift(addObj);
      editItem(addObj);
    };

    const save = () => {
      if (editedIndex.value > -1) {
        Object.assign(desserts.value[editedIndex.value], editedItem.value);
      }
      close();
    };

    onMounted(initialize);

    return {
      search,
      headers,
      desserts,
      editItem,
      deleteItem,
      addNew,
      save,
    };
  },
};
</script>

<style>
/* Your CSS styles here */
</style>
