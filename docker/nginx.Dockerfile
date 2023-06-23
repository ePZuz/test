# Yarn build
FROM node:14-alpine AS yarn

RUN apk add --update \
  py-pip \
  git \
  build-base \
  bash \
  && pip install virtualenv \
  && rm -rf /var/cache/apk/*

WORKDIR /var/www/html/
COPY package.json .
RUN yarn
COPY . .
RUN yarn
RUN mkdir -p public/js public/css

# Nginx build
FROM nginx:latest

RUN rm /etc/nginx/conf.d/*

COPY ./docker/phpfpm.conf /etc/nginx/conf.d/phpfpm.conf
COPY ./docker/fastcgi-php.conf /etc/nginx/fastcgi-php.conf

WORKDIR /var/www/html/public
COPY ./public /var/www/html/public
COPY --from=yarn /var/www/html/public/js/ /var/www/html/public/js/
COPY --from=yarn /var/www/html/public/css/ /var/www/html/public/css/

