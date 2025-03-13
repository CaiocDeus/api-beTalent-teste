<h1>Teste Prático Back-end BeTalent</h1>

## Descrição
<p>O teste consiste em estruturar uma API RESTful conectada a um banco de dados e a duas APIs de terceiros.

Trata-se de um sistema gerenciador de pagamentos multi-gateway. Ao realizar uma compra, deve-se tentar realizar a cobrança junto aos gateways, seguindo a ordem de prioridade definida. Caso o primeiro gateway resulte em erro, deve-se fazer a tentativa no segundo gateway. Se algum gateway retornar sucesso, não deve ser informado erro no retorno da API.</p>

## Pré-requisitos
- MySQL como banco de dados
- Respostas devem ser em JSON
- ORM para gestão do banco (Eloquent, Lucid, Knex, Bookshelf etc.)
- Validação de dados (VineJS, etc.)
- README detalhado com:
  - Requisitos
  - Como instalar e rodar o projeto
  - Detalhamento de rotas
  - Outras informações relevantes
- Implementar TDD <strong>(FICOU PENDENTE)<strong>
- Docker compose com MySQL, aplicação e mock dos gateways

## Tecnologias Utilizadas
![Laravel](https://img.shields.io/badge/Laravel-v12-FF2D20?style=for-the-badge&logo=laravel&logoColor=FF4A4A)
![MySQL](https://img.shields.io/badge/MySQL-73618F?style=for-the-badge&logo=mysql&logoColor=white)
![Eloquent](https://img.shields.io/badge/eloquent-ff5733?style=for-the-badge&color=FE2D20)
![Docker](https://img.shields.io/badge/docker-blue?style=for-the-badge&logo=docker)

<hr>

## 🎲 Banco de Dados

<p>
O banco de dados está estruturado da seguinte maneira:</p>

* Os ids são uuids
- users: id, email, password, role
- gateways: id, name, is_active, priority
- clients: id, name, email
- products: id, name, amount
- transactions: id, client_id, gateway_id, external_id, status, amount, card_last_numbers
- transaction_products: id, transaction_id, product_id, quantity

<br>

## ✨ Funcionalidades
- Login
- CRUD de usuários
- CRUD de produtos
- CRUD de clientes
- Ativar/desativar um gateway
- Alterar a prioridade de um gateway
- Detalhe do cliente e todas suas compras
- Listar todas as compras
- Detalhes de uma compra
- Realizar uma compra informando o produto
- Realizar reembolso de uma compra junto ao gateway
- Usuários tem roles:
  - ADMIN - faz tudo
  - MANAGER - pode gerenciar produtos e usuários
  - FINANCE - pode gerenciar produtos e realizar reembolso
  - USER - pode o resto que não foi citado

<p><strong>Observação</strong>: Todas as rotas exigem <strong>autenticação</strong> menos Login e Realizar uma compra</p>

<br>

## ⚙️ Executando a aplicação

Para executar o projeto localmente, siga os passos abaixo:

### Instalação

1. Clone o repositório:

```
 git clone https://github.com/CaiocDeus/api-beTalent-teste.git
```

2. Vá para a pasta do projeto:

```
cd api-beTalent-teste
```

3. Instale as dependências do projeto:

```
composer install
```

4. Configurar o arquivo de ambiente (.env):

```
cp .env.example .env
```

5. Suba os containers do projeto com o comando: (É preciso ter o Docker instalado)

```
./vendor/bin/sail up -d
```

6. Rode o seguinte comando para criar as tabelas no BD:

```
./vendor/bin/sail artisan migrate
```

7. Rode o seguinte comando para preencher o BD com dados nas tabelas (Users, Clients, Products e Gateways):

```
./vendor/bin/sail artisan db:seed
```

8. Após isso, você poderá fazer as requisições seguindo os passos da seção logo abaixo.

```
A aplicação por padrão estará funcionando na porta 80.
Para realizar o primeiro login utilize o usuário admin:
  {
    "email": "admin@admin.com",
    "password": "admin"
  }
```

<br>

## 📑 Documentação da API

Para auxiliar nos testes da API:

- Vou disponibilizar esta [Collection](https://github.com/CaiocDeus/api-beTalent-teste/blob/master/api_beTalent_teste.json) para ser utilizado no Postman, no Insomnia ou em outras ferramentas

### Funcionalidades dos usuários em rotas públicas.

<details>
  <summary>Logar na rota /api/user/login</summary>

  <code>POST</code> <code>/api/user/login</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |

  | Parâmetros Body   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `email` | `string` | **Obrigatório** -> Email do usuário |
  | `password` | `string` | **Obrigatório** -> Senha do usuário |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "token": "1|KNupIm0xOoPe3rC94EeWi9HcMFKg4ByqmP3ZpP5Bb3c8ec1d"
    }
</details>

<hr>

### Funcionalidades dos usuários em rotas autenticadas.

<details>
  <summary>Obter informações dos usuários na rota /api/user</summary>

  <code>GET</code> <code>/api/user</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    [
      {
        "id": "01957d0c-5f58-71e6-bc28-cf871b8b2bdd",
        "email": "test@hotmail.com",
        "role": "admin",
        "created_at": "2024-07-15T23:49:44.000000Z",
        "updated_at": "2024-07-15T23:49:44.000000Z"
      },
      {
        "id": "01957d0c-6021-72d3-80fb-bb6cf14f5708",
        "email": "test@hotmail.com",
        "role": "manager",
        "created_at": "2024-07-15T23:49:44.000000Z",
        "updated_at": "2024-07-15T23:49:44.000000Z"
      }
    ]

</details>

<details>
  <summary>Obter informação de um usuário na rota /api/user/{id}</summary>

  <code>GET</code> <code>/api/user/{id}</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do usuário |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "id": "01957d0c-5f58-71e6-bc28-cf871b8b2bdd",
      "email": "test@hotmail.com",
      "role": "admin",
      "created_at": "2024-07-15T23:49:44.000000Z",
      "updated_at": "2024-07-15T23:49:44.000000Z"
    }
</details>

<details>
  <summary>Criar usuário na rota /api/user</summary>

  <code>POST</code> <code>/api/user</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetros Body   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `email` | `string` | **Obrigatório** -> Email do usuário |
  | `password` | `string` | **Obrigatório** -> Senha do usuário |
  | `role` | `string` | **Obrigatório** -> Cargo do usuário |

  #### Exemplo de retorno

  <p>Status: 201 Created</p>
    {
      "id": "01956348-f7d7-7335-9b8b-11e2efb95a12"
      "message": "Usuário criado"
    }
</details>

<details>
  <summary>Alterar um usuário na rota /api/user/{id}</summary>

  <code>PUT</code> <code>/api/user/{id}</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do usuário |

  | Parâmetros Body   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `email` | `string` | Email do usuário |
  | `password` | `string` | Senha do usuário |
  | `role` | `string` | Cargo do usuário |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "message": "Usuário atualizado"
    }
</details>

<details>
  <summary>Exclusão de um usuário na rota /api/user/{id}</summary>

  <code>DELETE</code> <code>/api/user/{id}</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do usuário |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "message": "Usuário deletado"
    }
</details>

<hr>

### Funcionalidades dos clientes.

<details>
  <summary>Obter informações dos clientes na rota /api/client</summary>

  <code>GET</code> <code>/api/client</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    [
      {
        "id": "01957d0c-5f58-71e6-bc28-cf871b8b2bdd",
        "name": "Heitor Edilson Beltrão",
        "email": "benites.monica@galhardo.com.br",
        "created_at": "2024-07-15T23:49:44.000000Z",
        "updated_at": "2024-07-15T23:49:44.000000Z"
      },
      {
        "id": "01957d0c-6021-72d3-80fb-bb6cf14f5708",
        "name": "Franciele Paz Sobrinho",
        "email": "stefany.carmona@mares.net.br",
        "created_at": "2024-07-15T23:49:44.000000Z",
        "updated_at": "2024-07-15T23:49:44.000000Z"
      }
    ]

</details>

<details>
  <summary>Obter informação de um cliente na rota /api/client/{id}</summary>

  <code>GET</code> <code>/api/client/{id}</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do cliente |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "id": "01957d0c-5f58-71e6-bc28-cf871b8b2bdd",
      "name": "Heitor Edilson Beltrão",
      "email": "benites.monica@galhardo.com.br",
      "created_at": "2024-07-15T23:49:44.000000Z",
      "updated_at": "2024-07-15T23:49:44.000000Z"
    }
