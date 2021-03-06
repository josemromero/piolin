drop table usuarios cascade;

create table usuarios (
    id              bigserial       constraint pk_usuarios primary key,
    usuario         varchar(20)     not null constraint uq_usuarios_usuario unique,
    pass            char(32)        not null,
    email           varchar(100)    not null constraint uq_usuarios_email unique
);

create index idx_usuarios_usuario_pass on usuarios (usuario, pass);

insert into usuarios (usuario, pass, email)
values ('Perico', md5('cont'), 'sdafsd@ds.com');
insert into usuarios (usuario, pass, email)
values ('Anika', md5('power'), 'fsdg@ds.com');
insert into usuarios (usuario, pass, email)
values ('gato', md5('maup'), 'dfkuvhb@ds.com');


drop table pios cascade;

create table pios (
    id              bigserial       constraint pk_pios primary key,
    pio             varchar(140)    not null,
    f_creacion      timestamp       default current_timestamp,
    usuarios_id     bigint          not null constraint fk_pios_usuarios references usuarios (id)
                                    on delete cascade on update cascade
);

create index idx_pios_usuarios_id on pios (usuarios_id);

insert into pios (pio, usuarios_id)
values ('Hola esto es prueba', 1);
insert into pios (pio, usuarios_id)
values ('Hola sdfgesto es prueba', 2);
insert into pios (pio, usuarios_id)
values ('Hola eafgasdfsfsto es prueba', 1);

create view pios_usuarios_v as
select u.id as id_usuario, usuario, email, p.id as pio_id, pio, to_char(f_creacion, 'DD-Mon-YY HH24:MI') as fecha
from usuarios u join pios p
on u.id = p.usuarios_id;


drop table relaciones cascade;

create table relaciones (
    seguidor_id     bigint          constraint fk_seguidor_usuarios references usuarios (id)
                                    on delete cascade on update cascade,
    seguido_id      bigint          constraint fk_seguido_usuarios references usuarios (id)
                                    on delete cascade on update cascade,
    desde           timestamp       default current_timestamp,
    constraint      pk_relaciones   primary key (seguidor_id, seguido_id)
);

create index idx_relaciones_seguido_id on relaciones (seguido_id);

insert into relaciones (seguidor_id, seguido_id)
values (2, 1);
insert into relaciones (seguidor_id, seguido_id)
values (3, 1);
insert into relaciones (seguidor_id, seguido_id)
values (1, 2);
insert into relaciones (seguidor_id, seguido_id)
values (1, 3);

create view seguidos_v as
select seguidor_id, seguido_id, to_char(desde, 'DD-Mon-YY HH24:MI') as fecha_seg, pio, to_char(f_creacion, 'DD-Mon-YY HH24:MI') as fecha_cre, u.id as id_user, u.usuario as nombre
from relaciones r join usuarios u
on r.seguido_id = u.id
join pios p
on u.id = p.usuarios_id
order by fecha_cre;

-- Ejemplo de union de tablas para obtener pios
-- de usuario y seguidos
create view pios_tablon_v as
select id_usuario, usuario, pio, fecha from pios_usuarios_v
union
select seguido_id, nombre, pio, fecha_cre from seguidos_v
order by fecha;






