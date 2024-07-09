# Use the official MariaDB 10.4.32 image from Docker Hub
FROM mariadb:10.4.32

# Copy the initialization script to the Docker image
COPY db_init.sql /docker-entrypoint-initdb.d/

# Expose the default MariaDB port
EXPOSE 3306