</details>

<details>
  <summary>Obter informação de um cliente e suas transações na rota /api/client/{id}/transactions</summary>

  <code>GET</code> <code>/api/client/{id}/transactions</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do cliente |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "id": "01957de3-9f35-70fe-97a2-17a7010a003e",
      "name": "Sra. Franciele Sanches Neto",
      "email": "furtado.vicente@yahoo.com",
      "created_at": "2025-03-10T02:30:24.000000Z",
      "updated_at": "2025-03-10T02:30:24.000000Z",
      "transactions": [
        {
          "id": "019581f2-d24a-70ec-8a7a-fe215afbb2c8",
          "client_id": "01957de3-9f35-70fe-97a2-17a7010a003e",
          "gateway_id": "01957de3-9f7f-703e-a046-547afe427604",
          "external_id": "546a542a-d51f-4120-8f50-20f6ea20e97b",
          "status": "paid",
          "amount": "110.48",
          "card_last_numbers": "2771",
          "created_at": "2025-03-10T21:25:29.000000Z",
          "updated_at": "2025-03-10T21:25:29.000000Z"
        }
      ]
    }
</details>

<details>
  <summary>Criar cliente na rota /api/client</summary>

  <code>POST</code> <code>/api/client</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetros Body   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `name` | `string` | **Obrigatório** -> Nome do cliente |
  | `email` | `string` | **Obrigatório** -> Email do cliente |

  #### Exemplo de retorno

  <p>Status: 201 Created</p>
    {
      "id": "01956348-f7d7-7335-9b8b-11e2efb95a12"
      "message": "Cliente criado"
    }
