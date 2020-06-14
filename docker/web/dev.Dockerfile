FROM nginx:alpine

WORKDIR /soldout

COPY nginx.conf /etc/nginx/nginx.conf

# app code should be mounted into /soldout/www