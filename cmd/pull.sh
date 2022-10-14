#!/bin/sh

for image in $(docker images -f "reference=registry.gitlab.com/never-sleep/devops/nador-images/*/*/*" --format {{.Repository}}); do docker pull $image; done