FROM php:8.2-apache

RUN a2enmod rewrite
RUN printf 'upload_max_filesize=5M\npost_max_size=110M\nmax_file_uploads=21\n' > /usr/local/etc/php/conf.d/uploads.ini