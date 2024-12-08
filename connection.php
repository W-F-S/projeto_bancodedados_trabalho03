<?php
//https://www.php.net/manual/en/pgsql.examples-basic.php

// Execute the SQL query
$result = '';

$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234")
    or die('Could not connect: ' . pg_last_error());

echo 'Connected successfully';

$sql = <<<SQL
--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--



--
-- TOC entry 3482 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 216 (class 1259 OID 17612)
-- Name: cidade; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cidade (
    codigo_cid integer NOT NULL,
    nome_cid character varying(100) NOT NULL,
    preco_unit_peso real,
    preco_unit_valor real,
    fk_uf character varying(2),
    CONSTRAINT cidade_preco_unit_peso_check CHECK ((preco_unit_peso >= (0)::double precision)),
    CONSTRAINT cidade_preco_unit_valor_check CHECK ((preco_unit_valor >= (0)::double precision))
);


--
-- TOC entry 215 (class 1259 OID 17611)
-- Name: cidade_codigo_cid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cidade_codigo_cid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;




CREATE TABLE public.frete (
    quem_paga character varying(50) NOT NULL,
    peso_ou_valor character varying(10) NOT NULL,
    peso real NOT NULL,
    valor_produto real NOT NULL,
    pedagio real,
    icms real NOT NULL,
    data_frete date NOT NULL,
    fk_cliente_destinatario integer,
    fk_cod_cidade_origem integer,
    fk_cod_cidade_destino integer,
    fk_funcionario integer,
    fk_cliente_remetente integer,
    valor_frete real,
    id_frete integer NOT NULL,
    CONSTRAINT frete_icms_check CHECK ((icms >= (0)::double precision)),
    CONSTRAINT frete_pedagio_check CHECK ((pedagio >= (0)::double precision)),
    CONSTRAINT frete_peso_check CHECK ((peso >= (0)::double precision)),
    CONSTRAINT frete_valor_check CHECK ((valor_produto >= (0)::double precision))
);


--
-- TOC entry 3483 (class 0 OID 0)
-- Dependencies: 215
-- Name: cidade_codigo_cid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cidade_codigo_cid_seq OWNED BY public.cidade.codigo_cid;


--
-- TOC entry 218 (class 1259 OID 17619)
-- Name: cliente; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cliente (
    cod_cli integer NOT NULL,
    data_insc date NOT NULL,
    endereco character varying(200) NOT NULL,
    telefone character varying(15) NOT NULL,
    tipo character varying(10)
);


--
-- TOC entry 217 (class 1259 OID 17618)
-- Name: cliente_cod_cli_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cliente_cod_cli_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3484 (class 0 OID 0)
-- Dependencies: 217
-- Name: cliente_cod_cli_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cliente_cod_cli_seq OWNED BY public.cliente.cod_cli;


--
-- TOC entry 219 (class 1259 OID 17623)
-- Name: conhecimento_transporte; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.conhecimento_transporte (
    id_conhecimento character varying,
    fk_frete integer
);


--
-- TOC entry 220 (class 1259 OID 17628)
-- Name: empresa; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.empresa (
    codigo_cli integer,
    razao_social character varying,
    inscricao_estadual character varying,
    cnpj character varying,
    nome_representante character varying,
    telefone_representante bigint
);


--
-- TOC entry 221 (class 1259 OID 17633)
-- Name: estado; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.estado (
    uf character varying(2) NOT NULL,
    nome_est character varying(50) NOT NULL,
    icms_local real NOT NULL,
    icms_outro_uf real NOT NULL,
    CONSTRAINT estado_icms_local_check CHECK (((icms_local >= (0)::double precision) AND (icms_local <= (100)::double precision))),
    CONSTRAINT estado_icms_outro_uf_check CHECK (((icms_outro_uf >= (0)::double precision) AND (icms_outro_uf <= (100)::double precision)))
);


--
-- TOC entry 222 (class 1259 OID 17638)
-- Name: frete; Type: TABLE; Schema: public; Owner: -
--




--
-- TOC entry 3485 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN frete.quem_paga; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.frete.quem_paga IS '-- Indica quem paga o frete, remetente ou destinatário';


--
-- TOC entry 3486 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN frete.peso_ou_valor; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.frete.peso_ou_valor IS 'Valor do frete baseado no peso ou valor';


--
-- TOC entry 3487 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN frete.id_frete; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.frete.id_frete IS 'Numero conhecimento_ poderia ser uma nova tabela';


