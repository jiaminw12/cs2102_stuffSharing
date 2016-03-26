--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

ALTER TABLE ONLY public.userinfo DROP CONSTRAINT userinfo_pkey;
DROP TABLE public.userinfo;
DROP EXTENSION plpgsql;
DROP SCHEMA public;
--
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO postgres;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'standard public schema';


--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: userinfo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE userinfo (
    username character varying(256) NOT NULL,
    email character varying(256) NOT NULL,
    name character varying(256) NOT NULL,
    password character varying(128) NOT NULL,
    contact_num character(10) NOT NULL,
    address character varying(256) NOT NULL,
    date_of_birth date NOT NULL,
    admin smallint DEFAULT 0,
    bid_point integer DEFAULT 1000
);


ALTER TABLE userinfo OWNER TO postgres;

--
-- Data for Name: userinfo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO userinfo VALUES ('a', 'aa@aa.com', 'a', 'c4ca4238a0b923820dcc509a6f75849b', '111       ', 'ssad', '2016-03-03', 0, 1000);


--
-- Name: userinfo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY userinfo
    ADD CONSTRAINT userinfo_pkey PRIMARY KEY (username, email);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

