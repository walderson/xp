USE `xp`;

-- Cria��o de campo para armazenar a Unidade Organizacional da avalia��o
ALTER TABLE `avaliacao` ADD `uo_id` INT(10) UNSIGNED NOT NULL AFTER `trimestre`;
ALTER TABLE `avaliacao` DROP INDEX `avaliacao_uk`, ADD UNIQUE `avaliacao_uk` (`uo_id`, `usuario_id`, `trimestre`) USING BTREE;
ALTER TABLE avaliacao ADD CONSTRAINT avaliacao_uo_fk FOREIGN KEY (uo_id) REFERENCES uo(id);

-- Cria��o de campo para armazenar o Gestor da Unidade Organizacional
ALTER TABLE `uo` ADD `gestor_id` INT(10) UNSIGNED NULL AFTER `uo_id`;
ALTER TABLE uo ADD CONSTRAINT uo_gestor_fk FOREIGN KEY (gestor_id) REFERENCES usuario(id);
