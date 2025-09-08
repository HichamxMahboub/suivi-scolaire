#!/usr/bin/env bash
# Setup and migrate SQL database for Suivi-Scolaire
# Usage: ./setup_db.sh

# Ensure .env exists
if [ ! -f .env ]; then
  echo ".env not found; copying from .env.example"
  cp .env.example .env
fi
# Load environment variables from .env
export $(grep -v '^#' .env | xargs)
## Create database based on connection type
if [ "${DB_CONNECTION}" = "sqlite" ]; then
  echo "Using SQLite: ensuring database file exists at ${DB_DATABASE}..."
  mkdir -p "$(dirname "${DB_DATABASE}")"
  touch "${DB_DATABASE}"
  echo "SQLite database ready."
else
  echo "Creating database ${DB_DATABASE} using PHP PDO script..."
  if php create_database.php; then
    echo "Database created or already exists."
  else
    echo "Failed to create database via PHP script." >&2
    exit 1
  fi
fi

# Generate application key if missing
echo "Generating application key..."
php artisan key:generate --force || {
  echo "Failed to generate APP_KEY" >&2; exit 1;
}

echo "Clearing config cache..."
php artisan config:clear || true

echo "Running migrations and seeders..."
php artisan migrate:fresh --seed || { echo "Migration failed." >&2; exit 1; }

echo "Database setup complete."
