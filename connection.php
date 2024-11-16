<?php
//https://www.php.net/manual/en/pgsql.examples-basic.php

// Execute the SQL query
$result = '';

$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234")
    or die('Could not connect: ' . pg_last_error());

echo 'Connected successfully';

$sql = <<<SQL
CREATE SCHEMA IF NOT EXISTS public;

COMMENT ON SCHEMA public IS 'standard public schema';

CREATE TABLE IF NOT EXISTS public.cidade (
    codigo_cid SERIAL,
    nome_cid VARCHAR(100) NOT NULL,
    preco_unit_peso REAL CHECK (preco_unit_peso >= 0),
    preco_unit_valor REAL CHECK (preco_unit_valor >= 0),
    fk_uf VARCHAR(2)
);

CREATE TABLE IF NOT EXISTS public.cliente (
    cod_cli SERIAL,
    data_insc DATE NOT NULL,
    endereco VARCHAR(200) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    tipo VARCHAR(10)
);

-- TOC entry 223 (class 1259 OID 16658)
-- Name: conhecimento_transporte; Type: TABLE; Schema: public; Owner: -
--
CREATE TABLE IF NOT EXISTS  public.conhecimento_transporte (
   id_conhecimento character varying,
   fk_frete integer
);
--
-- TOC entry 224 (class 1259 OID 16668)
-- Name: empresa; Type: TABLE; Schema: public; Owner: -
--
CREATE TABLE IF NOT EXISTS  public.empresa (
   codigo_cli integer,
   razao_social character varying,
   inscricao_estadual character varying,
   cnpj character varying,
   nome_representante character varying,
   telefone_representante bigint
);
--
-- TOC entry 217 (class 1259 OID 16577)
-- Name: estado; Type: TABLE; Schema: public; Owner: -
--
CREATE TABLE IF NOT EXISTS  public.estado (
   uf character varying(2) NOT NULL,
   nome_est character varying(50) NOT NULL,
   icms_local real NOT NULL,
   icms_outro_uf real NOT NULL,
   CONSTRAINT estado_icms_local_check CHECK (((icms_local >= (0)::double precision) AND (icms_local <= (100)::double precision))),
   CONSTRAINT estado_icms_outro_uf_check CHECK (((icms_outro_uf >= (0)::double precision) AND (icms_outro_uf <= (100)::double precision)))
);
--
-- TOC entry 218 (class 1259 OID 16582)
-- Name: frete; Type: TABLE; Schema: public; Owner: -
--
CREATE TABLE IF NOT EXISTS  public.frete (
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
-- TOC entry 3476 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN frete.quem_paga; Type: COMMENT; Schema: public; Owner: -
--
COMMENT ON COLUMN public.frete.quem_paga IS '-- Indica quem paga o frete, remetente ou destinatário';
--
-- TOC entry 3477 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN frete.peso_ou_valor; Type: COMMENT; Schema: public; Owner: -
--
COMMENT ON COLUMN public.frete.peso_ou_valor IS 'Valor do frete baseado no peso ou valor';
--
-- TOC entry 3478 (class 0 OID 0)
-- Dependencies: 218
-- Name: COLUMN frete.id_frete; Type: COMMENT; Schema: public; Owner: -
--
COMMENT ON COLUMN public.frete.id_frete IS 'Numero conhecimento_ poderia ser uma nova tabela';
--
-- TOC entry 219 (class 1259 OID 16589)
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
-- TOC entry 3479 (class 0 OID 0)
-- Dependencies: 219
-- Name: frete_conhecimento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--
ALTER SEQUENCE public.frete_conhecimento_seq OWNED BY public.frete.id_frete;
--
-- TOC entry 220 (class 1259 OID 16590)
-- Name: funcionario; Type: TABLE; Schema: public; Owner: -
--
CREATE TABLE IF NOT EXISTS  public.funcionario (
   num_reg integer NOT NULL,
   nome_func character varying(100) NOT NULL
);
--
-- TOC entry 221 (class 1259 OID 16593)
-- Name: pessoa_fisica; Type: TABLE; Schema: public; Owner: -
--
CREATE TABLE IF NOT EXISTS  public.pessoa_fisica (
   nome_cli character varying(100) NOT NULL,
   cpf character varying(11) NOT NULL,
   codigo_cli integer
);
--
-- TOC entry 222 (class 1259 OID 16596)
-- Name: pessoa_juridica; Type: TABLE; Schema: public; Owner: -
--
CREATE TABLE IF NOT EXISTS  public.pessoa_juridica (
   razao_social character varying(150) NOT NULL,
   insc_estadual character varying(20) NOT NULL,
   cnpj character varying(14) NOT NULL,
   codigo_cli integer,
   id_representante character varying
);
--
-- TOC entry 3283 (class 2604 OID 16599)
-- Name: frete id_frete; Type: DEFAULT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.frete ALTER COLUMN id_frete SET DEFAULT nextval('public.frete_conhecimento_seq'::regclass);
--
-- TOC entry 3459 (class 0 OID 16567)
-- Dependencies: 215
-- Data for Name: cidade; Type: TABLE DATA; Schema: public; Owner: -
--
INSERT INTO public.cidade VALUES (1, 'São Paulo', 10.5, 200, 'SP');
INSERT INTO public.cidade VALUES (2, 'Rio de Janeiro', 9.8, 180, 'RJ');
INSERT INTO public.cidade VALUES (3, 'Belo Horizonte', 8.75, 150, 'MG');
INSERT INTO public.cidade VALUES (4, 'Goiânia', 7.6, 120, 'GO');
INSERT INTO public.cidade VALUES (5, 'Porto Alegre', 9.25, 170, 'RS');
--
-- TOC entry 3460 (class 0 OID 16574)
-- Dependencies: 216
-- Data for Name: cliente; Type: TABLE DATA; Schema: public; Owner: -
--
INSERT INTO public.cliente VALUES (1, '2024-01-15', 'Rua das Flores, 123', '(11) 91234-5678', NULL);
INSERT INTO public.cliente VALUES (2, '2024-02-20', 'Avenida Brasil, 456', '(21) 99876-5432', NULL);
INSERT INTO public.cliente VALUES (3, '2024-03-10', 'Praça Central, 789', '(31) 98765-4321', NULL);
INSERT INTO public.cliente VALUES (4, '2024-04-05', 'Rua do Comércio, 321', '(11) 95678-1234', NULL);
--
-- TOC entry 3467 (class 0 OID 16658)
-- Dependencies: 223
-- Data for Name: conhecimento_transporte; Type: TABLE DATA; Schema: public; Owner: -
--
--
-- TOC entry 3468 (class 0 OID 16668)
-- Dependencies: 224
-- Data for Name: empresa; Type: TABLE DATA; Schema: public; Owner: -
--
--
-- TOC entry 3461 (class 0 OID 16577)
-- Dependencies: 217
-- Data for Name: estado; Type: TABLE DATA; Schema: public; Owner: -
--
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
-- TOC entry 3462 (class 0 OID 16582)
-- Dependencies: 218
-- Data for Name: frete; Type: TABLE DATA; Schema: public; Owner: -
--
INSERT INTO public.frete VALUES ('Remetente', 'Peso', 150.5, 1200.75, 50, 204.75, '2024-10-01', 1, 1, 2, 1, 2, 500, 26);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 10.5, 321.75, 10, 204.75, '2024-10-12', 3, 2, 4, 3, 1, 100, 27);
INSERT INTO public.frete VALUES ('Destinatario', 'Peso', 50.5, 10.75, 1, 203.75, '2024-10-25', 1, 3, 5, 1, 4, 50, 29);
INSERT INTO public.frete VALUES ('Destinatario', 'Valor', 50.5, 10.75, 1, 203.75, '2024-10-25', 1, 3, 5, 1, 4, 50, 28);
--
-- TOC entry 3464 (class 0 OID 16590)
-- Dependencies: 220
-- Data for Name: funcionario; Type: TABLE DATA; Schema: public; Owner: -
--
INSERT INTO public.funcionario VALUES (1, 'Carlos Almeida');
INSERT INTO public.funcionario VALUES (2, 'Fernanda Silva');
INSERT INTO public.funcionario VALUES (3, 'João Pereira');
INSERT INTO public.funcionario VALUES (4, 'Mariana Oliveira');
INSERT INTO public.funcionario VALUES (5, 'Ricardo Santos');
--
-- TOC entry 3465 (class 0 OID 16593)
-- Dependencies: 221
-- Data for Name: pessoa_fisica; Type: TABLE DATA; Schema: public; Owner: -
--
INSERT INTO public.pessoa_fisica VALUES ('João da Silva', '12345678901', 1);
INSERT INTO public.pessoa_fisica VALUES ('Maria Oliveira', '98765432100', 2);
--
-- TOC entry 3466 (class 0 OID 16596)
-- Dependencies: 222
-- Data for Name: pessoa_juridica; Type: TABLE DATA; Schema: public; Owner: -
--
INSERT INTO public.pessoa_juridica VALUES ('Empresa XYZ Ltda', 'IS123456789', '11223344556677', 3, NULL);
INSERT INTO public.pessoa_juridica VALUES ('Comércio ABC S.A.', 'IS987654321', '99887766554433', 4, NULL);
--
-- TOC entry 3480 (class 0 OID 0)
-- Dependencies: 219
-- Name: frete_conhecimento_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--
SELECT pg_catalog.setval('public.frete_conhecimento_seq', 29, true);
--
-- TOC entry 3293 (class 2606 OID 16601)
-- Name: cidade cidade_pk; Type: CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.cidade
   ADD CONSTRAINT cidade_pk PRIMARY KEY (codigo_cid);