</details>

<details>
  <summary>Alterar um cliente na rota /api/client/{id}</summary>

  <code>PUT</code> <code>/api/client/{id}</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do cliente |

  | Parâmetros Body   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `name` | `string` | Nome do cliente |
  | `email` | `string` | Email do cliente |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "message": "Cliente atualizado"
    }
</details>

<details>
  <summary>Exclusão de um cliente na rota /api/client</summary>

  <code>DELETE</code> <code>/api/client</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do cliente |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "message": "Cliente deletado"
    }
</details>

<hr>

### Funcionalidades dos produtos.

<details>
  <summary>Obter informações dos produtos na rota /api/product</summary>

  <code>GET</code> <code>/api/product</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    [
      {
        "id": "01957de3-9f65-7166-b54e-7f69bc1b3e5b",
        "name": "Mochila",
        "amount": "50.50",
        "created_at": "2025-03-10T02:30:24.000000Z",
        "updated_at": "2025-03-10T02:30:24.000000Z"
      },
      {
        "id": "01957de3-9f6f-7017-ad1d-f314404501be",
        "name": "Caderno",
        "amount": "29.99",
        "created_at": "2025-03-10T02:30:24.000000Z",
        "updated_at": "2025-03-10T02:30:24.000000Z"
      },
    ]

</details>

