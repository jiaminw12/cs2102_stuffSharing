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
ALTER TABLE ONLY public.items DROP CONSTRAINT items_pkey;
ALTER TABLE public.items ALTER COLUMN "itemID" DROP DEFAULT;
DROP TABLE public.userinfo;
DROP SEQUENCE public."items_itemID_seq";
DROP TABLE public.items;
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
-- Name: items; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE items (
    "itemID" integer NOT NULL,
    owner character varying(256) NOT NULL,
    item_title character varying(256) NOT NULL,
    description text,
    category character varying(9) NOT NULL,
    min_bid integer DEFAULT 0 NOT NULL,
    pickup_location character varying(256) NOT NULL,
    return_location character varying(256) NOT NULL,
    borrow_start_date date NOT NULL,
    borrow_end_date date NOT NULL,
    bid_end_date timestamp with time zone NOT NULL,
    item_image text
);


ALTER TABLE items OWNER TO postgres;

--
-- Name: items_itemID_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE "items_itemID_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE "items_itemID_seq" OWNER TO postgres;

--
-- Name: items_itemID_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE "items_itemID_seq" OWNED BY items."itemID";


--
-- Name: userinfo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE userinfo (
    username character varying(256) NOT NULL,
    email character varying(256) NOT NULL,
    name character varying(256) NOT NULL,
    password text NOT NULL,
    contact_num character(10) NOT NULL,
    address character varying(256) NOT NULL,
    date_of_birth character varying(10) NOT NULL,
    admin smallint DEFAULT 0,
    bid_point integer DEFAULT 1000
);


ALTER TABLE userinfo OWNER TO postgres;

--
-- Name: itemID; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY items ALTER COLUMN "itemID" SET DEFAULT nextval('"items_itemID_seq"'::regclass);


--
-- Data for Name: items; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: items_itemID_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('"items_itemID_seq"', 1, false);


--
-- Data for Name: userinfo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO userinfo VALUES ('a', 'aaa@aa.com', 'a', 'c4ca4238a0b923820dcc509a6f75849b', '1         ', '1', '03/02/2016', 0, 1000);


--
-- Name: items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY items
    ADD CONSTRAINT items_pkey PRIMARY KEY ("itemID", owner, item_title);


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

