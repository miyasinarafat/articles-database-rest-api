# Articles Database

## Installation steps

### 0. Clone repository
```bash
git clone git@github.com:miyasinarafat/articles-database-rest-api.git
```

### 1. Install the packages
```bash
composer install
```

### 2. Environment setup
Setup necessary config on .env for mysql, memcached, and elasticsearch
```bash
cp .env.example .env
```

Add API key for newsapi.org:
```bash
# News APIs
NEWS_API_ORG_API_TOKEN=
```

### 2. Docker setup
Setup necessary config on .env for mysql, memcached, and elasticsearch
```bash
./vendor/bin/sail build
```
```bash
./vendor/bin/sail up -d
```

### 3. Application setup

```bash
./vendor/bin/sail artisan key:generate
```

```bash
./vendor/bin/sail artisan migrate
```

```bash
# Setting up database with initial categories and sources:
./vendor/bin/sail artisan app:InitDatabaseSetup
```

```bash
# Pull and save articles from APIs by sources:
./vendor/bin/sail artisan app:RetrieveNewsApiOrgArticles
```

```bash
# Indexing articles to Elasticsearch for search:
./vendor/bin/sail artisan search:ArticlesReindex 

# Example indexing:
yellow open articles _NSllINtRe2gqcHW5sFqKQ 1 1 279  0 234.1kb 234.1kb
```

## Access APIs
```bash
http://localhost:8000/
```
**POSTMAN COLLECTION:** https://documenter.getpostman.com/view/1974679/2s93CHvFW1
<img width="1715" alt="image" src="https://user-images.githubusercontent.com/16781160/219980611-e1008edb-e38e-4094-9a9c-4c863d30767f.png">


## Run unit test
```bash
./vendor/bin/sail artisan test
```

[//]: # (![image]&#40;https://user-images.githubusercontent.com/16781160/218310103-7a63602b-1936-4716-bebf-4fc81a48287e.png&#41;)

### Screenshots from frontend app
**1. Articles feed:**
![image](https://user-images.githubusercontent.com/16781160/219980729-9ef0a3cc-82b1-40da-879f-3a01f377122d.png)

**2. Articles search:**
![image](https://user-images.githubusercontent.com/16781160/219980904-b2800c3b-7dc2-4f39-b7e6-afdf6393dd51.png)

**3. User profile:**
![image](https://user-images.githubusercontent.com/16781160/219981527-cff5781e-97f2-4a1e-ab75-68e9ccf74893.png)
