# WW3 (PHP Natif)

Ce projet a ete migre en PHP simple, sans framework.

## Demarrage

```bash
composer start
```

Puis ouvrir http://localhost:8000

## Architecture

- [public/index.php](public/index.php): front controller + routage simple
- [app/config/config.php](app/config/config.php): configuration (DB)
- [app/models/Database.php](app/models/Database.php): connexion PDO partagee
- [app/controllers](app/controllers): controleurs
- [app/views/front_office](app/views/front_office): vues

## Routes principales

- `GET /` et `GET /actualite`
- `GET /actualite/{slug}`
- `GET /histoire`
- `GET /histoire/{slug}`
- `GET /api/users`
- `GET /api/users/{id}`
- `POST /api/users/{id}`

## Base de donnees

Configurer les acces dans [app/config/config.php](app/config/config.php).

Le schema SQL est disponible dans [db/schema.sql](db/schema.sql).
