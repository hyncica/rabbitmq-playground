#!/bin/sh
curl -f -X POST --cacert config/certs/ca/ca.crt -u "elastic:${ELASTICSEARCH_PASSWORD}" -H "Content-Type: application/json" https://elasticsearch:9200/_security/user/kibana_system/_password -d "{\"password\":\"${KIBANA_PASSWORD}\"}" | grep -q "^{}" 
