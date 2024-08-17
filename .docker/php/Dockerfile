FROM alpine:3.20
LABEL "org.opencontainers.image.authors"="F4LK0N"
LABEL "org.opencontainers.image.version"="1.0"

########################################################################################################################
### SETUP ###
########################################################################################################################
WORKDIR /

### CONTAINER ###
ENV ROOT_DATA="/data"
ENV ROOT_LOGS="/logs"

### OS ###
ENV ENV="/root/.ashrc"
ENV OS_VERSION="3.20"
ENV OS_APK_MIRROR="https://mirror.uepg.br/alpine"
ENV OS_TIMEZONE="America/Sao_Paulo"
ENV OS_DATA="${ROOT_DATA}/os"
ENV OS_LOGS="${ROOT_LOGS}/os"

### APACHE ###
ENV HTTPD_VERSION="2.4"
ENV HTTPD_ROOT="/etc/apache2"
ENV HTTPD_CONF="${HTTPD_ROOT}/httpd.conf"
ENV HTTPD_CONFD="${HTTPD_ROOT}/conf.d"
ENV HTTPD_DATA="${ROOT_DATA}/apache"
ENV HTTPD_LOGS="${ROOT_LOGS}/apache"

### PHP ###
ENV PHP_VERSION="8.2"
ENV PHP_ROOT="/etc/php82"
ENV PHP_CONF="${PHP_ROOT}/php.ini"
ENV PHP_CONFD="${PHP_ROOT}/conf.d"
ENV PHP_DATA="${ROOT_DATA}/php"
ENV PHP_LOGS="${ROOT_LOGS}/php"

### APPLICATION ###
ENV APP_ROOT="/src"

### FILESYSTEM ###
RUN set -eux; \
    mkdir --mode=777 --parents \
        # Container
        ${ROOT_DATA} \
        ${ROOT_LOGS} \
        # OS
        ${OS_DATA} \
        ${OS_LOGS} \
        # APACHE
        ${HTTPD_DATA} \
        ${HTTPD_LOGS} \
        # PHP
        ${PHP_DATA} \
        ${PHP_LOGS} \
        # Application
        ${APP_ROOT} \
    ;

### PACKAGES ###
RUN set -eux; \
    # Mirrors
    rm -f /etc/apk/repositories; \
    echo "${OS_APK_MIRROR}/v${OS_VERSION}/main" >> /etc/apk/repositories; \
    echo "${OS_APK_MIRROR}/v${OS_VERSION}/community" >> /etc/apk/repositories; \
    chmod 644 /etc/apk/repositories; \
    # Update
    apk update; \
    apk upgrade; \
    # Timezone
    apk add --virtual temp_dependencies \
        tzdata \
    ; \
    cp "/usr/share/zoneinfo/${OS_TIMEZONE}" /etc/localtime; \
    echo "${OS_TIMEZONE}" > /etc/timezone; \
    apk del temp_dependencies; \
    # Install
    apk add --no-cache \
        # OS
        openrc \
        iftop \
        # Apache
        apache2 \
        apache2-ssl \
        # PHP
        php82-apache2 \
        php82-bcmath \
        #php82-bz2 \
        #php82-calendar \
        php82-common \
        #php82-ctype \
        php82-curl \
        php82-dom \
        #php82-iconv \
        php82-fileinfo \
        #php82-gd \
        php82-gettext \
        php82-mbstring \
        php82-mysqli \
        #php82-mysqlnd \
        php82-openssl \
        php82-pdo \
        php82-pdo_mysql \
        php82-pdo_pgsql \
        #php82-pdo_sqlite \
        php82-pgsql \
        php82-phar \
        php82-session \
        php82-tokenizer \
        php82-xml \
        php82-xmlwriter \
        php82-opcache \
        php82-pecl-psr \
        php82-pecl-xdebug \
    ; \
### CLEAN ###
    # APK
    rm -rf /var/cache/apk/*; \
    rm -rf /var/lib/apk/*; \
    rm -rf /etc/apk/cache/*; \
    # Apache - Config
    rm ${HTTPD_CONF}; \
    rm ${HTTPD_CONFD}/*; \
    rm -r "/var/www"; \
    # PHP - Config
    rm ${PHP_CONF}; \
    rm ${PHP_CONFD}/*;

### IMPORT ###
# Shell
COPY --chmod=755 "./root/.ashrc" "/root/.ashrc"
# Adm && Entrypoint
COPY --chmod=755 "./usr/local/bin/*" "/usr/local/bin/"

# Composer
COPY --from=f4lk0n/fkn:data "/data/composer/latest/composer" "/usr/local/bin/composer"

# Apache
COPY ".docker/php/.${HTTPD_CONF}" "${HTTPD_CONF}"
COPY ".docker/php/.${HTTPD_CONF}-DEV" "${HTTPD_CONF}-DEV"
COPY ".docker/php/.${HTTPD_CONF}-STAG" "${HTTPD_CONF}-STAG"
COPY ".docker/php/.${HTTPD_CONF}-PROD" "${HTTPD_CONF}-PROD"
# PHP
COPY ".docker/php/.${PHP_CONF}" "${PHP_CONF}"
COPY ".docker/php/.${PHP_CONF}-DEV" "${PHP_CONF}-DEV"
COPY ".docker/php/.${PHP_CONF}-STAG" "${PHP_CONF}-STAG"
COPY ".docker/php/.${PHP_CONF}-PROD" "${PHP_CONF}-PROD"

#########################################################################################################################
#### CONTAINER ###
#########################################################################################################################
STOPSIGNAL SIGWINCH
ENTRYPOINT ["container-entrypoint"]
CMD ["RUN"]
