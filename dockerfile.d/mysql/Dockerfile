FROM mysql:5.7.21

RUN mkdir -p /var/log/mysql \
&& ln -sf /dev/stdout /var/log/mysql/access.log \
&& ln -sf /dev/stderr /var/log/mysql/error.log \
&& chmod -R 777 /var/log/mysql