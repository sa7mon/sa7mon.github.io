FROM alpine:latest

ENV HUGO_VERSION=0.147.9

RUN apk update && \
    apk add curl git && \
    curl -s -L "https://github.com/gohugoio/hugo/releases/download/v${HUGO_VERSION}/hugo_${HUGO_VERSION}_linux-amd64.tar.gz" | tar -xvz

WORKDIR '/site'
# Site files should be mounted to /site

ENTRYPOINT /hugo serve --port 8081 --bind 0.0.0.0 --disableFastRender --buildDrafts