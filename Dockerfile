FROM php:8.0-cli
COPY . /myapp
WORKDIR /myapp
CMD ["php", "bin/launcher.php"]