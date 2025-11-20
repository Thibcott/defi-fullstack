<script setup lang="ts">
import { ref } from 'vue'

interface TripResponse {
  from: string
  to: string
  analyticCode: string
  distance: number
  createdAt: string
}

interface AnalyticStat {
  analyticCode: string
  count: number
  totalDistance: number
}

const fromCode = ref('MX')
const toCode = ref('GST')
const analyticCode = ref('PASSAGER')

const loadingCreate = ref(false)
const errorCreate = ref<string | null>(null)
const createdTrip = ref<TripResponse | null>(null)

const loadingStats = ref(false)
const errorStats = ref<string | null>(null)
const stats = ref<AnalyticStat[]>([])

async function createTrip() {
  errorCreate.value = null
  createdTrip.value = null
  loadingCreate.value = true

  try {
    const response = await fetch('/api/trips', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        from: fromCode.value,
        to: toCode.value,
        analyticCode: analyticCode.value,
      }),
    })

    if (!response.ok) {
      const data = await response.json().catch(() => ({}))
      throw new Error(data.error || `Erreur HTTP ${response.status}`)
    }

    const data: TripResponse = await response.json()
    createdTrip.value = data
  } catch (error: any) {
    errorCreate.value = error.message ?? 'Une erreur est survenue'
  } finally {
    loadingCreate.value = false
  }
}

async function loadStats() {
  errorStats.value = null
  stats.value = []
  loadingStats.value = true

  try {
    const response = await fetch('/api/stats/analytic-codes', {
      method: 'GET',
    })

    if (!response.ok) {
      const data = await response.json().catch(() => ({}))
      throw new Error(data.error || `Erreur HTTP ${response.status}`)
    }

    const data: AnalyticStat[] = await response.json()
    stats.value = data
  } catch (error: any) {
    errorStats.value = error.message ?? 'Une erreur est survenue'
  } finally {
    loadingStats.value = false
  }
}
</script>

<template>
  <div style="max-width: 800px; margin: 2rem auto; font-family: system-ui;">
    <h1>Défi MOB – Routage de trains</h1>

    <!-- Création de trajet -->
    <section style="margin-top: 1.5rem;">
      <h2>Créer un trajet</h2>

      <form
        @submit.prevent="createTrip"
        style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1rem;"
      >
        <div>
          <label for="from">Station de départ (code court, ex. MX)</label><br />
          <input
            id="from"
            v-model="fromCode"
            type="text"
            style="width: 100%; padding: 0.4rem;"
          />
        </div>

        <div>
          <label for="to">Station d’arrivée (code court, ex. GST)</label><br />
          <input
            id="to"
            v-model="toCode"
            type="text"
            style="width: 100%; padding: 0.4rem;"
          />
        </div>

        <div>
          <label for="analytic">Code analytique (ex. PASSAGER, FRET…)</label><br />
          <input
            id="analytic"
            v-model="analyticCode"
            type="text"
            style="width: 100%; padding: 0.4rem;"
          />
        </div>

        <button
          type="submit"
          :disabled="loadingCreate"
          style="margin-top: 0.5rem; padding: 0.5rem 1rem; cursor: pointer;"
        >
          {{ loadingCreate ? 'Création en cours...' : 'Créer le trajet' }}
        </button>
      </form>

      <div v-if="errorCreate" style="margin-top: 1rem; color: #b00020;">
        ❌ {{ errorCreate }}
      </div>

      <div
        v-if="createdTrip"
        style="margin-top: 1.5rem; padding: 1rem; border: 1px solid #ddd; border-radius: 4px;"
      >
        <h3>Trajet créé</h3>
        <p>
          <strong>De :</strong> {{ createdTrip.from }}<br />
          <strong>À :</strong> {{ createdTrip.to }}<br />
          <strong>Code analytique :</strong> {{ createdTrip.analyticCode }}<br />
          <strong>Distance :</strong> {{ createdTrip.distance.toFixed(2) }} km<br />
          <strong>Créé le :</strong> {{ createdTrip.createdAt }}
        </p>
      </div>
    </section>

    <!-- Statistiques -->
    <section style="margin-top: 2rem;">
      <h2>Statistiques par code analytique</h2>

      <button
        @click="loadStats"
        :disabled="loadingStats"
        style="margin-top: 0.5rem; padding: 0.5rem 1rem; cursor: pointer;"
      >
        {{ loadingStats ? 'Chargement...' : 'Charger les stats' }}
      </button>

      <div v-if="errorStats" style="margin-top: 1rem; color: #b00020;">
        ❌ {{ errorStats }}
      </div>

      <table
        v-if="stats.length"
        style="margin-top: 1rem; border-collapse: collapse; width: 100%;"
      >
        <thead>
          <tr>
            <th style="border-bottom: 1px solid #ccc; text-align: left; padding: 0.5rem;">
              Code analytique
            </th>
            <th style="border-bottom: 1px solid #ccc; text-align: right; padding: 0.5rem;">
              Nombre de trajets
            </th>
            <th style="border-bottom: 1px solid #ccc; text-align: right; padding: 0.5rem;">
              Distance totale (km)
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in stats" :key="item.analyticCode">
            <td style="border-bottom: 1px solid #eee; padding: 0.5rem;">
              {{ item.analyticCode }}
            </td>
            <td style="border-bottom: 1px solid #eee; padding: 0.5rem; text-align: right;">
              {{ item.count }}
            </td>
            <td style="border-bottom: 1px solid #eee; padding: 0.5rem; text-align: right;">
              {{ item.totalDistance.toFixed(2) }}
            </td>
          </tr>
        </tbody>
      </table>

      <p v-else-if="!loadingStats" style="margin-top: 1rem; color: #666;">
        Aucune statistique encore. Crée quelques trajets puis clique sur “Charger les stats”.
      </p>
    </section>
  </div>
</template>

<style scoped>
h1 {
  margin-bottom: 0.5rem;
}
h2 {
  margin-bottom: 0.25rem;
}
label {
  font-weight: 600;
  font-size: 0.9rem;
}
code {
  background: #f5f5f5;
  padding: 0.1rem 0.25rem;
  border-radius: 3px;
}
</style>
