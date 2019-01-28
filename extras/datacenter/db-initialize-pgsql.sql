-- CREATE USER "airpi" PASSWORD '****';
-- CREATE DATABASE airpi OWNER airpi ENCODING 'UTF8';

CREATE TABLE stations2 (
    id       SERIAL PRIMARY KEY,
    name     VARCHAR UNIQUE NOT NULL,
    login    VARCHAR UNIQUE NOT NULL,
    password VARCHAR NOT NULL,
    lat      DOUBLE PRECISION,
    lon      DOUBLE PRECISION
);

CREATE TABLE data (
    station_id INTEGER NOT NULL,
    time_stamp TIMESTAMP NOT NULL,
    type       VARCHAR NOT NULL,
    value      FLOAT
);
ALTER TABLE data ADD CONSTRAINT data_station_id_fkey FOREIGN KEY (station_id) REFERENCES stations(id);
CREATE UNIQUE INDEX data_time_stamp_type_idx ON data (station_id, time_stamp, type);
CREATE INDEX data_time_stamp_idx ON data (time_stamp);
CREATE INDEX data_type_idx ON data (type);
