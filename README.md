# CODEIGNITER 3 - CUSTOM MIGRATIONS

## migrations customizadas para o codeigniter

## Criação rápida:
1 - colocar os arquivos do conteúdo em suas respectivas pastas.\
2 - executar no terminal: php index.php migrations make .\
3 - alterar o arquivo gerado na pasta migrations .\

## Funções de linha de comando:
*É necessário estar dentro do diretório raiz do projeto para executar os comandos* .\
php index.php migrations make (cria 1 novo arquivo de migration editável) .\
php index.php migrations migrate (roda todos os arquivos de migration que ainda não foram rodados) .\
php index.php migrations rollback (desfaz a última migration executada através da função down) .\


## Como funciona:
A função UP é o que a migration rodará. .\
A função down é o que será rodado caso seja necessário fazer 1 rollback. .\
As funções de migrations devem respeitar o modelo de *database forge*: https://codeigniter.com/userguide3/database/forge.html .\
**Um exemplo de migration pode ser visto dentro da pasta migrations**

## Funções disponíveis para up e down:
create_table(string $table, array $data, string $primaryKey = 'id') .\
rename_table(string $table, string $new_name) .\
add_columns(string $table, array $data) .\
change_columns(string $table, array $data) .\
drop_table(string $table) .\
drop_columns(string $table, array $data) .\


