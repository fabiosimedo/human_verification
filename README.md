docker exec -it humano_app php artisan view:clear &&
docker exec -it humano_app php artisan route:clear &&
docker exec -it humano_app php artisan config:clear &&
docker exec -it humano_app php artisan cache:clear &&
docker-compose restart app


mypasswd1234

docker exec -it humano_db mysql -uhumano_user -phumano_pass humano

docker exec -it humano_app tail -n 50 storage/logs/laravel.log

(
echo "===== ROUTES ====="
cat routes/web.php

echo -e "\n===== CONTROLLERS ====="
find app/Http/Controllers -type f -name "*.php" -print -exec cat {} \;

echo -e "\n===== MODELS ====="
find app/Models -type f -name "*.php" -print -exec cat {} \;

echo -e "\n===== VIEWS ====="
find resources/views -type f -name "*.blade.php" -print -exec cat {} \;

) > dump_humano_codigo.txt

scp root@172.233.29.200:/srv/humano/human_verification/humano_schema.sql ~/Desktop/

scp ~/Desktop/install_humano_mobile_theme.sh root@172.233.29.200:/srv/humano/human_verification