--
-- TOC entry 223 (class 1259 OID 17645)
-- Name: frete_conhecimento_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.frete_conhecimento_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 3488 (class 0 OID 0)
-- Dependencies: 223
-- Name: frete_conhecimento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.frete_conhecimento_seq OWNED BY public.frete.id_frete;


--
-- TOC entry 224 (class 1259 OID 17646)
-- Name: funcionario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.funcionario (
    num_reg integer NOT NULL,
    nome_func character varying(100) NOT NULL
);


--
-- TOC entry 225 (class 1259 OID 17649)
-- Name: pessoa_fisica; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pessoa_fisica (
    nome_cli character varying(100) NOT NULL,
    cpf character varying(11) NOT NULL,
    codigo_cli integer
);


--
-- TOC entry 226 (class 1259 OID 17652)
-- Name: pessoa_juridica; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pessoa_juridica (
    razao_social character varying(150) NOT NULL,
    insc_estadual character varying(20) NOT NULL,
    cnpj character varying(14) NOT NULL,
    codigo_cli integer,
    id_representante character varying
);


--
-- TOC entry 3285 (class 2604 OID 17615)
-- Name: cidade codigo_cid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cidade ALTER COLUMN codigo_cid SET DEFAULT nextval('public.cidade_codigo_cid_seq'::regclass);


--
-- TOC entry 3286 (class 2604 OID 17622)
-- Name: cliente cod_cli; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cliente ALTER COLUMN cod_cli SET DEFAULT nextval('public.cliente_cod_cli_seq'::regclass);


--
-- TOC entry 3287 (class 2604 OID 17657)
-- Name: frete id_frete; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frete ALTER COLUMN id_frete SET DEFAULT nextval('public.frete_conhecimento_seq'::regclass);


--
-- TOC entry 3489 (class 0 OID 0)
-- Dependencies: 215
-- Name: cidade_codigo_cid_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cidade_codigo_cid_seq', 2, true);


--
-- TOC entry 3490 (class 0 OID 0)
-- Dependencies: 217
-- Name: cliente_cod_cli_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cliente_cod_cli_seq', 1, false);


--
-- TOC entry 3491 (class 0 OID 0)
-- Dependencies: 223
-- Name: frete_conhecimento_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.frete_conhecimento_seq', 29, true);


--
-- TOC entry 3297 (class 2606 OID 17659)
-- Name: cidade cidade_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cidade
    ADD CONSTRAINT cidade_pk PRIMARY KEY (codigo_cid);


--
-- TOC entry 3299 (class 2606 OID 17661)
-- Name: cliente cliente_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cliente
    ADD CONSTRAINT cliente_pkey PRIMARY KEY (cod_cli);


--
-- TOC entry 3303 (class 2606 OID 17663)
-- Name: frete frete_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frete
    ADD CONSTRAINT frete_pk PRIMARY KEY (id_frete);


--
-- TOC entry 3305 (class 2606 OID 17665)
-- Name: funcionario funcionario_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.funcionario
    ADD CONSTRAINT funcionario_pkey PRIMARY KEY (num_reg);


--
-- TOC entry 3307 (class 2606 OID 17667)
-- Name: pessoa_fisica pessoa_fisica_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pessoa_fisica
    ADD CONSTRAINT pessoa_fisica_pkey PRIMARY KEY (cpf);


--
-- TOC entry 3309 (class 2606 OID 17669)
-- Name: pessoa_juridica pessoa_juridica_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pessoa_juridica
    ADD CONSTRAINT pessoa_juridica_pkey PRIMARY KEY (cnpj);


--
-- TOC entry 3301 (class 2606 OID 17671)
-- Name: estado uf; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.estado
    ADD CONSTRAINT uf PRIMARY KEY (uf);


--
-- TOC entry 3310 (class 2606 OID 17672)
-- Name: cidade cidade_estado_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cidade
    ADD CONSTRAINT cidade_estado_fk FOREIGN KEY (fk_uf) REFERENCES public.estado(uf);


--
-- TOC entry 3311 (class 2606 OID 17677)
-- Name: conhecimento_transporte conhecimento_transporte_frete_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.conhecimento_transporte
    ADD CONSTRAINT conhecimento_transporte_frete_fk FOREIGN KEY (fk_frete) REFERENCES public.frete(id_frete);


--
-- TOC entry 3312 (class 2606 OID 17682)
-- Name: empresa empresa_cliente_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.empresa
    ADD CONSTRAINT empresa_cliente_fk FOREIGN KEY (codigo_cli) REFERENCES public.cliente(cod_cli);


