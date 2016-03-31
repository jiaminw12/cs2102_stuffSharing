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

INSERT INTO items VALUES ('Hi48NvruplkXaAqgC4VSTgbHnPHSu0', 'aa@aa.com', 'TV Set', 'AC Power Adaptor (ACDP-160D01)
AC Power Cord
Batteries (R03)
Operating Instructions
Quick Setup Guide
Table Top Stand (Separate(assembly required))
Voice Remote Control (RMF-TX200P)', 'appliances', 100, 1, 'Tanjong Pagar', 'Bishan', '2016-05-28', '2016-07-09', '2016-04-30 19:19:19+08', 'ab90411d09c05ab6e0fd6417554fe645.jpg');
INSERT INTO items VALUES ('ys2NOYminZk6oAN5ysrtV5orUbqdFa', 'userb@userb.com', 'Arena 5.1', 'Immediately, the Arena 5.1 hits us with an exciting, dynamic performance that¡¯s powerful and immense. Explosions are delivered with authority and stacks of low-end rumble ¨C a credit to the sub. Sound placement is good too. When Tom Cruise¡¯s character, Cage, is dropped into the warzone for the first time, gunfire and the sound of helicopter blades fly around your listening position with natural fluidity.', 'appliances', 200, 1, 'Simei', 'Simei', '2016-05-07', '2016-06-04', '2016-04-30 11:08:14+08', '8a504f4e215b4633a41267a05f326799.jpg');
INSERT INTO items VALUES ('D0OwHvzPRQjmaerDmtyxBzTWdR81ki', 'userb@userb.com', 'ElectroX Vacuum Cleaner', 'Strong 2000W
Dust Capacity : 2L
Multi-Cyclone Separation System
HEPA filter to trap harmful airborne particles that causes respiratory allergies
Metal telescopic tube
Specially design dust bucket for easy and hygienic disposal
Accessories: Crevice Tool, Nozzle, Floor Brush', 'appliances', 0, 1, 'Bishan', 'Bishan', '2016-04-30', '2016-07-02', NULL, 'aacfd5050aae184ce0cd7f7ac4478282.jpg');
INSERT INTO items VALUES ('fGbwUzkt24ofUKYCCwoq4DOsgEN2KM', 'userb@userb.com', 'Origami: Beginner', 'Step By Step Tutorial', 'book', 50, 1, 'Clementi', 'Jurong', '2016-05-28', '2016-07-09', '2016-04-20 23:00:59+08', '4cb5ff5c4ce7df73a3f93fffe2e3e302.jpg');
INSERT INTO items VALUES ('CeOUtTYVoMjqIYTUBpZ2FQCGTtjdNX', 'aa@aa.com', 'Sony PS4', 'Built-in 1TB
Super-Speed USB (USB 3.0)', 'appliances', 100, 1, 'Bishan', 'Jurong East', '2016-05-07', '2016-06-04', '2016-04-30 20:52:13+08', '1be3f3c2f5117bc57e9b36eb59503b9f.jpg');
INSERT INTO items VALUES ('arJDJCTzbgKlNtx8GClrDFVQaIdf6U', 'aa@aa.com', 'Antique Chair Set', 'with 200 years', 'furniture', 0, 1, 'Bukit Batok', 'Bukit Batok', '2016-07-23', '2016-09-10', NULL, 'eb54f9d404646a334d4ab63aab62905e.jpg');
INSERT INTO items VALUES ('FTmzXSdKvmcbr6oc3kQIteJYunI6MU', 'aa@aa.com', 'Hand Driller', 'Quick New', 'tool', 0, 1, 'Ubi', 'Ubi', '2016-08-19', '2016-10-01', NULL, '09909df68e5374ae8051d79b76626d09.jpg');
INSERT INTO items VALUES ('cFERHehSoH1ERktBsuxqOC6DhIlpce', 'aa@aa.com', 'Jigsaw Puzzle', '5000 pieces', 'others', 0, 1, 'Yio Chu Kang', 'Yio Chu Kang', '2016-06-04', '2016-07-30', NULL, '716d93eda762c89457bd7540cb5a6f9a.JPG');
INSERT INTO items VALUES ('VhSvH7NyplOuFDXdiAUhYMy58lVBoG', 'userb@userb.com', 'Swing Chair', 'Rent for photo shooting', 'furniture', 250, 1, 'Bukit Timah', 'Bukit Timah', '2016-05-21', '2016-07-02', '2016-04-09 11:00:00+08', '1b424f4201989b7e816480437e9ec0e3.jpg');
INSERT INTO items VALUES ('AcTxT8SF0X0I6pLM5tguEtm3astAks', 'userb@userb.com', 'Black Dress', 'Low cut, branded', 'others', 150, 1, 'Orchard', 'Orchard', '2016-05-21', '2016-07-30', '2016-04-23 18:00:00+08', 'aeffdbb0e578bd4dc07e3abbb23299ac.jpg');
INSERT INTO items VALUES ('mGvjsLAD7ZrL1fq13Y8cipxaEGxfHS', 'userb@userb.com', 'Chainsaw', 'Easy to start, reduces the strength and effort needed to start the engine.', 'tool', 0, 1, 'Toa Payoh', 'Toa Payoh', '2016-05-14', '2016-06-04', NULL, '03a9da9abbc71a5fe6f9ab9cf6453ac9.jpg');
INSERT INTO items VALUES ('BaUyHHwVigLj7rHnDZGcJG5xRvtYYM', 'userb@userb.com', 'Red Wind Jacket', '', 'others', 90, 1, 'Tampines', 'Tampines', '2016-05-03', '2016-05-28', '2016-04-30 00:00:00+08', 'ef49c91a7506134487ab0f05b932517f.jpg');


--
-- Data for Name: userinfo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO userinfo VALUES ('a', 'aa@aa.com', 'aaa', 'c4ca4238a0b923820dcc509a6f75849b', '111111', 0, 1000);
INSERT INTO userinfo VALUES ('admin', 'admin@admin.com', 'adminlalal', 'c4ca4238a0b923820dcc509a6f75849b', '12345431', 0, 1000);
INSERT INTO userinfo VALUES ('userb', 'userb@userb.com', 'userb', 'c4ca4238a0b923820dcc509a6f75849b', '89876595', 0, 1000);


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

