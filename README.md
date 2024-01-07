# Projeto CodeIgniter 3 - Sistema de Cadastro de Clientes

## Requisitos

- PHP 7.4
- Servidor MySQL
- [CodeIgniter 3](https://codeigniter.com/)
## Instalação

1. Clone ou faça o download do projeto.
2. Execute o script SQL fornecido no arquivo `database.sql` no seu SGBD para criar as tabelas necessárias.
3. Configure o arquivo application/config/database.php com as informações do seu banco de dados.

## Rodar o Projeto

Para executar este projeto localmente, siga as instruções abaixo:

### Backend

1. Navegue até o diretório `back-end`:

   ```bash
   $ cd back-end
   ```

2. Inicie o servidor embutido do PHP:

    ```bash
   $ php -S localhost:8000
   ```
   
3. Certifique-se de que o PHP esteja instalado em seu ambiente de desenvolvimento.
O servidor será iniciado em http://localhost:8000.

### Frontend

1. Navegue até o diretório front-end.

2. Abra o arquivo index.html em seu navegador favorito.

3. O aplicativo será carregado e estará pronto para uso.

## Endpoints da API

A seguir estão os endpoints disponíveis nesta API para gerenciamento de clientes.

## Autenticação de Usuário

### 1. Login
- **Endpoint:** `/api/user/login`
- **Método:** `POST`
- **Descrição:** Autentica um usuário no sistema.
- **Parâmetros:**
    - `email` (string): E-mail do usuário.
    - `password` (string): Senha do usuário.

### 2. Registro de Usuário
- **Endpoint:** `/api/user/register`
- **Método:** `POST`
- **Descrição:** Registra um novo usuário no sistema.
- **Parâmetros:**
    - `email` (string): E-mail do novo usuário.
    - `password` (string): Senha do novo usuário.
    - `username` (string): Nome de usuário


## Gerenciamento de Clientes

### 4. Obter Todos os Clientes
- **Endpoint:** `/api/client/get`
- **Método:** `GET`
- **Descrição:** Retorna todos os clientes cadastrados no sistema.

### 5. Obter Cliente por ID
- **Endpoint:** `/api/client/get/{id}`
- **Método:** `GET`
- **Descrição:** Retorna informações detalhadas de um cliente específico.
- **Parâmetros:**
    - `id` (integer): ID do cliente desejado.

### 6. Registrar Novo Cliente
- **Endpoint:** `/api/client/register`
- **Método:** `POST`
- **Descrição:** Registra um novo cliente no sistema.
- **Parâmetros:**
    - `name` (string): Nome do cliente.
    - `email` (string): E-mail do cliente.
    - `phone` (string): Telefone do cliente.
    -  `cep` (string): CEP do cliente desejado.
    -  `street` (string): Rua do cliente desejado.
    - `complement` (string): Complemento do cliente desejado.
    - `neighborhood` (string): Bairro do cliente desejado.
    - `state` (string): Estado do cliente desejado.
    - `city` (string): Cidade do cliente desejado.
    - `id_client` (integer): ID do cliente desejado.
### 7. Atualizar Cliente
- **Endpoint:** `/api/client/update`
- **Método:** `PUT`
- **Descrição:** Atualiza as informações de um cliente existente.
- **Parâmetros:**
    - `name` (string): Nome do cliente.
    - `email` (string): E-mail do cliente.
    - `phone` (string): Telefone do cliente.
    -  `cep` (string): CEP do cliente desejado.
    -  `street` (string): Rua do cliente desejado.
    - `complement` (string): Complemento do cliente desejado.
    - `neighborhood` (string): Bairro do cliente desejado.
    - `state` (string): Estado do cliente desejado.
    - `city` (string): Cidade do cliente desejado.
    - `id_client` (integer): ID do cliente desejado.

### 8. Excluir Cliente
- **Endpoint:** `/api/client/delete`
- **Método:** `DELETE`
- **Descrição:** Exclui um cliente do sistema.
- **Parâmetros:**
    - `id` (integer): ID do cliente desejado.

## Estatísticas Gerais

### 9. Total de Clientes e Usuários
- **Endpoint:** `/api/all/count`
- **Método:** `GET`
- **Descrição:** Retorna o número total de clientes e usuários cadastrados no sistema.

#### Obs : Ao consultar a API lembre-se sempre de enviar o Token para autorização

## Exemplo de Login

Para acessar o sistema, utilize as seguintes credenciais:

- **E-mail:** admin@admin.com
- **Senha:** adminadmin

1. Faça uma requisição para o endpoint de login utilizando o método `POST`:

   ```http
   POST /api/user/login
     ```
   
2. No corpo da requisição, forneça as seguintes informações:
   
{
   "email": "admin@admin.com",
   "password": "adminadmin"
   }

## Tecnologias Utilizadas

Este projeto foi desenvolvido utilizando as seguintes tecnologias:

### CodeIgniter 3

- **Versão:** 3
- **Descrição:** CodeIgniter é um framework PHP leve e poderoso que oferece uma estrutura MVC (Model-View-Controller) para desenvolvimento web eficiente e organizado.

### PHP 7.4

- **Versão:** 7.4
- **Descrição:** PHP é uma linguagem de programação de script amplamente utilizada para desenvolvimento web. A versão 7.4 traz melhorias de desempenho e recursos aprimorados.

### MySQL

- **Versão:** 8
- **Descrição:** MySQL é um sistema de gerenciamento de banco de dados relacional amplamente utilizado para armazenar e recuperar dados.

### JWT (JSON Web Tokens) para Autenticação

- **Descrição:** JWT é um padrão aberto (RFC 7519) que define uma maneira compacta e autocontida de representar informações entre duas partes. No contexto deste projeto, é utilizado para autenticação de usuários.

- ### Bootstrap

- **Descrição:** Bootstrap é um framework de design front-end que facilita o desenvolvimento de interfaces web responsivas e atraentes.


### VIACEP (Consulta de CEP)

- **Descrição:** VIACEP é um serviço gratuito para consulta de CEP (Código de Endereçamento Postal) fornecido pelos Correios. Neste projeto, é utilizado para obter informações de endereço com base no CEP.