--
-- TOC entry 3318 (class 2606 OID 17687)
-- Name: pessoa_fisica fk_cliente_fisico; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pessoa_fisica
    ADD CONSTRAINT fk_cliente_fisico FOREIGN KEY (codigo_cli) REFERENCES public.cliente(cod_cli) ON DELETE CASCADE;


--
-- TOC entry 3319 (class 2606 OID 17692)
-- Name: pessoa_juridica fk_cliente_juridico; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pessoa_juridica
    ADD CONSTRAINT fk_cliente_juridico FOREIGN KEY (codigo_cli) REFERENCES public.cliente(cod_cli) ON DELETE CASCADE;


--
-- TOC entry 3313 (class 2606 OID 17697)
-- Name: frete fk_cod_cidade_destino; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frete
    ADD CONSTRAINT fk_cod_cidade_destino FOREIGN KEY (fk_cod_cidade_destino) REFERENCES public.cidade(codigo_cid);


--
-- TOC entry 3314 (class 2606 OID 17702)
-- Name: frete fk_cod_cidade_origem; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frete
    ADD CONSTRAINT fk_cod_cidade_origem FOREIGN KEY (fk_cod_cidade_origem) REFERENCES public.cidade(codigo_cid);


--
-- TOC entry 3315 (class 2606 OID 17707)
-- Name: frete fk_frete_cliente_destinatario; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frete
    ADD CONSTRAINT fk_frete_cliente_destinatario FOREIGN KEY (fk_cliente_destinatario) REFERENCES public.cliente(cod_cli);


--
-- TOC entry 3316 (class 2606 OID 17712)
-- Name: frete fk_frete_cliente_remetente; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frete
    ADD CONSTRAINT fk_frete_cliente_remetente FOREIGN KEY (fk_cliente_remetente) REFERENCES public.cliente(cod_cli);


--
-- TOC entry 3317 (class 2606 OID 17717)
-- Name: frete frete_funcionario_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.frete
    ADD CONSTRAINT frete_funcionario_fk FOREIGN KEY (fk_funcionario) REFERENCES public.funcionario(num_reg);


--
-- TOC entry 3320 (class 2606 OID 17799)
-- Name: pessoa_juridica pessoa_juridica_pessoa_fisica_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pessoa_juridica
    ADD CONSTRAINT pessoa_juridica_pessoa_fisica_fk FOREIGN KEY (id_representante) REFERENCES public.pessoa_fisica(cpf);


-- Completed on 2024-12-08 08:57:48 -03

INSERT INTO public.estado VALUES ('AC', 'Acre', 17, 12);
INSERT INTO public.estado VALUES ('AL', 'Alagoas', 18, 12);
INSERT INTO public.estado VALUES ('AP', 'Amapá', 18, 12);
INSERT INTO public.estado VALUES ('AM', 'Amazonas', 18, 12);
INSERT INTO public.estado VALUES ('BA', 'Bahia', 18, 12);
INSERT INTO public.estado VALUES ('CE', 'Ceará', 18, 12);
INSERT INTO public.estado VALUES ('DF', 'Distrito Federal', 18, 12);
INSERT INTO public.estado VALUES ('ES', 'Espírito Santo', 17, 12);
INSERT INTO public.estado VALUES ('GO', 'Goiás', 17, 12);
INSERT INTO public.estado VALUES ('MA', 'Maranhão', 18, 12);
INSERT INTO public.estado VALUES ('MT', 'Mato Grosso', 17, 12);
INSERT INTO public.estado VALUES ('MS', 'Mato Grosso do Sul', 17, 12);
INSERT INTO public.estado VALUES ('MG', 'Minas Gerais', 18, 12);
INSERT INTO public.estado VALUES ('PA', 'Pará', 18, 12);
INSERT INTO public.estado VALUES ('PB', 'Paraíba', 18, 12);
INSERT INTO public.estado VALUES ('PR', 'Paraná', 18, 12);
INSERT INTO public.estado VALUES ('PE', 'Pernambuco', 18, 12);
INSERT INTO public.estado VALUES ('PI', 'Piauí', 18, 12);
INSERT INTO public.estado VALUES ('RJ', 'Rio de Janeiro', 20, 12);
INSERT INTO public.estado VALUES ('RN', 'Rio Grande do Norte', 18, 12);
INSERT INTO public.estado VALUES ('RS', 'Rio Grande do Sul', 18, 12);
INSERT INTO public.estado VALUES ('RO', 'Rondônia', 17, 12);
INSERT INTO public.estado VALUES ('RR', 'Roraima', 17, 12);
INSERT INTO public.estado VALUES ('SC', 'Santa Catarina', 17, 12);
INSERT INTO public.estado VALUES ('SP', 'São Paulo', 18, 12);
INSERT INTO public.estado VALUES ('SE', 'Sergipe', 18, 12);
INSERT INTO public.estado VALUES ('TO', 'Tocantins', 18, 12);




