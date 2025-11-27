<script setup lang="ts">
import type { RouteRequest } from '@/api/routes';

const props = defineProps<{
  form: RouteRequest;
  loading: boolean;
  error: string | null;
}>();

const emit = defineEmits<{
  change: [payload: Partial<RouteRequest>];
  submit: [];
}>();

function onInput(field: keyof RouteRequest, value: string) {
  emit('change', { [field]: value });
}
</script>

<template>
  <div class="panel">
    <header class="panel__header">
      <p class="eyebrow">Routing</p>
      <h2>Calculer un trajet</h2>
      <p class="muted">
        Utilise les codes courts (max 10 caracteres) pour calculer le chemin le plus court.
      </p>
    </header>

    <form class="form" @submit.prevent="emit('submit')">
      <label class="field">
        <span>Station de depart</span>
        <input
          :value="props.form.fromStationId"
          type="text"
          required
          maxlength="10"
          @input="onInput('fromStationId', ($event.target as HTMLInputElement).value)"
        />
      </label>

      <label class="field">
        <span>Station d'arrivee</span>
        <input
          :value="props.form.toStationId"
          type="text"
          required
          maxlength="10"
          @input="onInput('toStationId', ($event.target as HTMLInputElement).value)"
        />
      </label>

      <label class="field">
        <span>Code analytique</span>
        <input
          :value="props.form.analyticCode"
          type="text"
          required
          @input="onInput('analyticCode', ($event.target as HTMLInputElement).value)"
        />
      </label>

      <div class="form__footer">
        <button type="submit" :disabled="props.loading">
          {{ props.loading ? 'Calcul en cours...' : 'Tracer la route' }}
        </button>
        <p v-if="props.error" class="error">
          {{ props.error }}
        </p>
      </div>
    </form>
  </div>
</template>
