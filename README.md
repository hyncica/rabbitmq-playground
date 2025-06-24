# rabbitmq-playground
Repository to play, test and learn to work with RabbitMQ

## Content
This repository contains a docker compose definition with three services
1. RabbitMQ service based on rabbitmq:4-management image
2. Producer service based on php:8.3-apache image
3. Consumer service based on php:8.3-cli

### Producer service
Produce service contains a simple HTML form for entering name and amount. 
After submitting the form, the php script will encode the data as JSON and sent them 
to RabbitMQ.

### Consumer service
Upon receiving a message from the RabbitMQ service, the consumer service will update
Google Spreadsheet using API.

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
To access producer web application access http://localhost:8050,
to access rabbitMQ management access http://localhost:8080

## Config variables in .env file
- `GOOGLE_APPLICATION_CREDENTIALS` variable contains a path to google credential file. 
The `consumer` folder is mounted to `/app` folder in container so `consumer/credentials` is `/app/credentials`
in container.
- `GOOGLE_SPREADSHEET_ID` variable contains ID of spreadsheet. You can find spreadsheet's ID in URL while editing
the file. The Google spreadsheet URL looks like https://docs.google.com/spreadsheets/d/<SPREADSHEET_ID>/edit?gid=0#gid=0 
- `GOOGLE_LIST_NAME` variable contains the name of list that consumer service should update. 
