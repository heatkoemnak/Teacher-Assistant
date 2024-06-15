<template>
  <div>
    <v-app-bar extended color="#365BC2">
      <v-app-bar-title>Teacher's Assistant</v-app-bar-title>

      <v-spacer></v-spacer>
      <!-- <v-avatar size="30">
        <v-img
          alt="John"
          src="https://cdn.vuetifyjs.com/images/john.jpg"
        ></v-img>
      </v-avatar>
      <v-btn icon="mdi-dots-vertical"></v-btn> -->
      <profile-drop-down :user="user" />
    </v-app-bar>

    <v-main class="pt-0">
      <v-container>
        <v-row>
          <v-col cols="12">
            <span class="py-1 px-5 rounded-lg" style="background-color: #E84D72; color:white;">Recently</span>
          </v-col>

          <!-- Recently added classes -->
          <v-col v-for="classItem in recentClasses" :key="classItem.id" cols="3">
            <v-card height="200" elevation="5">
              <v-row class="pa-2" no-gutters>
                <v-col cols="12">
                  <v-img :src="classItem.image" alt="Class Image"></v-img>
                </v-col>
                <v-col cols="12" class="pt-2 d-flex justify-space-between align-center">
                  <span>{{ classItem.class_name }}</span>
                  <v-btn variant="text" size="small" @click="viewClass(classItem.id)">View class</v-btn>
                </v-col>
              </v-row>
            </v-card>
          </v-col>

          <!-- All classes with search functionality -->
          <v-col cols="12" class="d-flex justify-space-between align-center">
            <span>All Classes</span>
            <v-col cols="3">
              <v-text-field
                v-model="searchText"
                append-icon="mdi-magnify"
                label="Search class"
                variant="outlined"
                @input="filterClasses"
              ></v-text-field>
            </v-col>
          </v-col>

          <v-col v-for="classItem in filteredClasses" :key="classItem.id" cols="3">
            <v-card height="200" elevation="5">
              <v-row class="pa-2" no-gutters>
                <v-col cols="12">
                  <v-img :src="classItem.image" alt="Class Image"></v-img>
                </v-col>
                <v-col cols="12" class="pt-2 d-flex justify-space-between align-center">
                  <span>{{ classItem.name }}</span>
                  <v-btn variant="text" size="small" @click="viewClass(classItem.id)">View class</v-btn>
                </v-col>
              </v-row>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </div>
</template>

<script>
import axios from 'axios';
import ProfileDropDown from "./components/ProfileDropDown.vue";

export default {
  components: { ProfileDropDown },
  data() {
    return {
      searchText: '',
      classes: [
        // { id: 1, name: 'ITE-G8-M4', image: 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg' },
        // { id: 2, name: 'ITE-G7-M3', image: 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg' },
        // { id: 3, name: 'Math-G6-A1', image: 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg' },
        // { id: 4, name: 'Science-G8-B2', image: 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg' },
        // { id: 5, name: 'History-G7-C1', image: 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg' },
        // { id: 6, name: 'Art-G6-D1', image: 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg' },
        // { id: 7, name: 'Music-G7-E1', image: 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg' },
        // { id: 8, name: 'Physical Education', image: 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg' },
        // { id: 9, name: 'Biology-G8-A1', image: 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg' },
        // { id: 10, name: 'Chemistry-G7-A2', image: 'https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg' }
      ],
      recentClasses: []
    };
  },

  computed: {
    // recentClasses() {
    //   return this.classes.slice(0, 4);
    // },
    filteredClasses() {
      const searchText = this.searchText.toLowerCase().trim();
      if (!searchText) {
        return this.classes;
      } else {
        return this.classes.filter(classItem =>
          classItem.name.toLowerCase().includes(searchText)
        );
      }
    },
  },

  methods: {
    viewClass(classId) {
      localStorage.setItem('recently', classId)
      this.$router.push(`/class/${classId}/dashboard`);
    },
    getClass(val) {
      const newArrClass = []
      for(let i = 0; i <val.length; i++){
        console.log(val[i].id)
        const thisclass = {
          id : val[i].id,
          name : val[i].class_name,
          image : val[i].image
        }
        newArrClass.push(thisclass)
      }
      this.classes = newArrClass
    },
    async onRecentClasses(val) {
      const RecentlyCLass = await axios.get(`http://localhost:4000/classes/${val}`)
      this.recentClasses.push(RecentlyCLass.data)
    },
  },
  async mounted() {
    const openedClass = localStorage.getItem('recently')
    this.onRecentClasses(openedClass)
    const allclass = await axios.get('http://localhost:4000/classes')
    console.log(allclass.data)
    this.getClass(allclass.data)
    
  }
};
</script>
