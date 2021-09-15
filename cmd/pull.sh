#!/bin/sh

for image in $(docker images -f "reference=registry.caffeina.co/devops/nador-images/*/*/*" --format {{.Repository}}); do docker pull $image; done