--
-- TOC entry 3465 (class 0 OID 17612)
-- Dependencies: 216
-- Data for Name: cidade; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.cidade VALUES (1, 'São Paulo', 10.5, 200, 'SP');
INSERT INTO public.cidade VALUES (2, 'Rio de Janeiro', 9.8, 180, 'RJ');
INSERT INTO public.cidade VALUES (3, 'Belo Horizonte', 8.75, 150, 'MG');
INSERT INTO public.cidade VALUES (4, 'Goiânia', 7.6, 120, 'GO');
INSERT INTO public.cidade VALUES (5, 'Porto Alegre', 9.25, 170, 'RS');
INSERT INTO public.cidade VALUES (6, 'Cidade teste', 10, 10, 'RS');
INSERT INTO public.cidade VALUES (7, 'Curitiba', 9.5, 160, 'PR');
INSERT INTO public.cidade VALUES (8, 'Florianópolis', 9.2, 140, 'SC');
INSERT INTO public.cidade VALUES (9, 'Salvador', 8.8, 130, 'BA');
INSERT INTO public.cidade VALUES (10, 'Fortaleza', 8.7, 120, 'CE');
INSERT INTO public.cidade VALUES (11, 'Brasília', 9.1, 150, 'DF');
INSERT INTO public.cidade VALUES (12, 'Manaus', 8.6, 100, 'AM');
INSERT INTO public.cidade VALUES (13, 'Recife', 8.9, 110, 'PE');
INSERT INTO public.cidade VALUES (14, 'Belém', 8.4, 90, 'PA');
INSERT INTO public.cidade VALUES (15, 'Campinas', 9.3, 140, 'SP');
INSERT INTO public.cidade VALUES (16, 'Vitória', 8.5, 110, 'ES');
INSERT INTO public.cidade VALUES (17, 'São Luís', 8.2, 100, 'MA');
INSERT INTO public.cidade VALUES (18, 'João Pessoa', 8.7, 90, 'PB');
INSERT INTO public.cidade VALUES (19, 'Maceió', 8.9, 95, 'AL');
INSERT INTO public.cidade VALUES (20, 'Aracaju', 8.5, 85, 'SE');
INSERT INTO public.cidade VALUES (21, 'Cuiabá', 8.3, 80, 'MT');
INSERT INTO public.cidade VALUES (22, 'Campo Grande', 8.4, 85, 'MS');
INSERT INTO public.cidade VALUES (23, 'Macapá', 7.9, 70, 'AP');
INSERT INTO public.cidade VALUES (24, 'Palmas', 7.8, 60, 'TO');
INSERT INTO public.cidade VALUES (25, 'Boa Vista', 7.6, 55, 'RR');
INSERT INTO public.cidade VALUES (26, 'Porto Velho', 7.7, 65, 'RO');
INSERT INTO public.cidade VALUES (27, 'Rio Branco', 7.5, 50, 'AC');
INSERT INTO public.cidade VALUES (28, 'Sorocaba', 8.8, 100, 'SP');
INSERT INTO public.cidade VALUES (29, 'Joinville', 8.9, 110, 'SC');
INSERT INTO public.cidade VALUES (30, 'Niterói', 8.6, 120, 'RJ');
INSERT INTO public.cidade VALUES (31, 'Uberlândia', 8.5, 130, 'MG');
INSERT INTO public.cidade VALUES (32, 'Juiz de Fora', 8.4, 110, 'MG');
INSERT INTO public.cidade VALUES (33, 'Caxias do Sul', 8.3, 115, 'RS');
INSERT INTO public.cidade VALUES (34, 'Londrina', 8.7, 120, 'PR');
INSERT INTO public.cidade VALUES (35, 'Maringá', 8.6, 115, 'PR');
INSERT INTO public.cidade VALUES (36, 'Blumenau', 8.8, 105, 'SC');
INSERT INTO public.cidade VALUES (37, 'Itajaí', 8.5, 100, 'SC');
INSERT INTO public.cidade VALUES (38, 'Canoas', 8.2, 95, 'RS');
INSERT INTO public.cidade VALUES (39, 'Pelotas', 8.1, 90, 'RS');
INSERT INTO public.cidade VALUES (40, 'Anápolis', 8.0, 85, 'GO');



