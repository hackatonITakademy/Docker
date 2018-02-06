FROM mysql:latest

# Environment variables
ENV MYSQL_ROOT_PASSWORD 0000

# Create Database
COPY database.sql /docker-entrypoint-initdb.d/database.sql
