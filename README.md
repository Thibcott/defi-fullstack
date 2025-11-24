o# 🚄 Défi Fullstack -- MOB (Montreux--Oberland Bernois)

Ce projet implémente le défi technique proposé par le MOB :\
✔ un **backend PHP 8 / Symfony** exposant une API\
✔ un **frontend Vue 3 + TypeScript** consommant cette API\
✔ calcul des distances entre deux stations\
✔ création de trajets avec codes analytiques\
✔ statistiques basées sur les trajets\
✔ tests backend + frontend\
✔ lancement simple en local (PHP / npm)\
✔ compatible Docker (si Docker Desktop installé)

## 🗂️ Architecture du projet

    defi-fullstack/
    │
    ├── backend/          → Backend Symfony (API)
    ├── frontend/         → Frontend Vue + TypeScript
    ├── stations.json     → Liste des stations
    ├── distances.json    → Distances entre stations
    └── README.md

# 🔧 Backend -- Symfony (PHP 8)

Endpoints : - `GET /stations` - `GET /distance?from=MX&to=CGE` -
`POST /trips` - `GET /trips` - `GET /stats/analytic-codes`

## Lancer le backend

    cd backend
    composer install
    php -S localhost:8000 -t public

## Tests backend

    cd backend
    ./vendor/bin/phpunit

# 🌐 Frontend -- Vue 3 + TypeScript

## Lancer le frontend

    cd frontend
    npm install
    npm run dev

Accessible sur : http://localhost:5173

## Tests frontend

    cd frontend
    npm run test

# 🧠 Fonctionnement

-   Données chargées depuis `stations.json` et `distances.json`
-   Calcul du plus court chemin (Dijkstra)
-   Trajets stockés en mémoire
-   Stats par code analytique

# 🐳 Docker (optionnel)

    docker compose up -d

# 🔚 Conclusion

Solution fullstack complète, testée, simple à lancer et adaptée au
besoin du défi.
