# WordPress Docker Local Development Environment

A Docker Compose-based local development environment for WordPress, supporting PHP 8.3, Nginx, MariaDB, and Redis.

## 📦 Tech Stack

| Component | Version | Description |
|-----------|---------|-------------|
| PHP | 8.3-FPM | PHP-FPM Application Server |
| Nginx | 1.31.5 | Web Server |
| MariaDB | 13.0.1 | Database |
| Redis | 8.10 | Cache / Session Storage |

## 🚀 Quick Start

### 1. Prerequisites

Ensure [Docker Desktop](https://www.docker.com/products/docker-desktop/) is installed.

### 2. Configure Environment Variables

Copy the environment template and customize:

```bash
cp .env.docker .env
```

Edit the `.env` file with your settings:

```env
# Docker container name prefix
DOCKER_APP_NAME=leonwp

# Database configuration
DB_DATABASE=leonwp
DB_USERNAME=leonwp
DB_PASSWORD=leonwp

# Application configuration
APP_NAME=wordpress
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=Asia/Shanghai
```

### 3. Start Services

```bash
docker-compose up -d
```

### 4. Access the Site

- **WordPress Site**: http://localhost:16220
- **Nginx Status Page**: http://localhost:16226
- **Database Port**: localhost:16222
- **Redis Port**: localhost:16225

## 📁 Project Structure

```
wp.base/
├── docker-compose.yml          # Docker Compose configuration
├── .env                        # Environment variables (local)
├── .env.docker                 # Environment variables template
├── .gitignore
│
├── docker/
│   ├── php/
│   │   ├── Dockerfile          # PHP 8.3 FPM image (active)
│   │   ├── php.docker.8.2      # PHP 8.2 FPM image (legacy/alternative)
│   │   └── conf.d/
│   │       └── settings.ini    # PHP configuration
│   ├── nginx/
│   │   ├── default.conf        # Nginx configuration
│   │   └── logs/               # Nginx logs directory
│   ├── mariadb/
│   │   └── data/               # MySQL data persistence

│
├── nginx_webroot/              # Web root directory
│   └── default/
│       ├── index.php           # Connection test page
│       └── mail.php            # Email test page
│
└── tars/                       # Archive directory
```

## ⚙️ Configuration

### PHP Configuration (`docker/php/conf.d/settings.ini`)

```ini
file_uploads = On
upload_max_filesize = 64M
post_max_size = 64M
memory_limit = 256M
max_execution_time = 300
max_input_time = 300
```

### PHP Extensions (Active Dockerfile - PHP 8.3)

- **Core Extensions**: mysqli, pdo_mysql, gd, opcache, zip, bcmath, intl, exif
- **Third-party Extensions**: redis, imagick
- **WP-CLI**: Pre-installed and ready to use

### Alternative Dockerfile (`php.docker.8.2` - PHP 8.2)

A legacy Dockerfile with additional features:

- **Additional PHP Extensions**: soap, pcntl, sockets, xsl, mongodb, swoole
- **Debugging**: xdebug
- **Tools**: composer (aliyun mirror), vim, curl, net-tools, telnet
- **Email**: sendmail + msmtp (configured for mailcatcher)
- **System**: cron, iputils-ping

### Nginx Configuration Features

- WordPress pretty permalinks support
- xmlrpc disabled
- Static assets long-term caching
- `.git` directory protection
- Large file upload support (100MB)

## 🔧 Common Commands

### Docker Management

```bash
# Start all services
docker-compose up -d

# Check service status
docker-compose ps

# View logs
docker-compose logs -f

# Stop services
docker-compose down

# Stop and remove data volumes
docker-compose down -v
```

### WP-CLI Usage

Enter the PHP container:

```bash
docker exec -it leonwp.php wp --allow-root <command>
```

Common WP-CLI commands:

```bash
# Install WordPress
docker exec -it leonwp.php wp core install --allow-root \
    --url="http://localhost" \
    --title="My Site" \
    --admin_user="admin" \
    --admin_password="password" \
    --admin_email="admin@example.com"

# Install a plugin
docker exec -it leonwp.php wp plugin install <plugin-name> --allow-root

# Update all plugins
docker exec -it leonwp.php wp plugin update --all --allow-root

# Update WordPress core
docker exec -it leonwp.php wp core update --allow-root
```

### Database Management

```bash
# Enter MariaDB shell
docker exec -it leonwp.mysql mariadb -u root -proot

# Backup database
docker exec leonwp.mysql mariadb-dump -u root -proot leonwp > backup.sql

# Restore database
docker exec -i leonwp.mysql mariadb -u root -proot leonwp < backup.sql
```

### PHP Configuration Changes

After editing `docker/php/conf.d/settings.ini`, restart the PHP container:

```bash
docker-compose restart php
```

## 🔌 Port Mapping

| Service | Host Port | Container Port |
|---------|-----------|----------------|
| Nginx HTTP | 16220 | 80 |
| Nginx Status | 16226 | 81 |
| MariaDB | 16222 | 3306 |
| Redis | 16225 | 6379 |

## 📝 WordPress Configuration

Add the following to your WordPress configuration for local development support:

```php
// Remove all data when removing plugin
// define( 'WC_REMOVE_ALL_DATA', true );

// Allow WordPress to make HTTP requests to external/private IPs (for local development)
define( 'ALLOW_UNFILTERED_UPLOADS', true );
add_filter( 'http_request_host_is_external', '__return_true' );
```

## 🔐 Default Credentials

| Item | Value |
|------|-------|
| Database Host | mariadb |
| Database Name | leonwp |
| Database User | leonwp |
| Database Password | leonwp |
| MariaDB Root Password | root |

## 📝 Local Development PHP Configuration

Add the following to your WordPress `wp-config.php` for local development support:

```php
// Remove all data when removing plugin
// define( 'WC_REMOVE_ALL_DATA', true );

// Allow WordPress to make HTTP requests to external/private IPs (for local development)
define( 'ALLOW_UNFILTERED_UPLOADS', true );
add_filter( 'http_request_host_is_external', '__return_true' );


add_filter( 'http_request_args', function ( $args ) {
    $args['reject_unsafe_urls'] = false;
    return $args;
}, 999 );
```

## ⚠️ Notes

1. **Data Persistence**: MariaDB data is stored in `docker/mariadb/data/`. Deleting this directory will result in data loss.
2. **Port Conflicts**: If ports 16220–16226 are in use, modify the port mappings in `docker-compose.yml`.
3. **File Permissions**: The PHP container runs as the `www-data` user. Ensure correct file permissions.
4. **Timezone**: Configure the timezone via `APP_TIMEZONE` in `.env`.
