# API Guard Seguros — Documentação

API REST para o projeto Guard Seguros, desenvolvida em **Laravel 11** com **Laravel Sanctum** para autenticação.

---

## Índice

1. [Visão geral](#visão-geral)
2. [Requisitos e instalação](#requisitos-e-instalação)
3. [Autenticação](#autenticação)
4. [Formato de resposta](#formato-de-resposta)
5. [Endpoints públicos](#endpoints-públicos)
6. [Endpoints protegidos (autenticados)](#endpoints-protegidos-autenticados)
7. [Endpoints Master (admin)](#endpoints-master-admin)
8. [Parâmetros de consulta (GET)](#parâmetros-de-consulta-get)
9. [Modelos e campos](#modelos-e-campos)

---

## Visão geral

- **Base URL da API:** `/api` (prefixo padrão Laravel)
- **Autenticação:** Bearer Token (Laravel Sanctum)
- **Formato:** JSON

---

## Requisitos e instalação

- **PHP:** ^8.5  
- **Laravel:** ^11.9  
- **Laravel Sanctum:** ^4.0  

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Configure no `.env` o banco de dados, `APP_URL` e, se necessário, opções de e-mail (para recuperação de senha e contato).

---

## Autenticação

### Login

Gera um token de acesso para uso nos endpoints protegidos.

| Método | Endpoint        | Autenticação |
|--------|-----------------|--------------|
| POST   | `/api/login`    | Não          |

**Body (JSON):**

```json
{
  "email": "usuario@email.com",
  "password": "senha"
}
```

**Resposta de sucesso (200):**

```json
{
  "title": "Sucesso!",
  "message": "Logado com sucesso",
  "data": {
    "token": "1|..."
  }
}
```

Use o valor de `data.token` no header em requisições autenticadas:

```
Authorization: Bearer 1|...
```

---

### Esqueci minha senha

Envia instruções de redefinição para o e-mail informado.

| Método | Endpoint                      | Autenticação |
|--------|-------------------------------|--------------|
| POST   | `/api/login/forgot-password`  | Não          |

**Body (JSON):**

```json
{
  "email": "usuario@email.com"
}
```

**Validação:** `email` obrigatório, deve existir na tabela `users`.

---

### Redefinir senha

Altera a senha usando o token recebido por e-mail.

| Método | Endpoint           | Autenticação |
|--------|--------------------|--------------|
| POST   | `/api/login/reset` | Não          |

**Body (JSON):**

```json
{
  "token": "token_recebido_por_email",
  "password": "nova_senha"
}
```

**Validação:** `token` deve existir em `users.remember_token`; `password` obrigatório.

---

### Logout

Invalida o token do usuário autenticado.

| Método | Endpoint      | Autenticação   |
|--------|---------------|----------------|
| POST   | `/api/logout` | Bearer Token   |

---

### Atualizar senha (usuário logado)

| Método | Endpoint            | Autenticação   |
|--------|---------------------|----------------|
| PUT    | `/api/user/password`| Bearer Token   |

**Body (JSON):**

```json
{
  "password": "nova_senha"
}
```

---

### Dados do usuário logado

| Método | Endpoint   | Autenticação   |
|--------|------------|----------------|
| GET    | `/api/me`  | Bearer Token   |
| GET    | `/api/user`| Bearer Token   |

Retorna os dados do usuário autenticado.

---

## Formato de resposta

Respostas padrão da API seguem a estrutura:

```json
{
  "title": "Título da resposta",
  "message": "Mensagem descritiva",
  "data": { }
}
```

- **Sucesso:** status `200` ou `201`  
- **Criado:** `201`  
- **Sem conteúdo (ex.: delete):** `204`  
- **Não autorizado:** `401`  
- **Proibido:** `403`  
- **Não aceitável (validação):** `406`  
- **Erro de servidor:** `500`  

Para listagens com paginação, envie o header:

```
Paginated: true
```

e use os parâmetros de consulta descritos em [Parâmetros de consulta (GET)](#parâmetros-de-consulta-get).

---

## Endpoints públicos

### Contato (Fale conosco)

Envio de mensagem pelo formulário “Fale conosco”. Não requer autenticação.

| Método | Endpoint           | Descrição        |
|--------|--------------------|------------------|
| POST   | `/api/contact-us`  | Criar contato    |

**Body (JSON):**

| Campo               | Tipo   | Obrigatório | Regras                          |
|---------------------|--------|-------------|----------------------------------|
| name                | string | Sim         | min:3, max:90                    |
| email               | string | Sim         | e-mail válido                    |
| phone               | string | Sim         | min:3, max:120                   |
| insurance_type      | string | Não         | min:3, max:90                    |
| other_insurance_type| string | Não         | min:3, max:90                    |

**Resposta (200):**

```json
{
  "message": "Contato enviado com sucesso!"
}
```

---

### Banner

Listagem de banners (ex.: carrossel do site).

| Método | Endpoint       | Descrição   |
|--------|----------------|-------------|
| GET    | `/api/banner`  | Listar      |

---

### Comentários / Depoimentos

| Método | Endpoint        | Descrição   |
|--------|-----------------|-------------|
| GET    | `/api/comment`  | Listar      |

---

### Contato (informações da empresa)

Dados de contato exibidos no site (telefone, e-mail, endereço, horário).

| Método | Endpoint       | Descrição   |
|--------|----------------|-------------|
| GET    | `/api/contact` | Listar      |

---

### Seguradoras

| Método | Endpoint                 | Descrição   |
|--------|--------------------------|-------------|
| GET    | `/api/insurance-companies` | Listar    |

---

### Registro (cadastro de usuário)

Cadastro de novo usuário no sistema.

| Método | Endpoint        | Descrição  |
|--------|-----------------|------------|
| POST   | `/api/register` | Cadastrar  |

**Body (JSON):**

| Campo                | Tipo   | Obrigatório | Regras              |
|----------------------|--------|-------------|----------------------|
| name                 | string | Sim         | min:3, max:90        |
| email                | string | Sim         | e-mail válido         |
| tax_id               | string | Sim         | numérico, 11 dígitos (CPF) |
| password             | string | Sim         | confirmed             |
| password_confirmation| string | Sim         | —                    |
| company              | string | Sim         | min:3, max:90        |
| segment              | string | Sim         | min:3, max:90        |

---

## Endpoints protegidos (autenticados)

Todos os endpoints abaixo exigem header:

```
Authorization: Bearer {token}
```

- `GET /api/user` — Dados do usuário  
- `GET /api/me` — Dados do usuário  
- `POST /api/logout` — Logout  
- `PUT /api/user/password` — Atualizar senha  

Os demais recursos protegidos estão no grupo **Master** (apenas usuários com perfil master).

---

## Endpoints Master (admin)

Exigem autenticação **e** o middleware `MasterMiddleware` (usuário master). Use o mesmo header Bearer.

### Banner

| Método | Endpoint            | Descrição     |
|--------|---------------------|---------------|
| POST   | `/api/banner`       | Criar         |
| GET    | `/api/banner/{id}`  | Ver um        |
| PUT    | `/api/banner/{id}`  | Atualizar     |
| DELETE | `/api/banner/{id}`  | Excluir       |

**Campos (Banner):** `title`, `subtitle`, `image_desktop`, `image_mobile`, `badge`, `button_text`, `button_link`, `is_active`.  
Upload: `image_desktop` e/ou `image_mobile` via multipart/form-data.

---

### Comentários

| Método | Endpoint             | Descrição     |
|--------|----------------------|---------------|
| POST   | `/api/comment`       | Criar         |
| GET    | `/api/comment/{id}`  | Ver um        |
| PUT    | `/api/comment/{id}`  | Atualizar     |
| DELETE | `/api/comment/{id}`  | Excluir       |

**Campos:** `name`, `company`, `role`, `comment`, `rating`, `is_active`.

---

### Contato (informações da empresa)

| Método | Endpoint             | Descrição     |
|--------|----------------------|---------------|
| POST   | `/api/contact`       | Criar         |
| GET    | `/api/contact/{id}`  | Ver um        |
| PUT    | `/api/contact/{id}`  | Atualizar     |
| DELETE | `/api/contact/{id}`  | Excluir       |

**Campos:** `whatsapp`, `email`, `address`, `city`, `state`, `zip_code`, `business_hours_start`, `business_hours_end`.

---

### Fale conosco (mensagens recebidas)

| Método | Endpoint                    | Descrição     |
|--------|-----------------------------|---------------|
| GET    | `/api/contact-us`           | Listar        |
| PUT    | `/api/contact-us/{id}`      | Atualizar     |

Útil para marcar como lido ou gerenciar status. Campos do modelo: `name`, `email`, `insurance_type`, `other_insurance_type`, `phone`, `message`, `read`.

---

### Seguradoras

| Método | Endpoint                              | Descrição     |
|--------|---------------------------------------|---------------|
| POST   | `/api/insurance-companies`            | Criar         |
| GET    | `/api/insurance-companies/{id}`       | Ver uma       |
| PUT    | `/api/insurance-companies/{id}`       | Atualizar     |
| DELETE | `/api/insurance-companies/{id}`       | Excluir       |

**Campos:** `name`, `image`, `description`, `type`, `is_active`.  
Upload de imagem: campo `image` via multipart/form-data.

---

## Parâmetros de consulta (GET)

Para listagens que usam o trait de consulta do projeto:

| Parâmetro   | Descrição              | Exemplo   |
|------------|------------------------|-----------|
| `limit`    | Itens por página       | `10` (máx. 100) |
| `order_by` | Campo de ordenação     | `id`, `name`    |
| `order`    | Direção                | `asc`, `desc`   |
| `page`     | Página (com paginação) | `1`             |

Para ativar resposta paginada, envie o header:

```
Paginated: true
```

Outros query params podem ser usados como filtros conforme implementação de cada controller/model.

---

## Modelos e campos

Resumo dos principais recursos e campos (fillable):

| Recurso            | Campos principais                                                                 |
|--------------------|------------------------------------------------------------------------------------|
| **Banner**         | title, subtitle, image_desktop, image_mobile, badge, button_text, button_link, is_active |
| **Comment**        | name, company, role, comment, rating, is_active                                    |
| **Contact**        | whatsapp, email, address, city, state, zip_code, business_hours_start, business_hours_end |
| **ContactUs**      | name, email, insurance_type, other_insurance_type, phone, message, read            |
| **InsuranceCompanies** | name, image, description, type, is_active                                    |
| **Register**       | name, email, tax_id, password, company, segment (conforme RegisterRequest)         |
| **User**           | Gerenciado por Laravel (auth); uso de remember_token para reset de senha          |

---

## Observações

- Em produção, utilize **HTTPS** e mantenha o `.env` seguro (nunca versionar chaves e senhas).
- Tokens Sanctum devem ser enviados apenas em canal seguro (HTTPS).
- O perfil **Master** é verificado pelo `MasterMiddleware`; a definição de quem é master depende da sua lógica (ex.: campo na tabela `users` ou tabela de perfis).

Se precisar incluir um recurso **Sobre nós** (About Us) no futuro, basta registrar as rotas em `routes/api.php` dentro do grupo desejado (público ou master) e referenciar o `AboutUsController` e o model `AboutUs`.
