programa depende da extensão PostgresSQL
    instalação usando arch linux:
        sudo pacman -S php-pgsql

    para iniciar o bd, use
        systemctl start postgresql.service

    para iniciar o código use:
        php -S localhost:3030 -c ./php.d/

    o codigo espera que exista:
        um banco de dados chamado: "bancodedados"
        um usuário com todas as permissões: "root"
        a senha do usuário anterior deve ser: "1234"
        o host deve ser do tipo localhost
