FROM soldout:latest
FROM nginx:alpine

WORKDIR /soldout

COPY --from=0 /soldout www/
COPY nginx.conf /etc/nginx/nginx.conf