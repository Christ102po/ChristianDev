-- Run in phpMyAdmin for an existing portfolio installation.
CREATE TABLE IF NOT EXISTS portfolio_settings (
 setting_key VARCHAR(100) PRIMARY KEY,
 setting_value VARCHAR(500) NOT NULL DEFAULT ''
);
