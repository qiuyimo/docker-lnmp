FROM postgres:10.3-alpine

RUN mkdir -p /var/log/postgresql \
&& ln -sf /dev/stdout /var/log/postgresql/access.log \
&& ln -sf /dev/stderr /var/log/postgresql/error.log \
&& chmod -R 777 /var/log/postgresql