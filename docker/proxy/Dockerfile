FROM nginx:1.29.0

RUN mkdir /etc/nginx/templates
COPY ./nginx.dev.conf /etc/nginx/templates/default.conf.template
COPY ./proxy_params /etc/nginx/proxy_params