<details>
  <summary>Obter informação de um produto na rota /api/product/{id}</summary>

  <code>GET</code> <code>/api/product/{id}</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do produto |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "id": "01957de3-9f65-7166-b54e-7f69bc1b3e5b",
      "name": "Mochila",
      "amount": "50.50",
      "created_at": "2025-03-10T02:30:24.000000Z",
      "updated_at": "2025-03-10T02:30:24.000000Z"
    }
</details>

<details>
  <summary>Criar produto na rota /api/produto</summary>

  <code>POST</code> <code>/api/produto</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetros Body   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `name` | `string` | **Obrigatório** -> Nome do produto |
  | `amount` | `number` | **Obrigatório** -> Valor do produto |

  #### Exemplo de retorno

  <p>Status: 201 Created</p>
    {
      "id": "01956348-f7d7-7335-9b8b-11e2efb95a12"
      "message": "Produto criado"
    }
</details>

<details>
  <summary>Alterar um produto na rota /api/product/{id}</summary>

  <code>PUT</code> <code>/api/product/{id}</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do Produto |

  | Parâmetros Body   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `name` | `string` | Nome do Produto |
  | `amount` | `number` | Valor do Produto |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "message": "Produto atualizado"
    }
</details>

<details>
  <summary>Exclusão de um produto na rota /api/product</summary>

  <code>DELETE</code> <code>/api/product</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do produto |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "message": "Produto deletado"
    }
</details>

<hr>

### Funcionalidades dos gateways.

<details>
  <summary>Obter informações dos gateways na rota /api/gateway</summary>

  <code>GET</code> <code>/api/gateway</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    [
      {
        "id": "01957de3-9f7f-703e-a046-547afe427604",
        "name": "Gateway 1",
        "is_active": 1,
        "priority": 1,
        "created_at": "2025-03-10T02:30:24.000000Z",
        "updated_at": "2025-03-10T02:30:24.000000Z"
      },
      {
        "id": "01957de3-9f84-7165-bb23-de9dce697121",
        "name": "Gateway 2",
        "is_active": 1,
        "priority": 2,
        "created_at": "2025-03-10T02:30:24.000000Z",
        "updated_at": "2025-03-10T02:30:24.000000Z"
      }
    ]
</details>

<details>
  <summary>Alterar o status do gateway /api/gateway/{id}/change-status</summary>

  <code>PUT</code> <code>/api/gateway/{id}/change-status</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do Gateway |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "message": "Gateway {$status}"
    }
</details>

<details>
  <summary>Alterar a prioridade do gateway /api/gateway/{id}/change-priority</summary>

  <code>PUT</code> <code>/api/gateway/{id}/change-priority</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID do Gateway |

  | Parâmetros Body   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `priority` | `number` | Prioridade do Gateway |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "message": "Prioridade do gateway alterada"
    }
</details>

<hr>

### Funcionalidades das transações em rotas públicas.

