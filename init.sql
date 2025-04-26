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

CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT UNIQUE,
  password TEXT -- 保存加密后的密码
);

CREATE TABLE IF NOT EXISTS oauth_clients (
  client_id TEXT PRIMARY KEY,
  client_secret TEXT NOT NULL,
  redirect_uri TEXT NOT NULL,
  grant_types TEXT DEFAULT 'authorization_code',
  scope TEXT DEFAULT NULL,
  user_id TEXT DEFAULT NULL
);

