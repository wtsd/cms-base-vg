# CMS Base (Repository-Ready) — MySQL + Disclaimer

> **Important compatibility notice**  
Runs on **PHP 7.4** for legacy dependency compatibility.  
**Please plan to upgrade to PHP 8.x** (replace PHPExcel with PhpSpreadsheet, pin Composer deps to PHP 8+, run CI on PHP 8.2+, validate Symfony components). 

# CMS Base (Repository-Ready)

Dockerized legacy PHP CMS with MySQL for quick demo.

## Quick start
```bash
cp .env.example .env
make up
make db-load # optional
```
Open http://localhost:8080

> Note: PHP 7.4 image is used for legacy compatibility.

## Included services
- app: PHP 7.4 + Apache (DocumentRoot `dist/web`)
- db: MySQL 8 on host port 3307
