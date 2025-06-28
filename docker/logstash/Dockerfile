FROM docker.elastic.co/logstash/logstash:8.17.3

COPY ./logstash/pipeline /usr/share/logstash/pipeline
RUN rm /usr/share/logstash/config/logstash.yml
COPY ./logstash/config/logstash.yml /usr/share/logstash/config/logstash.yml

CMD ["logstash"]