<details>
  <summary>Realizar uma transação na rota /api/transaction</summary>

  <code>POST</code> <code>/api/transaction</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Content-Type` | `application/json` | **Obrigatório** -> Tipo de mídia dos dados que estão sendo enviados na requisição |

  | Parâmetros Body   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `client_id` | `string` | **Obrigatório** -> Id do Cliente |
  | `cardNumber` | `number` | **Obrigatório** -> Número do cartão |
  | `cvv` | `number` | **Obrigatório** -> CVV do cartão |
  | `products` | `array` | **Obrigatório** -> Produtos da Transação |

  | Parâmetros Products   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** -> Id do Produto |
  | `quantity` | `number` | **Obrigatório** -> Quantidade do Produto |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "message": "Transação efetuada"
    }
</details>

<hr>

### Funcionalidades das transações em rotas autenticadas.

<details>
  <summary>Obter informações das transações na rota /api/transaction</summary>

  <code>GET</code> <code>/api/transaction</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    [
      {
        "id": "019581f2-d24a-70ec-8a7a-fe215afbb2c8",
        "client_id": "01957de3-9f35-70fe-97a2-17a7010a003e",
        "gateway_id": "01957de3-9f7f-703e-a046-547afe427604",
        "external_id": "546a542a-d51f-4120-8f50-20f6ea20e97b",
        "status": "paid",
        "amount": "110.48",
        "card_last_numbers": "2771",
        "created_at": "2025-03-10T21:25:29.000000Z",
        "updated_at": "2025-03-10T21:25:29.000000Z"
      },
      {
        "id": "a7e46bdc-ff82-4c16-a4d3-15db5c802b26",
        "client_id": "01957de3-9f3b-7023-b51c-0db629e5c257",
        "gateway_id": "01957de3-9f84-7165-bb23-de9dce697121",
        "external_id": "75994ccd-3b3c-4948-a0b7-8f244e4f9946",
        "status": "paid",
        "amount": "50.99",
        "card_last_numbers": "3672",
        "created_at": "2025-03-10T22:25:29.000000Z",
        "updated_at": "2025-03-10T22:25:29.000000Z"
      }
    ]

</details>

<details>
  <summary>Obter informação de uma transação e seus produtos na rota /api/transaction/{id}</summary>

  <code>GET</code> <code>/api/transaction/{id}</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID da transação |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "id": "019581f2-d24a-70ec-8a7a-fe215afbb2c8",
      "client_id": "01957de3-9f35-70fe-97a2-17a7010a003e",
      "gateway_id": "01957de3-9f7f-703e-a046-547afe427604",
      "external_id": "546a542a-d51f-4120-8f50-20f6ea20e97b",
      "status": "paid",
      "amount": "110.48",
      "card_last_numbers": "2771",
      "created_at": "2025-03-10T21:25:29.000000Z",
      "updated_at": "2025-03-10T21:25:29.000000Z",
      "products": [
        {
          "id": "01957de3-9f65-7166-b54e-7f69bc1b3e5b",
          "name": "Mochila",
          "amount": "50.50",
          "created_at": "2025-03-10T02:30:24.000000Z",
          "updated_at": "2025-03-10T02:30:24.000000Z",
          "pivot": {
            "transaction_id": "019581f2-d24a-70ec-8a7a-fe215afbb2c8",
            "product_id": "01957de3-9f65-7166-b54e-7f69bc1b3e5b",
            "quantity": 1
          }
        },
        {
          "id": "01957de3-9f6f-7017-ad1d-f314404501be",
          "name": "Caderno",
          "amount": "29.99",
          "created_at": "2025-03-10T02:30:24.000000Z",
          "updated_at": "2025-03-10T02:30:24.000000Z",
          "pivot": {
            "transaction_id": "019581f2-d24a-70ec-8a7a-fe215afbb2c8",
            "product_id": "01957de3-9f6f-7017-ad1d-f314404501be",
            "quantity": 2
          }
        }
      ]
    }
</details>

<details>
  <summary>Realizar o reembolso de uma transação na rota /api/transaction/{id}/refund</summary>

  <code>PUT</code> <code>/api/transaction/{id}/refund</code>

  | Headers   | Tipo       | Descrição                           |
  | :---------- | :--------- | :---------------------------------- |
  | `Authorization` | `Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` | **Obrigatório** -> Seu token gerado no login |

  | Parâmetro via Request   | Tipo       | Descrição               |
  | :---------- | :--------- | :---------------------------------- |
  | `id` | `string` | **Obrigatório** ->  ID da Transação |

  #### Exemplo de retorno

  <p>Status: 200 OK</p>
    {
      "message": "Transação reembolsada"
    }
</details>

<hr>

## Autor

Caio Cesar de Deus

<hr>

## 📫 Contato
[![Linkedin](https://img.shields.io/badge/linkedin-%230077B5.svg?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/caio-deus/)
[![Email](https://img.shields.io/badge/Microsoft_Outlook-0078D4?style=for-the-badge&logo=microsoft-outlook&logoColor=white)](mailto:caioc.deus@outlook.com)