--
-- TOC entry 3467 (class 0 OID 17619)
-- Dependencies: 218
-- Data for Name: cliente; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.cliente VALUES (1, '2024-01-15', 'Rua das Flores, 123', '(11) 91234-5678', NULL);
INSERT INTO public.cliente VALUES (2, '2024-02-20', 'Avenida Brasil, 456', '(21) 99876-5432', NULL);
INSERT INTO public.cliente VALUES (3, '2024-03-10', 'Praça Central, 789', '(31) 98765-4321', NULL);
INSERT INTO public.cliente VALUES (4, '2024-04-05', 'Rua do Comércio, 321', '(11) 95678-1234', NULL);
INSERT INTO public.cliente VALUES (5, '2024-05-12', 'Rua das Palmeiras, 456', '(41) 91234-9876', NULL);
INSERT INTO public.cliente VALUES (6, '2024-06-18', 'Avenida Independência, 789', '(51) 98765-1234', NULL);
INSERT INTO public.cliente VALUES (7, '2024-07-25', 'Rua das Margaridas, 321', '(61) 95678-4321', NULL);
INSERT INTO public.cliente VALUES (8, '2024-08-30', 'Avenida das Américas, 654', '(71) 99876-5678', NULL);
INSERT INTO public.cliente VALUES (9, '2024-09-10', 'Praça da República, 987', '(81) 91234-6789', NULL);
INSERT INTO public.cliente VALUES (10, '2024-10-15', 'Rua Dom Pedro, 456', '(91) 98765-6789', NULL);
INSERT INTO public.cliente VALUES (11, '2024-11-20', 'Avenida Getúlio Vargas, 123', '(31) 99876-5432', NULL);
INSERT INTO public.cliente VALUES (12, '2024-12-05', 'Rua dos Bandeirantes, 789', '(41) 95678-1234', NULL);
INSERT INTO public.cliente VALUES (13, '2025-01-10', 'Avenida das Nações, 321', '(51) 91234-5678', NULL);
INSERT INTO public.cliente VALUES (14, '2025-02-15', 'Rua Coronel Silva, 654', '(61) 98765-6789', NULL);
INSERT INTO public.cliente VALUES (15, '2025-03-20', 'Avenida Santos Dumont, 987', '(71) 95678-4321', NULL);
INSERT INTO public.cliente VALUES (16, '2025-04-25', 'Praça João Pessoa, 456', '(81) 99876-5432', NULL);
INSERT INTO public.cliente VALUES (17, '2025-05-30', 'Rua Sete de Setembro, 123', '(91) 98765-6789', NULL);
INSERT INTO public.cliente VALUES (18, '2025-06-05', 'Avenida Rio Branco, 789', '(31) 95678-1234', NULL);
INSERT INTO public.cliente VALUES (19, '2025-07-10', 'Rua Barão do Rio Branco, 321', '(41) 99876-5678', NULL);
INSERT INTO public.cliente VALUES (20, '2025-08-15', 'Avenida Tiradentes, 654', '(51) 91234-6789', NULL);
INSERT INTO public.cliente VALUES (21, '2025-09-01', 'Rua do Sol, 123', '(11) 92345-6789', NULL);
INSERT INTO public.cliente VALUES (22, '2025-09-10', 'Avenida Primavera, 456', '(21) 93456-7890', NULL);
INSERT INTO public.cliente VALUES (23, '2025-09-15', 'Praça Verde, 789', '(31) 94567-8901', NULL);
INSERT INTO public.cliente VALUES (24, '2025-09-20', 'Rua das Pedras, 321', '(41) 95678-9012', NULL);
INSERT INTO public.cliente VALUES (25, '2025-10-01', 'Avenida Central, 654', '(51) 96789-0123', NULL);
INSERT INTO public.cliente VALUES (26, '2025-10-10', 'Praça do Trabalhador, 987', '(61) 97890-1234', NULL);
INSERT INTO public.cliente VALUES (27, '2025-10-15', 'Rua São Jorge, 456', '(71) 98901-2345', NULL);
INSERT INTO public.cliente VALUES (28, '2025-10-20', 'Avenida João Paulo, 123', '(81) 91234-3456', NULL);
INSERT INTO public.cliente VALUES (29, '2025-11-01', 'Praça Santa Maria, 789', '(91) 92345-4567', NULL);
INSERT INTO public.cliente VALUES (30, '2025-11-10', 'Rua do Comércio, 654', '(31) 93456-5678', NULL);
INSERT INTO public.cliente VALUES (31, '2025-11-15', 'Avenida do Contorno, 321', '(41) 94567-6789', NULL);
INSERT INTO public.cliente VALUES (32, '2025-11-20', 'Praça da Liberdade, 456', '(51) 95678-7890', NULL);
INSERT INTO public.cliente VALUES (33, '2025-12-01', 'Rua Dom Pedro II, 123', '(61) 96789-8901', NULL);
INSERT INTO public.cliente VALUES (34, '2025-12-10', 'Avenida das Árvores, 654', '(71) 97890-9012', NULL);
INSERT INTO public.cliente VALUES (35, '2025-12-15', 'Praça do Cruzeiro, 987', '(81) 98901-0123', NULL);
INSERT INTO public.cliente VALUES (36, '2025-12-20', 'Rua Dom Bosco, 789', '(91) 91234-1234', NULL);
INSERT INTO public.cliente VALUES (37, '2026-01-01', 'Avenida do Sol, 456', '(31) 92345-2345', NULL);
INSERT INTO public.cliente VALUES (38, '2026-01-10', 'Praça dos Pioneiros, 321', '(41) 93456-3456', NULL);
INSERT INTO public.cliente VALUES (39, '2026-01-15', 'Rua São Paulo, 654', '(51) 94567-4567', NULL);
INSERT INTO public.cliente VALUES (40, '2026-01-20', 'Avenida dos Estados, 987', '(61) 95678-5678', NULL);

