--
-- PostgreSQL database dump
--

-- Dumped from database version 12.9 (Debian 12.9-1.pgdg110+1)
-- Dumped by pg_dump version 12.9 (Ubuntu 12.9-0ubuntu0.20.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: lcube; Type: DATABASE; Schema: -; Owner: -
--

CREATE DATABASE lcube WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'en_US.utf8' LC_CTYPE = 'en_US.utf8';


\connect lcube

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: Login; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."Login" (
    u_id integer NOT NULL,
    phone_number numeric(10,0) NOT NULL,
    password text NOT NULL,
    token text NOT NULL
);


--
-- Name: Login_u_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public."Login_u_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: Login_u_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public."Login_u_id_seq" OWNED BY public."Login".u_id;


--
-- Name: Messages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."Messages" (
    s_id numeric(10,0) NOT NULL,
    r_id numeric(10,0) NOT NULL,
    body_text text NOT NULL,
    r_read boolean NOT NULL,
    date date,
    m_id numeric(10,0) NOT NULL
);


--
-- Name: Relationship; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."Relationship" (
    u_id numeric(10,0) NOT NULL,
    c_uid numeric(10,0) NOT NULL
);


--
-- Name: Login u_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."Login" ALTER COLUMN u_id SET DEFAULT nextval('public."Login_u_id_seq"'::regclass);


--
-- Name: Login Login_token_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."Login"
    ADD CONSTRAINT "Login_token_key" UNIQUE (token);


--
-- Name: Login Login_u_id_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."Login"
    ADD CONSTRAINT "Login_u_id_key" UNIQUE (u_id);


--
-- Name: Messages Messages_m_id_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."Messages"
    ADD CONSTRAINT "Messages_m_id_key" UNIQUE (m_id);


--
-- PostgreSQL database dump complete
--

