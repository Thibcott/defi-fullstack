FROM node:22-alpine AS build
WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

FROM nginx:alpine

# On remplace la config par notre nginx.conf
COPY nginx.conf /etc/nginx/conf.d/default.conf

# On copie les fichiers buildés de Vite
COPY --from=build /app/dist /usr/share/nginx/html
