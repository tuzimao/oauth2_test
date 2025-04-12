CREATE TABLE oauth_clients (
  client_id TEXT,
  client_secret TEXT,
  redirect_uri TEXT
);

CREATE TABLE oauth_access_tokens (
  access_token TEXT,
  client_id TEXT,
  user_id TEXT,
  expires TEXT,
  scope TEXT
);

CREATE TABLE oauth_authorization_codes (
  authorization_code TEXT,
  client_id TEXT,
  user_id TEXT,
  redirect_uri TEXT,
  expires TEXT,
  scope TEXT,
  id_token TEXT  -- ✅ 为兼容 OpenID Connect 新增字段
);

CREATE TABLE oauth_users (
  username TEXT,
  password TEXT,
  first_name TEXT,
  last_name TEXT
);

CREATE TABLE oauth_refresh_tokens (
  refresh_token TEXT,
  client_id TEXT,
  user_id TEXT,
  expires TEXT,
  scope TEXT
);

CREATE TABLE oauth_scopes (
  scope TEXT,
  is_default INTEGER
);

CREATE TABLE oauth_public_keys (
  client_id TEXT,
  public_key TEXT,
  private_key TEXT,
  encryption_algorithm TEXT
);

CREATE TABLE oauth_jwt (
  client_id TEXT,
  subject TEXT,
  public_key TEXT
);
