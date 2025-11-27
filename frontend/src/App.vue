<script setup lang="ts">
import RouteForm from '@/components/RouteForm.vue';
import RouteSummary from '@/components/RouteSummary.vue';
import StatsBoard from '@/components/StatsBoard.vue';
import { useRouteCreation } from '@/composables/useRouteCreation';
import { useAnalyticsStats } from '@/composables/useAnalyticsStats';
import { onMounted } from 'vue';
import type { RouteRequest } from '@/api/routes';

const routeCreation = useRouteCreation();
const stats = useAnalyticsStats();

const updateForm = (patch: Partial<RouteRequest>) => {
  Object.assign(routeCreation.form, patch);
};

const submitAndRefresh = async () => {
  await routeCreation.submit();
  if (!routeCreation.error.value) {
    stats.refresh();
  }
};

onMounted(() => {
  stats.refresh();
});
</script>

<template>
  <div class="page">
    <header class="hero">
      <p class="eyebrow">MOB - Defi Fullstack</p>
      <h1>Tracer les routes. Observer les chiffres.</h1>
      <p class="muted">
        Frontend connecte a l'API /api/v1 pour calculer des trajets et compiler les statistiques
        analytiques.
      </p>
    </header>

    <main class="layout">
      <section class="layout__left">
        <RouteForm
          :form="routeCreation.form"
          :loading="routeCreation.loading.value"
          :error="routeCreation.error.value"
          @change="updateForm"
          @submit="submitAndRefresh"
        />
        <RouteSummary :route="routeCreation.lastRoute.value" />
      </section>

      <section class="layout__right">
        <StatsBoard
          :stats="stats.stats.value"
          :loading="stats.loading.value"
          :error="stats.error.value"
          @refresh="stats.refresh"
        />
      </section>
    </main>
  </div>
</template>
