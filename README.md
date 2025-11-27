# MOB - Defi Fullstack

Application fullstack qui expose l'API `/api/v1` (Symfony/PHP) et une interface Vue 3/TypeScript pour calculer des trajets et consulter des statistiques analytiques.

## Demarrer avec Docker

```bash
docker compose up --build
```

- Backend : http://localhost:8000
- Frontend : http://localhost:5173
- Base PostgreSQL : mot de passe a definir dans `backend/.env.local` via `DATABASE_PASSWORD` (non versionne). Les migrations sont executees automatiquement au demarrage du conteneur backend.

## API (extrait)

| Methode | URL             | Description                               |
| ------- | --------------- | ----------------------------------------- |
| POST    | `/api/v1/routes`| Calcul et enregistrement d'un trajet      |
| GET     | `/api/v1/routes`| Liste des trajets enregistres             |
| GET     | `/api/v1/stats` | Statistiques par code analytique          |

La specification complete est dans `openapi.yml`.

## Lancer sans Docker

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

## Tests

- Backend : `cd backend && ./vendor/bin/phpunit`
- Frontend : `cd frontend && npm run test`

## Notes de securite / DevSecOps

- Aucune donnee secrete n'est versionnee : renseigner `DATABASE_PASSWORD` dans `backend/.env.local`.
- Les migrations Doctrine sont appliquees automatiquement via la commande de demarrage du conteneur backend.
- Le proxy Nginx (frontend) reste pret pour etre configure en HTTPS ; ajouter un certificat cote reverse proxy si necessaire.
