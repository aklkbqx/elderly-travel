docker exec -d php_elderly_travel php -S 0.0.0.0:3000 -t /var/www/html & pid=$!
while true; do
    inotifywait -e modify,move,create,delete *.php
    docker exec php_elderly_travel kill $pid
    docker exec -d php_elderly_travel php -S 0.0.0.0:3000 -t /var/www/html & pid=$!
done