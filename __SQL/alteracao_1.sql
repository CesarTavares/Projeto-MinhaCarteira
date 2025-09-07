-- Alterando o tipo da coluna valor
ALTER TABLE minhacarteira.lancamentos_despesas MODIFY COLUMN valor DECIMAL(10,2) DEFAULT 0;

-- Alterando o tipo da coluna valor
ALTER TABLE minhacarteira.lancamentos_receitas MODIFY COLUMN valor DECIMAL(10,2) DEFAULT 0;

