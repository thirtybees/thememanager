CREATE TABLE IF NOT EXISTS `PREFIX_thememanager_template` (
  `entity_type`                 VARCHAR(32)         NOT NULL,
  `id_entity`                   INT(11) UNSIGNED    NOT NULL,
  `template`                    VARCHAR(64)         NOT NULL,
  PRIMARY KEY (`entity_type`, `id_entity`)
)
  ENGINE = ENGINE_TYPE
  DEFAULT CHARSET = CHARSET_TYPE
  COLLATE utf8mb4_unicode_ci;
