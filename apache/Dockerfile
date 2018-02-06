FROM ubuntu:latest

RUN apt-get update
RUN apt-get install -y nano
RUN apt-get install -y apache2

RUN service apache2 start

RUN apt-get install -y php7.0
RUN apt-get install -y php7.0-mysql
RUN apt-get install -y libapache2-mod-php7.0
RUN apt-get install -y php7.0-mbstring

RUN apt-get upgrade -y
RUN service apache2 restart

RUN apt-get install -y php7.0-intl
RUN apt-get install -y php7.0-xsl
RUN apt-get install -y unzip

RUN apt-get install curl -y
RUN apt-get install -y git

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer.phar
RUN echo alias composer='/usr/local/bin/composer.phar' >> ~/.bashrc
RUN . ~/.bashrc

# RUN git clone https://github.com/hackatonITakademy/Hackaton-API.git /var/www/api.hackaton
# RUN cd /var/www/api.hackaton && cp .env.example .env

COPY api.hackaton.conf /etc/apache2/sites-available/api.hackaton.conf
RUN cd /etc/apache2/sites-available/ && a2ensite api.hackaton.conf

COPY envvars /etc/apache2/envvars
RUN service apache2 start

# RUN cd /var/www/api.hackaton/ && composer install

#RUN cd /var/www/api.hackaton/ && chmod -R 777 storage/
#RUN cd /var/www/api.hackaton/ && php artisan key:generate


# CMD cp .env.example .env
# VOLUME ["/var/www"]
