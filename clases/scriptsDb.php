<?php
// Scrips para la creacion de la base de datos.

/*
    CREATE TABLE parqueaw.menu(
        id decimal(5, 0) GENERATED ALWAYS AS IDENTITY ( START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE NO CYCLE NO ORDER CACHE 20 ),
        archivo varchar(45),
        nombre varchar(45),
        tipo char(1),
        criterio varchar(45),
        orden decimal(2,0),
        estado char(1),
        usuario varchar(15),
        fecha decimal(8,0),
        hora decimal(6,0),
        equipo varchar(20),
        dirip varchar(20)
    );

   INSERT INTO menu (archivo, nombre, tipo, criterio, orden, estado, usuario, fecha, hora, equipo, dirip) VALUES
   ('menu', 'Menu', 'P', '0', 1, 'v', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
   ('permiso', 'Permisos', 'P', '0', 2, 'v', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
   ('vehiculo', 'Vehiculo', 'P', '0', 3, 'v', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
   ('parqueadero', 'Parqueaderos', 'A', '0', 4, 'v', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
   ('tipovehi', 'Tipo de vehiculo', 'P', '0', 5, 'v', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
   ('estavehi', 'Estado del vehiculo', 'P', '0', 5, 'v', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126');


    CREATE TABLE parqueaw.motivo(
        id decimal(5, 0) GENERATED ALWAYS AS IDENTITY ( START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE NO CYCLE NO ORDER CACHE 20 ),
        tabla varchar(45),
        nombre varchar(45),
        criterio varchar(45),
        estado char(1),
        usuario varchar(15),
        fecha decimal(8,0),
        hora decimal(6,0),
        equipo varchar(20),
        dirip varchar(20)
    );

INSERT INTO motivo (tabla, nombre, criterio, estado, usuario, fecha, hora, equipo, dirip) VALUES
('menu', 'Parametros', 'P', 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126');
('menu', 'Inactivo', 'A', 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126');


CREATE TABLE parqueaw.permiso(
   id decimal(5, 0) GENERATED ALWAYS AS IDENTITY ( START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE NO CYCLE NO ORDER CACHE 20 ),
   login varchar(15) NOT NULL,
   menu decimal(5, 0) NOT NULL,
   estado char(1) NOT NULL,
   usuario varchar(15) NOT NULL,
   fecha decimal(8,0) NOT NULL,
   hora decimal(6,0) NOT NULL,
   equipo varchar(20) NOT NULL,
   dirip varchar(20) NOT NULL
);

INSERT INTO permiso (login, menu, estado, usuario, fecha, hora, equipo, dirip) VALUES
('egutierrez', 2, 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126');,
('lhosorio', 2, 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
('lhosorio', 3, 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
('lhosorio', 4, 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
('lhosorio', 5, 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
('lhosorio', 6, 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
('lhosorio', 7, 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126');

CREATE TABLE parqueaw.usuario(
   id decimal(5, 0) GENERATED ALWAYS AS IDENTITY ( START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE NO CYCLE NO ORDER CACHE 20 ),
   login varchar(15),
   canumdocum varchar(15) NOT NULL,
   estado char(1),
   usuario varchar(15),
   fecha decimal(8,0),
   hora decimal(6,0),
   equipo varchar(20),
   dirip varchar(20)
);

INSERT INTO usuario (login, canumdocum, estado, usuario, fecha, hora, equipo, dirip) VALUES
('lhosorio', '94528383', 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126');


CREATE TABLE parqueaw.parqueadero(
   id decimal(5, 0) GENERATED ALWAYS AS IDENTITY ( START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE NO CYCLE NO ORDER CACHE 20 ),
   nombre varchar(45) NOT NULL,
   cupocar decimal(5, 0) NOT NULL,
   cupomot decimal(5, 0) NOT NULL,
   ascodarea char(1) NOT NULL,
   tiempo decimal(2, 0) NOT NULL,
   estado char(1) NOT NULL,
   usuario varchar(15) NOT NULL,
   fecha decimal(8,0) NOT NULL,
   hora decimal(6,0) NOT NULL,
   equipo varchar(20) NOT NULL,
   dirip varchar(20) NOT NULL
);

insert into parqueadero (nombre, cupo, tiempo, estado, usuario, fecha, hora, equipo, dirip) 
values ('Circunvalar', '95', '15', 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126');

CREATE TABLE parqueaw.parquehora(
   id decimal(5, 0) GENERATED ALWAYS AS IDENTITY ( START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE NO CYCLE NO ORDER CACHE 20 ),
   parqueadero decimal(5, 0) NOT NULL,
   dia decimal(1, 0) NOT NULL,
   inicio varchar(8) NOT NULL,
   fin varchar(8) NOT NULL,
   estado char(1) NOT NULL,
   usuario varchar(15) NOT NULL,
   fecha decimal(8,0) NOT NULL,
   hora decimal(6,0) NOT NULL,
   equipo varchar(20) NOT NULL,
   dirip varchar(20) NOT NULL
);

insert into parquehora (parqueadero, dia, inicio, fin, estado, usuario, fecha, hora, equipo, dirip) 
values ('1', '1', '08:00:00', '19:30:00', 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126');


CREATE TABLE parqueaw.vehiculos(
   placa varchar(10) NOT NULL,
   nombre varchar(45) NOT NULL,
   tipousua varchar(2) NOT NULL,
   tipovehi char(1) NOT NULL,
   canumdocum varchar(15) NOT NULL,
   color varchar(6) NOT NULL,
   estavehi varchar(2) NOT NULL,
   estado char(1) NOT NULL,
   usuario varchar(15) NOT NULL,
   fecha decimal(8,0) NOT NULL,
   hora decimal(6,0) NOT NULL,
   equipo varchar(20) NOT NULL,
   dirip varchar(20) NOT NULL
);

insert into vehiculos (placa, nombre, tipousua, tipovehi, canumdocum, color, estavehi, estado, usuario, fecha, hora, equipo, dirip) values 
('DBT28C', '', 'E', 'M', '94528383', 'FF0000', '10', 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126'),
('DBT28D', 'IBM', 'T', 'M', '94528382', 'FFFF00', '20', 'A', 'lhosorio', '20140708', '070000', 'CirSisLho', '10.25.35.126');

CREATE TABLE parqueaw.invehiculos(
   id decimal(5, 0) GENERATED ALWAYS AS IDENTITY ( START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE NO CYCLE NO ORDER CACHE 20 ),
   placa varchar(10) NOT NULL,
   nombre varchar(45) NOT NULL,
   tipousua varchar(2) NOT NULL,
   tipovehi char(1) NOT NULL,
   canumdocum varchar(15) NOT NULL,
   color varchar(6) NOT NULL,
   estavehi varchar(2) NOT NULL,
   estado char(1) NOT NULL,
   usuario varchar(15) NOT NULL,
   fecha decimal(8,0) NOT NULL,
   hora decimal(6,0) NOT NULL,
   equipo varchar(20) NOT NULL,
   dirip varchar(20) NOT NULL
);


CREATE TABLE parqueaw.movimiento(
   id decimal(5, 0) GENERATED ALWAYS AS IDENTITY ( START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE NO CYCLE NO ORDER CACHE 20 ),
   parqueadero decimal(5, 0) NOT NULL,
   placa varchar(10) NOT NULL,
   mrcodcons DECIMAL(10, 0) NOT NULL,
   nombre varchar(45) NOT NULL,
   tipousua varchar(2) NOT NULL,
   tipovehi char(1) NOT NULL,
   estavehi varchar(2) NOT NULL,
   fecing decimal(8, 0) NOT NULL,
   horing decimal(6, 0) NOT NULL,
   fecfac decimal(8, 0) NOT NULL,
   horfac decimal(6, 0) NOT NULL,
   fecsal decimal(8, 0) NOT NULL,
   horsal decimal(6, 0) NOT NULL,
   durdia decimal(3, 0) NOT NULL,
   durhor decimal(6, 0) NOT NULL,
   estado char(1) NOT NULL,
   usuario varchar(15) NOT NULL,
   fecha decimal(8,0) NOT NULL,
   hora decimal(6,0) NOT NULL,
   equipo varchar(20) NOT NULL,
   dirip varchar(20) NOT NULL
);

CREATE TABLE parqueaw.parusu(
   id decimal(5, 0) GENERATED ALWAYS AS IDENTITY ( START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE NO CYCLE NO ORDER CACHE 20 ),
   parqueadero decimal(5, 0) NOT NULL,
   login varchar(15) NOT NULL,
   estado char(1) NOT NULL,
   usuario varchar(15) NOT NULL,
   fecha decimal(8,0) NOT NULL,
   hora decimal(6,0) NOT NULL,
   equipo varchar(20) NOT NULL,
   dirip varchar(20) NOT NULL
);




*/
?>
