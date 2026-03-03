# R606_eval
Application PHP affichant le contenu d'une table MySQL, conteneurisée avec Docker.

## Démarrage

**1. Cloner le projet**
```bash
git clone https://github.com/4keezix/R606_eval.git
cd R606_eval
```

**2. Configurer l'environnement**
```bash
cp .env.example .env
```
Renseigner les valeurs dans `.env` si besoin.

**3. Lancer le projet**
```bash
docker compose up -d --build
```

L'application est accessible sur [http://localhost:8080](http://localhost:8080).  
Les migrations sont exécutées automatiquement au démarrage.

## Lancer les tests

```bash
docker compose exec app ./vendor/bin/phpunit
```

## Arrêter le projet

```bash
docker compose down
```