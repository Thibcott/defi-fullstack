<script setup lang="ts">
import type { RouteResponse } from '@/api/routes';

defineProps<{
  route: RouteResponse | null;
}>();
</script>

<template>
  <div class="panel">
    <header class="panel__header">
      <p class="eyebrow">Resultat</p>
      <h2>Dernier trajet</h2>
      <p class="muted">Distance et chemin calcules en direct.</p>
    </header>

    <div v-if="route" class="summary">
      <div class="summary__row">
        <span>De</span>
        <strong>{{ route.fromStationId }}</strong>
      </div>
      <div class="summary__row">
        <span>A</span>
        <strong>{{ route.toStationId }}</strong>
      </div>
      <div class="summary__row">
        <span>Code analytique</span>
        <strong>{{ route.analyticCode }}</strong>
      </div>
      <div class="summary__row">
        <span>Distance</span>
        <strong>{{ route.distanceKm.toFixed(2) }} km</strong>
      </div>
      <div class="summary__row">
        <span>Chemin</span>
        <strong>{{ route.path.join(' \u2794 ') }}</strong>
      </div>
      <div class="summary__row">
        <span>Horodatage</span>
        <strong>{{ new Date(route.createdAt).toLocaleString() }}</strong>
      </div>
    </div>
    <p v-else class="muted">Aucun trajet pour le moment.</p>
  </div>
</template>
