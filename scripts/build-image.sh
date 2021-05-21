#!/bin/sh

docker login -u $CI_REGISTRY_USER -p $CI_REGISTRY_PASSWORD $CI_REGISTRY

docker pull $CI_REGISTRY_IMAGE/$IMAGE_NAME:$ENV_TAG || true

# Build image using cache from container registry
docker build --cache-from $CI_REGISTRY_IMAGE/$IMAGE_NAME:$ENV_TAG  \
             -t $CI_REGISTRY_IMAGE/$IMAGE_NAME:$ENV_TAG \
             -f $DOCKER_FILE .

# Push image into container registry
docker push $CI_REGISTRY_IMAGE/$IMAGE_NAME:$ENV_TAG
