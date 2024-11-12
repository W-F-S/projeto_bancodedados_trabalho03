Rode usando: php -S localhost:3030


programa depende da extensão PostgresSQL
    instalação usando arch linux:
        sudo pacman -S php-pgsql

    para iniciar o bd, use
        systemctl start postgresql.service

    para iniciar o código use:
        php -S localhost:3030 -c ./php.d/

