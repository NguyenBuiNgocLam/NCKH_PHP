# Sử dụng image PHP + Apache chính thức từ Docker Hub
FROM php:8.1-apache

# Chép toàn bộ mã nguồn vào thư mục webroot của Apache trong container
COPY . /var/www/html/

# Mở port 80
EXPOSE 80
