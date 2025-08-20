.PHONY: up down logs db-load init-config
up:
	docker compose up --build -d
	sleep 8
	$(MAKE) init-config
init-config:
	docker compose exec -T app php scripts/init-config.php
db-load:
	docker compose exec -T db bash -lc 'mysql -uroot -proot $${MYSQL_DATABASE}' < db/db-struct.sql
logs:
	docker compose logs -f --tail=200 app
down:
	docker compose down -v
