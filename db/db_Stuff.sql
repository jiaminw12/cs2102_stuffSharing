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

ALTER TABLE ONLY public.items DROP CONSTRAINT items_owner_fkey;
ALTER TABLE ONLY public.borrows DROP CONSTRAINT borrows_userinfo_fkey;
ALTER TABLE ONLY public.borrows DROP CONSTRAINT borrows_items_fkey;
ALTER TABLE ONLY public.bids DROP CONSTRAINT bids_userinfo_fkey;
ALTER TABLE ONLY public.bids DROP CONSTRAINT bids_items_fkey;
ALTER TABLE ONLY public.userinfo DROP CONSTRAINT userinfo_username_key;
ALTER TABLE ONLY public.userinfo DROP CONSTRAINT userinfo_pkey;
ALTER TABLE ONLY public.items DROP CONSTRAINT items_pkey;
ALTER TABLE ONLY public.borrows DROP CONSTRAINT borrows_pkey;
ALTER TABLE ONLY public.bids DROP CONSTRAINT bids_pkey;
DROP TABLE public.userinfo;
DROP TABLE public.items;
DROP TABLE public.borrows;
DROP TABLE public.bids;
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
-- Name: bids; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE bids (
    owner character varying(256) NOT NULL,
    bidder character varying(256) NOT NULL,
    item_id character varying(256) NOT NULL,
    bid_point integer NOT NULL,
    created_date timestamp with time zone DEFAULT now()
);


ALTER TABLE bids OWNER TO postgres;

--
-- Name: borrows; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE borrows (
    owner character varying(256) NOT NULL,
    borrower character varying(256) NOT NULL,
    item_id character varying(256) NOT NULL,
    status smallint DEFAULT 0 NOT NULL,
    created_date timestamp with time zone DEFAULT now()
);


ALTER TABLE borrows OWNER TO postgres;

--
-- Name: items; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE items (
    item_id character varying(256) NOT NULL,
    owner character varying(256) NOT NULL,
    item_title character varying(256) NOT NULL,
    description text,
    category character varying(50) NOT NULL,
    bid_point_status smallint NOT NULL,
    available smallint DEFAULT 1,
    pickup_location character varying(256) NOT NULL,
    return_location character varying(256) NOT NULL,
    borrow_start_date date NOT NULL,
    borrow_end_date date NOT NULL,
    bid_end_date timestamp with time zone,
    item_image text
);


ALTER TABLE items OWNER TO postgres;

--
-- Name: userinfo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE userinfo (
    username character varying(128) NOT NULL,
    email character varying(256) NOT NULL,
    name character varying(256) NOT NULL,
    password text NOT NULL,
    contact_num character varying(10) NOT NULL,
    admin smallint DEFAULT 0,
    bid_point integer DEFAULT 1000
);


ALTER TABLE userinfo OWNER TO postgres;

--
-- Data for Name: bids; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: borrows; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: items; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: userinfo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: bids_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY bids
    ADD CONSTRAINT bids_pkey PRIMARY KEY (owner, item_id, bid_point);


--
-- Name: borrows_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY borrows
    ADD CONSTRAINT borrows_pkey PRIMARY KEY (owner, item_id);


--
-- Name: items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY items
    ADD CONSTRAINT items_pkey PRIMARY KEY (item_id, owner);


--
-- Name: userinfo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY userinfo
    ADD CONSTRAINT userinfo_pkey PRIMARY KEY (email);


--
-- Name: userinfo_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY userinfo
    ADD CONSTRAINT userinfo_username_key UNIQUE (username);


--
-- Name: bids_items_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bids
    ADD CONSTRAINT bids_items_fkey FOREIGN KEY (owner, item_id) REFERENCES items(owner, item_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: bids_userinfo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bids
    ADD CONSTRAINT bids_userinfo_fkey FOREIGN KEY (bidder) REFERENCES userinfo(email) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: borrows_items_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY borrows
    ADD CONSTRAINT borrows_items_fkey FOREIGN KEY (owner, item_id) REFERENCES borrows(owner, item_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: borrows_userinfo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY borrows
    ADD CONSTRAINT borrows_userinfo_fkey FOREIGN KEY (borrower) REFERENCES userinfo(email) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: items_owner_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY items
    ADD CONSTRAINT items_owner_fkey FOREIGN KEY (owner) REFERENCES userinfo(email) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


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

