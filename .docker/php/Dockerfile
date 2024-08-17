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
    ; \
    # Clean
    rm -rf /var/cache/apk/*; \
    rm -rf /var/lib/apk/*; \
    rm -rf /etc/apk/cache/*;

### IMPORT ###
# Shell
COPY --chmod=755 "./root/.ashrc" "/root/.ashrc"
# Adm && Entrypoint
COPY --chmod=755 "./usr/local/bin/*" "/usr/local/bin/"

#########################################################################################################################
#### CONTAINER ###
#########################################################################################################################
STOPSIGNAL SIGWINCH
ENTRYPOINT ["container-entrypoint"]
CMD ["RUN"]