--
-- TOC entry 3295 (class 2606 OID 16603)
-- Name: cliente cliente_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.cliente
   ADD CONSTRAINT cliente_pkey PRIMARY KEY (cod_cli);
--
-- TOC entry 3299 (class 2606 OID 16605)
-- Name: frete frete_pk; Type: CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.frete
   ADD CONSTRAINT frete_pk PRIMARY KEY (id_frete);
--
-- TOC entry 3301 (class 2606 OID 16607)
-- Name: funcionario funcionario_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.funcionario
   ADD CONSTRAINT funcionario_pkey PRIMARY KEY (num_reg);
--
-- TOC entry 3303 (class 2606 OID 16609)
-- Name: pessoa_fisica pessoa_fisica_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.pessoa_fisica
   ADD CONSTRAINT pessoa_fisica_pkey PRIMARY KEY (cpf);
--
-- TOC entry 3305 (class 2606 OID 16611)
-- Name: pessoa_juridica pessoa_juridica_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.pessoa_juridica
   ADD CONSTRAINT pessoa_juridica_pkey PRIMARY KEY (cnpj);
--
-- TOC entry 3297 (class 2606 OID 16613)
-- Name: estado uf; Type: CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.estado
   ADD CONSTRAINT uf PRIMARY KEY (uf);