--
-- TOC entry 3468 (class 0 OID 17623)
-- Dependencies: 219
-- Data for Name: conhecimento_transporte; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 3469 (class 0 OID 17628)
-- Dependencies: 220
-- Data for Name: empresa; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 3470 (class 0 OID 17633)
-- Dependencies: 221
-- Data for Name: estado; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.funcionario VALUES (1, 'Carlos Almeida');
INSERT INTO public.funcionario VALUES (2, 'Fernanda Silva');
INSERT INTO public.funcionario VALUES (3, 'João Pereira');
INSERT INTO public.funcionario VALUES (4, 'Mariana Oliveira');
INSERT INTO public.funcionario VALUES (5, 'Ricardo Santos');
INSERT INTO public.funcionario VALUES (6, 'Ana Paula');
INSERT INTO public.funcionario VALUES (7, 'Lucas Ribeiro');
INSERT INTO public.funcionario VALUES (8, 'Beatriz Costa');
INSERT INTO public.funcionario VALUES (9, 'Gabriel Martins');
INSERT INTO public.funcionario VALUES (10, 'Juliana Souza');
INSERT INTO public.funcionario VALUES (11, 'Rodrigo Carvalho');
INSERT INTO public.funcionario VALUES (12, 'Patrícia Lima');
INSERT INTO public.funcionario VALUES (13, 'Thiago Mendes');
INSERT INTO public.funcionario VALUES (14, 'Larissa Ferreira');
INSERT INTO public.funcionario VALUES (15, 'Felipe Nascimento');
INSERT INTO public.funcionario VALUES (16, 'Vanessa Rocha');
INSERT INTO public.funcionario VALUES (17, 'Diego Torres');
INSERT INTO public.funcionario VALUES (18, 'Marcelo Fonseca');
INSERT INTO public.funcionario VALUES (19, 'Carolina Duarte');
INSERT INTO public.funcionario VALUES (20, 'Renato Barbosa');
INSERT INTO public.funcionario VALUES (21, 'Daniela Moura');
INSERT INTO public.funcionario VALUES (22, 'Bruno Moreira');
INSERT INTO public.funcionario VALUES (23, 'Tatiana Gomes');
INSERT INTO public.funcionario VALUES (24, 'Pedro Henrique');
INSERT INTO public.funcionario VALUES (25, 'Aline Monteiro');



--
-- TOC entry 3471 (class 0 OID 17638)
-- Dependencies: 222
-- Data for Name: frete; Type: TABLE DATA; Schema: public; Owner: -
--


--
-- TOC entry 3473 (class 0 OID 17646)
-- Dependencies: 224
-- Data for Name: funcionario; Type: TABLE DATA; Schema: public; Owner: -
--

