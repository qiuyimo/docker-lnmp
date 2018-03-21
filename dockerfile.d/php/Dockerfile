ARG PHP_VERSION=7.2.3

FROM php:${PHP_VERSION}-fpm-alpine3.7

ENV TZ=Asia/Shanghai \
    APP_ENV=development

# 构建变量默认值为原始值，笔记本构建统一使用 docker-compose 构建
# 国内镜像地址在 docker-compose.yml 中定义

ARG ALPINE_URL=mirrors.ustc.edu.cn

RUN sed -i "s/dl-cdn.alpinelinux.org/${ALPINE_URL}/g" /etc/apk/repositories \
    && set -xe \
      # 新增的包
      && KHS1994_PHP_FPM_APKS=" \
                            git \
                            # 以下两个均为版本控制系统
                            # subversion \
                            # mercurial \
                            openssh-client \
                            bash \
                            tini \
                            tzdata \
                            " \
      # 不要删除
      && KHS1994_PHP_FPM_RUN_DEPS=" \
                         libmemcached \
                         libpq \
                         libzip \
                         libpng \
                         freetype \
                         libjpeg-turbo \
                         yaml \
                         " \
      # *-dev 编译之后删除
      && KHS1994_PHP_FPM_BUILD_DEPS=" \
                         libressl-dev \
                         libmemcached-dev \
                         cyrus-sasl-dev \
                         postgresql-dev \
                         libzip-dev \
                         libpng-dev \
                         freetype-dev \
                         libjpeg-turbo-dev \
                         yaml-dev \
                         " \
        && apk add --no-cache --virtual .khs1994-php-fpm-run-deps $KHS1994_PHP_FPM_RUN_DEPS \
        && apk add --no-cache linux-headers \
        && apk add --no-cache --virtual .khs1994-php-fpm-apks $KHS1994_PHP_FPM_APKS \
        && apk add --no-cache --virtual .khs1994-php-fpm-build-deps $KHS1994_PHP_FPM_BUILD_DEPS \
        && docker-php-ext-configure zip \
                                    --with-libzip \
        && docker-php-ext-configure gd \
                                    --with-freetype-dir=/usr/include/ \
                                    --with-jpeg-dir=/usr/include/ \
        && docker-php-ext-install -j"$(nproc)" bcmath \
                                               pdo_pgsql \
                                               pdo_mysql \
                                               zip \
                                               gd \
                                               pcntl \
        && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
        && pecl install mongodb \
                        igbinary \
                        redis \
                        memcached \
                        # 安装测试版的扩展，可以在扩展名后加 -beta
                        xdebug \
                        yaml \
                        swoole \
        && docker-php-ext-enable mongodb \
                                 redis \
                                 memcached \
                                 xdebug \
                                 yaml \
                                 igbinary \
                                 # opcache 已默认安装，需要自行载入
                                 opcache \
                                 swoole \
        && apk del .build-deps linux-headers .khs1994-php-fpm-build-deps \
        && rm -rf /tmp/*

 # 创建日志文件夹
RUN mkdir -p /var/log/php-fpm \
 && ln -sf /dev/stdout /var/log/php-fpm/access.log \
 && ln -sf /dev/stderr /var/log/php-fpm/error.log \
 && ln -sf /dev/stderr /var/log/php-fpm/xdebug-remote.log \
 && chmod -R 777 /var/log/php-fpm
WORKDIR /app
