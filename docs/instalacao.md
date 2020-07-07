# Instalando

Existem duas maneiras de instalar o pacote `bnw/laravel-plugin-core`:

## Em um projeto Laravel 

Para essa modalidade, os recursos ficarão disponiveis diretamente na instalação normal do Laravel.

```c
composer create-peoject laravel/laravel meu-projeto
cd meu-projeto
chmod -Rf 777 storage
chmod -Rf 777 bootstrap/cache
composer require bnw/laravel-plugin-core 
php artisan publish --tag="core-config"
php artisan publish --tag="core-assets"
php artisan publish --tag="core-theme"
```

Após isso, é possivel acessar o painel através da rota '/admin'.

## Em um plugin isolado

Para essa modalidade, os recursos ficarão disponiveis dentro de um plugin isolado sob o contexto de uma pacote do composer.
O mecanismo utiliza o Laravel como dependencia e possibilita o desenvolvimento modular.

É como se o desenvolvedor estivesse dentro do Laravel, mas na verdade está em um pacote totalmente separado.

Para mais informações sobre como criar um plugin, acesse [Criando um plugin isolado](plugin.md).

## Mais informações

[Criando um plugin isolado](plugin.md)

[Voltar para o início](../readme.md)