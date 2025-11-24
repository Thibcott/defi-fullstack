# 🚄 Défi Fullstack – Solution Simple

Ce projet propose une petite application fullstack pour le défi MOB :

* **Backend** : Symfony (API REST) + PHP 8
* **Frontend** : Vue 3 + TypeScript
* **Données** : fichiers `stations.json` & `distances.json`
* **Fonctions** : créer des trajets, calculer des distances, afficher des statistiques

---

## 🚀 Lancer le projet avec Docker (recommandé)

Depuis la racine du projet :

```bash
docker compose up --build
```

Cela démarre :

* le **backend** sur [http://localhost:8000](http://localhost:8000)
* le **frontend** sur [http://localhost:5173](http://localhost:5173)
* la **base PostgreSQL** automatiquement

Aucune configuration supplémentaire n’est nécessaire.

---

## 🧩 Structure du projet

```
defi-fullstack/
 ├── backend/      → API Symfony
 ├── frontend/     → Application Vue 3
 ├── stations.json
 ├── distances.json
 └── docker-compose.yml
```

---

## 🔌 Endpoints utiles

| Méthode | URL                     | Description               |
| ------- | ----------------------- | ------------------------- |
| POST    | `/trips`                | Créer un trajet           |
| GET     | `/stats/analytic-codes` | Stats par code analytique |

---

## 🛠️ Lancer sans Docker

### Backend

```bash
cd backend
composer install
php -S localhost:8000 -t public
```

### Frontend

```bash
cd frontend
npm install
npm run dev
```

---

## 📦 Données utilisées

* **stations.json** : liste des gares MOB
* **distances.json** : distances entre gares pour le calcul

Le calcul utilise l’algorithme du **plus court chemin (Dijkstra)**.

---

## 📄 Tests

### Backend

```bash
cd backend
./vendor/bin/phpunit
```

### Frontend

```bash
cd frontend
npm run test
```

---

## 🎯 Objectif du défi

Fournir une solution simple et fonctionnelle permettant :

* la création de trajets
* le calcul automatique des distances
* l’obtention de statistiques
* une interface claire et réactive
