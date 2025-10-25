# Docker image for our server. Switches the PHP config for dev/prod based on the build target.

# We want an Apache server with the PHP addon, which is provided by the base image below.
FROM php:apache AS base

	# Install the MySQLi extension for working with the database.
	RUN docker-php-ext-install mysqli

	# The web server runs on Port 80 (HTTP).
	EXPOSE 80/tcp

	# Load the shared config overrides for both profiles.
	COPY php.overrides.ini "$PHP_INI_DIR/conf.d"

FROM base AS dev

	# Use the development PHP configuration.
	RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

FROM base AS prod

	# The source directory for the http web root.
	ARG SOURCE_DIR

	# Use the production PHP configuration.
	RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

	# Copy the web root into the image.
	COPY ${SOURCE_DIR} "/var/www/html"
