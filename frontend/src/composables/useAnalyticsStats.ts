import { ref } from 'vue';
import { fetchStats, type AnalyticDistance } from '@/api/routes';

export function useAnalyticsStats() {
  const stats = ref<AnalyticDistance[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);

  const refresh = async () => {
    loading.value = true;
    error.value = null;
    stats.value = [];

    try {
      stats.value = await fetchStats();
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Une erreur est survenue';
    } finally {
      loading.value = false;
    }
  };

  return {
    stats,
    loading,
    error,
    refresh,
  };
}
