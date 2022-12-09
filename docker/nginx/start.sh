APP_DOMAIN=$(echo $APP_URL | sed -E s/https?:\\/\\///g)

sed s/\\[\\[SERVER_NAME\\]\\]/$APP_DOMAIN/g /etc/nginx/templates/site.conf.template > /etc/nginx/conf.d/site.conf
nginx -g "daemon off;"
