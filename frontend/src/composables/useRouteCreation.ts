import { reactive, ref } from 'vue';
import { createRoute, type RouteRequest, type RouteResponse } from '@/api/routes';

export function useRouteCreation() {
  const form = reactive<RouteRequest>({
    fromStationId: 'MX',
    toStationId: 'GST',
    analyticCode: 'PASSAGER',
  });

  const lastRoute = ref<RouteResponse | null>(null);
  const loading = ref(false);
  const error = ref<string | null>(null);

  const submit = async () => {
    error.value = null;
    loading.value = true;
    lastRoute.value = null;

    try {
      lastRoute.value = await createRoute({ ...form });
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Une erreur est survenue';
    } finally {
      loading.value = false;
    }
  };

  return {
    form,
    lastRoute,
    loading,
    error,
    submit,
  };
}
