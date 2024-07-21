FROM phpdockerio/php:8.3-fpm
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Fix: W: http://ppa.launchpad.net/ondrej/php/ubuntu/dists/jammy/InRelease: Key is stored in legacy trusted.gpg keyring (/etc/apt/trusted.gpg), see the DEPRECATION section in apt-key(8) for details.
RUN cp /etc/apt/trusted.gpg /etc/apt/trusted.gpg.d

# Install selected extensions and other required packages
RUN apt-get update && \
    apt-get -y dist-upgrade && \
    apt-get -y --no-install-recommends install \
        php8.3-bcmath \
        php8.3-curl \
        php8.3-gd \
        php8.3-igbinary \
        php8.3-imagick \
        php8.3-imap \
        php8.3-intl \
        php8.3-mysql \
        php8.3-mbstring \
        php8.3-pgsql \
        php8.3-redis \
        php8.3-soap \
        php8.3-xmlrpc \
        php8.3-xsl \
        php8.3-yaml \
        php8.3-zip \
        php8.3-ldap \
        git \
        libfontconfig1 \
        libxrender1 \
        libxext6 \
        rsync \
        acl \
        vim \
        make \
        qpdf \
        pandoc && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Set the timezone
RUN rm -f /etc/localtime && ln -s /usr/share/zoneinfo/Europe/Warsaw /etc/localtime

# Install Composer
RUN curl -sSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy PHP configuration overrides
COPY php-ini-overrides.ini /etc/php/8.3/fpm/conf.d/99-overrides.ini
COPY php-ini-overrides.ini /etc/php/8.3/cli/conf.d/99-overrides.ini

# Add user with specified UID and GID
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN groupadd -g ${GROUP_ID} myusergroup && \
    useradd -u ${USER_ID} -g myusergroup -m myuser

# Final cleanup
RUN apt-get autoremove
