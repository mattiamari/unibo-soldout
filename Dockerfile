FROM alpine:latest AS build

ENV TZ=Europe/Rome

RUN apk add --no-cache npm composer php7-tokenizer php7-zip

WORKDIR /build

# Copy and install dependencies BEFORE copying the rest of the code so we can cache
# the layer, not having to download all deps again if a source file changes
COPY package.json package-lock.json composer.json composer.lock ./

# Running composer before npm as php dependencies change less often
RUN composer install
RUN npm i

COPY . .

RUN npm run build-app && \
    npm run build-manager

FROM scratch

WORKDIR /soldout

COPY --from=build build/api api
COPY --from=build build/app/dist app/
COPY --from=build build/app/res app/res
COPY --from=build build/app/favicon.ico build/app/manifest.json build/app/style.css app/

COPY --from=build build/manager/*.php manager/
COPY --from=build build/manager/*.js manager/
COPY --from=build build/manager/style.css manager/
COPY --from=build build/manager/logo.svg manager/

COPY --from=build build/vendor vendor