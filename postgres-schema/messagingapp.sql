--
-- PostgreSQL database dump
--

-- Dumped from database version 12.9 (Ubuntu 12.9-0ubuntu0.20.04.1)
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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: messaging_app_contacts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.messaging_app_contacts (
    u_id integer,
    c_uid integer
);


ALTER TABLE public.messaging_app_contacts OWNER TO postgres;

--
-- Name: messaging_app_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.messaging_app_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 2147483647
    CACHE 1;


ALTER TABLE public.messaging_app_messages_id_seq OWNER TO postgres;

--
-- Name: messaging_app_messages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.messaging_app_messages (
    sender_id integer,
    reciever_id integer,
    body_text text,
    reciever_read boolean DEFAULT false,
    date timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    message_id integer DEFAULT nextval('public.messaging_app_messages_id_seq'::regclass)
);


ALTER TABLE public.messaging_app_messages OWNER TO postgres;

--
-- Name: messaging_app_user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.messaging_app_user (
    u_id integer NOT NULL,
    username text NOT NULL,
    password text,
    token text,
    first_name text,
    last_name text
);


ALTER TABLE public.messaging_app_user OWNER TO postgres;

--
-- Name: messaging_app_user_u_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.messaging_app_user_u_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.messaging_app_user_u_id_seq OWNER TO postgres;

--
-- Name: messaging_app_user_u_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.messaging_app_user_u_id_seq OWNED BY public.messaging_app_user.u_id;


--
-- Name: messaging_app_user u_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messaging_app_user ALTER COLUMN u_id SET DEFAULT nextval('public.messaging_app_user_u_id_seq'::regclass);


--
-- Name: messaging_app_messages messaging_app_messages_message_id_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messaging_app_messages
    ADD CONSTRAINT messaging_app_messages_message_id_key UNIQUE (message_id);


--
-- Name: messaging_app_user messaging_app_user_phone_number_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messaging_app_user
    ADD CONSTRAINT messaging_app_user_phone_number_key UNIQUE (username);


--
-- Name: messaging_app_user messaging_app_user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messaging_app_user
    ADD CONSTRAINT messaging_app_user_pkey PRIMARY KEY (u_id);

--
-- PostgreSQL database dump complete
--
