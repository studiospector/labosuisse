FROM mesosphere/aws-cli:latest

RUN apk add --update mysql-client

COPY ./backup_service/periodic/weekly /etc/periodic/weekly
RUN chmod -R +x /etc/periodic

ENTRYPOINT [ "/usr/sbin/crond" ]
CMD [ "-f" ]
