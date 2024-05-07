<template>
  <v-empty-state>
    <v-empty-state
      headline="Whoops, 404"
      title="Page not found"
      text="The page you were looking for does not exist"
      image="https://vuetifyjs.b-cdn.net/docs/images/logos/v.png"
    ></v-empty-state>

    <template v-slot:actions>
      <v-btn
        class="text-none"
        color="white"
        elevation="1"
        rounded="lg"
        size="small"
        text="Learn more"
        width="96"
      ></v-btn>

      <v-btn
        class="text-none"
        elevation="1"
        rounded="lg"
        size="small"
        text="Add transfer"
        width="96"
      ></v-btn>
    </template>
  </v-empty-state>
</template>
