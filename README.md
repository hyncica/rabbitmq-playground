# rabbitmq-playground
Repository to play, test and learn to work with RabbitMQ

## Branches
- **master** - main branch, contains simplest version of playground. RabbitMQ is running in default configuration.
- **ELK** - adds ELK stack for better monitoring of what's happening in RabbitMQ. By [VencaH](https://github.com/VencaH), thanks. 

## Content
This repository contains a docker compose definition with following services
1. RabbitMQ service based on rabbitmq:4-management image
2. Producer service based on php:8.3-apache image
3. Consumer service based on php:8.3-cli image
4. Logstash service based on logstash:8.17.3 image
5. Elasticsearch service based on elasticsearch:8.17.3 image
6. Kibana service base on kibana:8.17.3 image
7. Kibana-sysuser-pwd-change serivice based on curl:latest image

### Producer service
Produce service contains a simple HTML form for entering name and amount. 
After submitting the form, the php script will encode the data as JSON and sent them 
to RabbitMQ.

### Consumer service
Upon receiving a message from the RabbitMQ service, the consumer service will update
Google Spreadsheet using API.

### Kibana-sysuser-pwd-change service
First start of Kibana require change in elastic kibana system user "kibana_system". 
To automate this, a side car service is defined. This service set up the password to value
in env variable `KIBANA_PASSWORD` using the elastic API endpoint.

## Prerequisites
1. Docker
2. Google service account (see https://cloud.google.com/iam/docs/service-account-overview)
3. Google Spreadsheet shared with the service account. Editor access to spreadsheet is required.

## How to run this
1. Download a credential file for the service account and place it in `consumer/credentials` folder.
2. Copy `.env.dist` file to `.env` and set values. 
3. Run the containers. You can use the following command to run all services: 
```shell
docker compose up -d
```
4. (Optional) Import default dashboard and related objects from kibana/export.ndjson.
To access producer web application access http://localhost:8050,
to access rabbitMQ management access http://localhost:8080,
to access Kibana web application access http://localhost:5601.

## Config variables in .env file
- `GOOGLE_APPLICATION_CREDENTIALS` variable contains a path to google credential file. 
The `consumer` folder is mounted to `/app` folder in container so `consumer/credentials` is `/app/credentials`
in container.
- `GOOGLE_SPREADSHEET_ID` variable contains ID of spreadsheet. You can find spreadsheet's ID in URL while editing
the file. The Google spreadsheet URL looks like https://docs.google.com/spreadsheets/d/<SPREADSHEET_ID>/edit?gid=0#gid=0 
- `GOOGLE_LIST_NAME` variable contains the name of list that consumer service should update.
- `ELASTICSEARCH_PASSWORD` variable contains password for the default user for Elastic, also used by services (Logstash, Kibana).
- `KIBANA_PASSWORD` varible contains password for the kibana system user "kibana_system".
