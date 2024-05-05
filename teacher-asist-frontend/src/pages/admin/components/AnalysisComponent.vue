<template>
  <v-row dense>
    <v-col v-for="(card, i) in cards" :key="i" cols="12" md="4">
      <v-card elevation="4">
        <div class="pa-4">
          <div class="ps-4 text-caption text-medium-emphasis">{{ card.title }}</div>

          <v-card-title class="pt-0 mt-n1 d-flex align-center">
            <div class="me-2">{{ card.value }}</div>

            <v-chip
              :color="card.color"
              :prepend-icon="`mdi-arrow-${card.change.startsWith('-') ? 'down' : 'up'}`"
              class="pe-1"
              size="x-small"
              label
            >
              <template #prepend>
                <v-icon size="10"></v-icon>
              </template>

              <span class="text-caption">{{ card.change }}</span>
            </v-chip>
          </v-card-title>
        </div>

        <v-sparkline
          :color="card.color"
          :gradient="[`${card.color}E6`, `${card.color}33`, `${card.color}00`]"
          :model-value="card.data"
          height="50"
          line-width="1"
          min="0"
          padding="0"
          fill
          smooth
        ></v-sparkline>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { ref } from 'vue';

export default {
  setup() {
    const bandwidth = ref([5, 2, 5, 9, 5, 10, 3, 5, 3, 7, 1, 8, 2, 9, 6]);
    const cache = ref([9, 9, 9, 9, 8.9, 9, 9, 9, 9, 9]);

    const cards = ref([
      {
        title: 'Bandwidth Used',
        value: '1.01 TB',
        change: '-20.12%',
        color: '#da5656',
        data: bandwidth.value,
      },
      
      {
        title: 'Cache Hit Rate',
        value: '95.69 %',
        change: '0.75%',
        color: '#2fc584',
        data: cache.value,
      },
    ]);

    return {
      cards,
    };
  },
};
</script>
