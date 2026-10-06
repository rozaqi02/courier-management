# Courier Management API

A Laravel REST API for managing courier/delivery personnel data.

## Requirements

- PHP >= 8.2
- Composer
- MySQL 8.x

## Setup

```bash
# Clone the repository
git clone <repo-url>
cd courier-management

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
# DB_DATABASE=courier_management
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# (Optional) Seed sample data
php artisan db:seed

# Start server
php artisan serve
```

## API Endpoints

Base URL: `http://localhost:8000/api`

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/couriers` | List all couriers (paginated) |
| POST | `/couriers` | Create a new courier |
| GET | `/couriers/{id}` | Get a specific courier |
| PUT/PATCH | `/couriers/{id}` | Update a courier |
| DELETE | `/couriers/{id}` | Delete a courier |

## Query Parameters (GET /couriers)

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `search` | string | Search by courier name (partial match) | `?search=Budiono` |
| `level` | string | Filter by level (comma-separated) | `?level=2,3` |
| `sort` | string | Sort by field: `name` (default) or `created_at` | `?sort=created_at` |
| `per_page` | integer | Items per page (default: 10) | `?per_page=5` |

## Example Requests

```bash
# List all couriers, sorted by name
GET /api/couriers

# Search by name
GET /api/couriers?search=Budiono+Hadi+Agung

# Filter couriers with level 2 or 3
GET /api/couriers?level=2,3

# Sort by registration date
GET /api/couriers?sort=created_at

# Create a courier
POST /api/couriers
{
    "name": "Ahmad Fauzi",
    "phone": "08123456789",
    "email": "ahmad@example.com",
    "level": 2,
    "vehicle_type": "motorcycle",
    "vehicle_plate": "B 5678 ABC",
    "address": "Jakarta Selatan",
    "status": "active"
}
```

## Courier Fields

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `name` | string | ✅ | Courier full name |
| `phone` | string | ❌ | Phone number |
| `email` | string | ❌ | Email (unique) |
| `level` | integer | ✅ | Courier level (1-5) |
| `vehicle_type` | string | ❌ | `motorcycle`, `car`, or `truck` |
| `vehicle_plate` | string | ❌ | Vehicle plate number |
| `address` | string | ❌ | Courier address |
| `status` | string | ❌ | `active` or `inactive` (default: active) |

## Running Tests

```bash
php artisan test
```