--
-- TOC entry 3306 (class 2606 OID 16614)
-- Name: cidade cidade_estado_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.cidade
   ADD CONSTRAINT cidade_estado_fk FOREIGN KEY (fk_uf) REFERENCES public.estado(uf);
--
-- TOC entry 3314 (class 2606 OID 16663)
-- Name: conhecimento_transporte conhecimento_transporte_frete_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.conhecimento_transporte
   ADD CONSTRAINT conhecimento_transporte_frete_fk FOREIGN KEY (fk_frete) REFERENCES public.frete(id_frete);
--
-- TOC entry 3315 (class 2606 OID 16673)
-- Name: empresa empresa_cliente_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.empresa
   ADD CONSTRAINT empresa_cliente_fk FOREIGN KEY (codigo_cli) REFERENCES public.cliente(cod_cli);
--
-- TOC entry 3312 (class 2606 OID 16619)
-- Name: pessoa_fisica fk_cliente_fisico; Type: FK CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.pessoa_fisica
   ADD CONSTRAINT fk_cliente_fisico FOREIGN KEY (codigo_cli) REFERENCES public.cliente(cod_cli) ON DELETE CASCADE;
--
-- TOC entry 3313 (class 2606 OID 16624)
-- Name: pessoa_juridica fk_cliente_juridico; Type: FK CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.pessoa_juridica
   ADD CONSTRAINT fk_cliente_juridico FOREIGN KEY (codigo_cli) REFERENCES public.cliente(cod_cli) ON DELETE CASCADE;
--
-- TOC entry 3307 (class 2606 OID 16629)
-- Name: frete fk_cod_cidade_destino; Type: FK CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.frete
   ADD CONSTRAINT fk_cod_cidade_destino FOREIGN KEY (fk_cod_cidade_destino) REFERENCES public.cidade(codigo_cid);
--
-- TOC entry 3308 (class 2606 OID 16634)
-- Name: frete fk_cod_cidade_origem; Type: FK CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.frete
   ADD CONSTRAINT fk_cod_cidade_origem FOREIGN KEY (fk_cod_cidade_origem) REFERENCES public.cidade(codigo_cid);
--
-- TOC entry 3309 (class 2606 OID 16639)
-- Name: frete fk_frete_cliente_destinatario; Type: FK CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.frete
   ADD CONSTRAINT fk_frete_cliente_destinatario FOREIGN KEY (fk_cliente_destinatario) REFERENCES public.cliente(cod_cli);
--
-- TOC entry 3310 (class 2606 OID 16649)
-- Name: frete fk_frete_cliente_remetente; Type: FK CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.frete
   ADD CONSTRAINT fk_frete_cliente_remetente FOREIGN KEY (fk_cliente_remetente) REFERENCES public.cliente(cod_cli);
--
-- TOC entry 3311 (class 2606 OID 16644)
-- Name: frete frete_funcionario_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--
ALTER TABLE ONLY public.frete
   ADD CONSTRAINT frete_funcionario_fk FOREIGN KEY (fk_funcionario) REFERENCES public.funcionario(num_reg);
-- Completed on 2024-10-12 14:42:09 -03
--
-- PostgreSQL database dump complete
--
SQL;

// Remove todas as tabelas e registros do banco
//  pg_query($dbconn, "DO $$ DECLARE
//      rec RECORD;
//  BEGIN
//      FOR rec IN (SELECT tablename FROM pg_tables WHERE schemaname = 'public') LOOP
//          EXECUTE 'DROP TABLE IF EXISTS ' || rec.tablename || ' CASCADE';
//      END LOOP;
//  END $$;");

$result = pg_query($dbconn, $sql);

if (!$result) {
    echo "Error in table creation: " . pg_last_error();
} else {
    echo "Tables created successfully";
}

// Close the database connection
pg_close($dbconn);

?>
