FROM alpine:latest

ENV HUGO_VERSION=0.105.0
ENV BUILD_DRAFTS=true

RUN apk update && \
    apk add curl git && \
    curl -s -L "https://github.com/gohugoio/hugo/releases/download/v${HUGO_VERSION}/hugo_${HUGO_VERSION}_linux-amd64.tar.gz" | tar -xvz

WORKDIR '/site'
# Site files should be mounted to /site

#ENTRYPOINT /hugo serve --buildDrafts --port 8081 --bind 0.0.0.0
CMD /bin/sh