--
-- TOC entry 3474 (class 0 OID 17649)
-- Dependencies: 225
-- Data for Name: pessoa_fisica; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.pessoa_fisica VALUES ('João da Silva', '12345678901', 1);
INSERT INTO public.pessoa_fisica VALUES ('Maria Oliveira', '98765432100', 2);
INSERT INTO public.pessoa_fisica VALUES ('Paulo Santos', '45645645678', 5);
INSERT INTO public.pessoa_fisica VALUES ('Fernanda Lima', '65465465487', 6);
INSERT INTO public.pessoa_fisica VALUES ('Lucas Mendes', '78978978912', 7);
INSERT INTO public.pessoa_fisica VALUES ('Beatriz Ferreira', '98798798721', 8);
INSERT INTO public.pessoa_fisica VALUES ('Rafael Costa', '15915915963', 9);
INSERT INTO public.pessoa_fisica VALUES ('Juliana Araújo', '95195195136', 10);
INSERT INTO public.pessoa_fisica VALUES ('Ricardo Almeida', '75375375398', 11);
INSERT INTO public.pessoa_fisica VALUES ('Camila Ribeiro', '35735735741', 12);
INSERT INTO public.pessoa_fisica VALUES ('Gabriel Martins', '85285285269', 13);
INSERT INTO public.pessoa_fisica VALUES ('Larissa Silva', '25825825896', 14);
INSERT INTO public.pessoa_fisica VALUES ('Thiago Barbosa', '45678912300', 15);
INSERT INTO public.pessoa_fisica VALUES ('Vanessa Moraes', '78912345677', 16);
INSERT INTO public.pessoa_fisica VALUES ('Diego Rocha', '12365498711', 17);
INSERT INTO public.pessoa_fisica VALUES ('Patrícia Carvalho', '65412378988', 18);
INSERT INTO public.pessoa_fisica VALUES ('Bruno Fernandes', '14725836900', 19);
INSERT INTO public.pessoa_fisica VALUES ('Letícia Vieira', '36925814777', 20);


--
-- TOC entry 3475 (class 0 OID 17652)
-- Dependencies: 226
-- Data for Name: pessoa_juridica; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.pessoa_juridica VALUES ('Logística Global S.A.', 'IS345678901', '33445566718899', 23, '12345678901');
INSERT INTO public.pessoa_juridica VALUES ('Construções Silva e Santos', 'IS456789012', '4455337889900', 24, '98765432100');
INSERT INTO public.pessoa_juridica VALUES ('Comercial Lima LTDA', 'IS567890123', '55667788990011', 25, '45645645678');
INSERT INTO public.pessoa_juridica VALUES ('Exportadora Mendes', 'IS678901234', '66778899001122', 26, '65465465487');
INSERT INTO public.pessoa_juridica VALUES ('Importadora Beatriz LTDA', 'IS789012345', '77889900112233', 27, '78978978912');
INSERT INTO public.pessoa_juridica VALUES ('Rafael Transporte e Logística', 'IS890123456', '88990011223344', 28, '98798798721');
INSERT INTO public.pessoa_juridica VALUES ('Juliana Consultoria Empresarial', 'IS901234567', '99001122334455', 29, '15915915963');
INSERT INTO public.pessoa_juridica VALUES ('Ricardo Engenharia S.A.', 'IS012345678', '00112233445566', 30, '95195195136');
INSERT INTO public.pessoa_juridica VALUES ('Camila Finanças LTDA', 'IS123450678', '11223344556677', 31, '75375375398');
INSERT INTO public.pessoa_juridica VALUES ('Gabriel Marketing S.A.', 'IS234560789', '22334455667788', 32, '35735735741');
INSERT INTO public.pessoa_juridica VALUES ('Larissa Eventos LTDA', 'IS345670890', '33445566778899', 33, '85285285269');
INSERT INTO public.pessoa_juridica VALUES ('Thiago Consultoria de TI', 'IS456780901', '44556677889900', 34, '25825825896');
INSERT INTO public.pessoa_juridica VALUES ('Vanessa Moda LTDA', 'IS567890012', '55667788990021', 35, '45678912300');
INSERT INTO public.pessoa_juridica VALUES ('Diego Comércio Geral', 'IS678900123', '66778899041122', 36, '78912345677');
INSERT INTO public.pessoa_juridica VALUES ('Patrícia Restaurante LTDA', 'IS789010234', '77885900112233', 37, '12365498711');
INSERT INTO public.pessoa_juridica VALUES ('Bruno Investimentos S.A.', 'IS890120345', '88990611223344', 38, '65412378988');
INSERT INTO public.pessoa_juridica VALUES ('Letícia Hotelaria LTDA', 'IS901230456', '99001127334455', 39, '14725836900');
INSERT INTO public.pessoa_juridica VALUES ('Felipe Indústria de Plásticos', 'IS012340567', '80112233445566', 40, '36925814777');


