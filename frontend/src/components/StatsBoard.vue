<script setup lang="ts">
import type { AnalyticDistance } from '@/api/routes';

const props = defineProps<{
  stats: AnalyticDistance[];
  loading: boolean;
  error: string | null;
}>();

const emit = defineEmits<{
  refresh: [];
}>();
</script>

<template>
  <div class="panel">
    <header class="panel__header">
      <p class="eyebrow">Analytics</p>
      <div class="panel__header__title">
        <h2>Distances par code</h2>
        <button type="button" class="ghost" :disabled="props.loading" @click="emit('refresh')">
          {{ props.loading ? 'Actualisation...' : 'Actualiser' }}
        </button>
      </div>
      <p class="muted">Vue synthetique des distances cumulees par code analytique.</p>
    </header>

    <div v-if="props.error" class="error">{{ props.error }}</div>

    <table v-if="props.stats.length" class="table">
      <thead>
        <tr>
          <th>Code</th>
          <th>Trajets</th>
          <th>Distance totale (km)</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="stat in props.stats" :key="stat.analyticCode">
          <td>{{ stat.analyticCode }}</td>
          <td class="text-right">{{ stat.tripCount }}</td>
          <td class="text-right">{{ stat.totalDistanceKm.toFixed(2) }}</td>
        </tr>
      </tbody>
    </table>

    <p v-else-if="!props.loading" class="muted">Aucune donnee pour le moment.</p>
  </div>
</template>
