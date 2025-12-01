# PHP Payment Service

## 1. Installation Guide

### Clone and Navigate to Project

```bash
git clone git@github.com:tapasdatta/codetask.git
cd codetask
```

### Build Docker Images

```bash
docker-compose build
```

### Install Dependencies

```bash
docker-compose run --rm payment-service-dev composer install
```

_Note: If vendor directory is still empty, try removing Docker volumes:_

```bash
docker-compose down -v
docker-compose build
docker-compose run --rm payment-service-dev composer install
```

## 2. Run the Application in Browser

### Start Development Server

```bash
docker-compose up payment-service-dev
```

Access the application at: http://localhost:8000

## 3. Run PHP Unit Tests

```bash
docker-compose run --rm payment-service-test
```
