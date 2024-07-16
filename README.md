# Demonstração de integração com Asaas

## Requerimentos de instalação

* PHP 8.2.12
* Composer 2>= .4.2
* MySQL >= 8.0
* [Asaas Access Token (v3)](https://docs.asaas.com/docs/autenticacao)
* Credenciais de SMTP

Para Desenvolvimento
* Node.js >= 20.14
* Npm >= 10.7.0


## Instalação

1) Crie um banco de dados no MySQL com o seguinte comando

```mysql
CREATE DATABASE demo_asaas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

2) Criar um arquivo `.env` a partir do `.env.example` e preenchê-lo com as credenciais do MySQL, Asaas e SMTP.

```shell
cp .env.example .env
```

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=demo_asaas
DB_USERNAME=*****
DB_PASSWORD=****

ASAAS_HOST="https://sandbox.asaas.com/api"
ASAAS_TOKEN=""

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="app@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

3) Instale as dependências do composer

```shell 
composer install
```

4) Crie uma chave de encrpitação e uma pasta para armazenamento

```shell
php artisan key:generate
php artisan storage:link
```

5) Crie as tabelas do banco de dados e uma carga inicial de dados

```shell
php artisan migrate
php artisan db:seed
```

6) Crie um servidor web com o seguinte comando

```shell
php artisan serve
```

## Acesso a aplicação

Após a instalação, a aplicação estará disponível no seguinte link:

[http://127.0.0.1:8000](http://127.0.0.1:8000)

Credenciais de desenvolvimento

* E-mail: admin@example.com
* Senha: admin@123

## Desenvolvimento

Para compilar o build de assets (css, js), executar o comando:

```shell
npm run build
```

## Testes

Para executar a bateria de testes, executar o comando:

```shell
php artisan test
```

## Links

* [Referência de API Asaas](https://docs.asaas.com/reference/comece-por-aqui)

