FROM docker.elastic.co/elasticsearch/elasticsearch:8.17.3

# RUN mkdir -p config/certs/
RUN mkdir -p certs/
COPY ./elastic/cert_config.yml usr/share/certs/cert_config.yml
RUN bin/elasticsearch-certutil ca --silent --pem --out certs/ca.zip
RUN unzip certs/ca.zip -d config/certs
RUN bin/elasticsearch-certutil cert --silent --pem --in usr/share/certs/cert_config.yml --out certs/certs.zip --ca-cert config/certs/ca/ca.crt --ca-key config/certs/ca/ca.key
RUN unzip certs/certs.zip -d config/certs


CMD ["bin/elasticsearch"]


