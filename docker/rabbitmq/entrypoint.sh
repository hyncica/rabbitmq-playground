#!/bin/sh
# Wait for docker to properly setup to avoid "erlang.cookie: eacces" issue
sleep 10
(docker-entrypoint.sh rabbitmq-server) &
echo "Waiting for rabbitmq to start."
      
until rabbitmq-diagnostics check_port_connectivity; do 
    sleep 2
done

echo "rabbitmq running, setting trace on."
rabbitmqctl trace_on -p /
wait
