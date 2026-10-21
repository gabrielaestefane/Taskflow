CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE projects (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    user_id INTEGER NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),

    CONSTRAINT chk_projects_status
        CHECK (status IN ('ativo', 'arquivado')),
    CONSTRAINT fk_projects_user
        FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE tasks (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    prioridade VARCHAR(10) NOT NULL DEFAULT 'media',
    data_inicio DATE,
    data_prazo DATE,
    project_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),

    CONSTRAINT chk_tasks_status
        CHECK (status IN ('pendente', 'em_andamento', 'concluido')),
    CONSTRAINT chk_tasks_prioridade
        CHECK (prioridade IN ('baixa', 'media', 'alta')),
    CONSTRAINT chk_tasks_datas
        CHECK (data_prazo IS NULL OR data_inicio IS NULL
               OR data_prazo >= data_inicio),
    CONSTRAINT fk_tasks_project
        FOREIGN KEY (project_id) REFERENCES projects(id),
    CONSTRAINT fk_tasks_user
        FOREIGN KEY (user_id) REFERENCES users(id)
);