INSERT INTO public.frete VALUES ('Remetente', 'Peso', 150.5, 1200.75, 50, 204.75, '2024-10-01', 32, 1, 2, 1, 2, 500, 26);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 10.5, 321.75, 10, 204.75, '2024-10-12', 20, 2, 4, 3, 1, 100, 27);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 50.5, 10.75, 1, 203.75, '2024-10-25', 33, 3, 5, 1, 4, 50, 29);
INSERT INTO public.frete VALUES ('Destinatario', 'Valor', 50.5, 10.75, 1, 203.75, '2024-10-25', 21, 3, 5, 1, 4, 50, 28);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 50.5, 10.75, 1, 203.75, '2024-10-25', 21, 3, 5, 1, 4, 50, 30);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 120.0, 2500.50, 90, 400.50, '2024-11-10', 22, 5, 1, 2, 7, 800, 31);
INSERT INTO public.frete VALUES ('Remetente', 'Valor', 0.0, 5000.00, 100, 500.00, '2024-11-15', 23, 3, 6, 4, 8, 900, 32);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 80.0, 1200.75, 50, 250.75, '2024-11-20', 32, 2, 4, 3, 9, 400, 33);
INSERT INTO public.frete VALUES ('Remetente', 'Peso', 300.0, 3500.00, 150, 750.00, '2024-11-25', 36, 4, 5, 6, 10, 1200, 34);
INSERT INTO public.frete VALUES ('Destinatario', 'Valor', 0.0, 10000.00, 200, 1000.00, '2024-12-01', 38, 6, 3, 5, 1, 1500, 35);
INSERT INTO public.frete VALUES ('Remetente', 'Peso', 50.0, 800.00, 25, 175.00, '2024-12-10', 1, 1, 2, 2, 4, 300, 36);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 90.0, 2000.00, 75, 400.00, '2024-12-15', 25, 5, 6, 3, 8, 700, 37);
INSERT INTO public.frete VALUES ('Remetente', 'Valor', 0.0, 7500.00, 100, 800.00, '2024-12-20', 33, 2, 4, 4, 7, 1100, 38);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 70.0, 950.00, 40, 250.00, '2024-12-25', 1, 3, 5, 6, 10, 450, 39);
INSERT INTO public.frete VALUES ('Remetente', 'Peso', 150.0, 2200.00, 85, 300.00, '2025-01-01', 29, 4, 3, 5, 9, 800, 40);
INSERT INTO public.frete VALUES ('Destinatario', 'Valor', 0.0, 6000.00, 90, 600.00, '2025-01-10', 28, 1, 2, 3, 8, 1000, 41);
INSERT INTO public.frete VALUES ('Remetente', 'Peso', 200.0, 4500.00, 125, 900.00, '2025-01-15', 22, 5, 6, 4, 7, 1300, 42);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 60.0, 1400.00, 35, 200.00, '2025-01-20', 3, 3, 4, 6, 1, 500, 43);
INSERT INTO public.frete VALUES ('Remetente', 'Valor', 0.0, 8500.00, 150, 850.00, '2025-01-25', 27, 2, 5, 2, 10, 1400, 44);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 80.0, 1800.00, 45, 300.00, '2025-02-01', 36, 6, 4, 5, 9, 600, 45);
INSERT INTO public.frete VALUES ('Remetente', 'Peso', 130.0, 2800.00, 70, 400.00, '2025-02-10', 22, 4, 1, 3, 8, 900, 46);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 100.0, 3500.00, 150, 500.00, '2025-02-15', 22, 5, 6, 4, 7, 1200, 47);
INSERT INTO public.frete VALUES ('Remetente', 'Valor', 0.0, 9200.00, 180, 950.00, '2025-02-20', 22, 1, 2, 6, 10, 1500, 48);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 90.0, 2600.00, 100, 300.00, '2025-02-25', 27, 3, 5, 2, 4, 700, 49);


--
-- PostgreSQL database dump complete
--
SQL;

//Remove todas as tabelas e registros do banco
$sql_delete = <<<SQL

DO $$ DECLARE
     rec RECORD;
 BEGIN
     FOR rec IN (SELECT tablename FROM pg_tables WHERE schemaname = 'public') LOOP
         EXECUTE 'DROP TABLE IF EXISTS ' || rec.tablename || ' CASCADE';
     END LOOP;
 END $$;


SQL;
$result = pg_query($dbconn, $sql_delete);


$result = pg_query($dbconn, $sql);

if (!$result) {
    echo "Error in table creation: " . pg_last_error();
} else {
    echo "Tables created successfully";
}

// Close the database connection
pg_close($dbconn);

?>
