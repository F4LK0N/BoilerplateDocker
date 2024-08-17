# Compose DotEnv and Args
.env
```
PROJECT_NAME="fkn"
PROJECT_HOST="${PROJECT_NAME}.com"
```
compose.yml
```
services:
  alpine:
    container_name: ${COMPOSE_PREFIX}alpine
    build: 
      context: ./.docker/alpine
      args:
        - ARG_HOST=alpine
        - ARG_DOMAIN=${PROJECT_HOST}
        - ARG_TIMEZONE=${PROJECT_TIMEZONE}
        - ARG_OS_VERSION=3.17
        - ARG_OS_APK_MIRROR=${ALPINE_APK_MIRROR}
```
Dockerfile
```
ARG ARG_DOMAIN
ENV DOMAIN="${ARG_DOMAIN}"
```
- https://docs.docker.com/compose/environment-variables/set-environment-variables/#compose-file
- https://docs.docker.com/compose/compose-file/compose-file-v3/#args
- https://stackoverflow.com/questions/58695423/pass-args-to-the-dockerfile-from-docker-compose





# Shell Config
Dockerfile:
```
ENV ENV="/root/.ashrc"
```
- https://stackoverflow.com/questions/38024160/how-to-get-etc-profile-to-run-automatically-in-alpine-docker





# APK Mirrors
https://wiki.alpinelinux.org/wiki/Alpine_Package_Keeper
https://mirrors.alpinelinux.org/
https://mirror.uepg.br/alpine/
https://mirror.uepg.br/alpine/v3.17/main/
https://mirror.uepg.br/alpine/v3.17/community/
