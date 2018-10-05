Create database if not exists tienda character set 'utf8' collate 'utf8_unicode_ci';
Use tienda;

Create table clientes(
	clienteId int auto_increment,
	nombre varchar(50), 
	direccion varchar(50), 
	cp varchar(5), 
	email varchar(50), 
	tlfno int(9), 
	tipoTarjeta enum("debito", "credito", "prepago", "revolving"), 
	numTarjeta bigint(16), 
	fechaCaducidad date, 
	primary key(clienteId, email)
)engine=innodb;

Create table productos(
	productoId int auto_increment,
	nombre varchar(50),
	descripcion varchar(100),
	precio double(19,4), 
	iva int(2),
	imagen varchar(100),
	stock int,
	primary key(productoId, nombre)
)engine=innodb;

Create table cesta(
	clienteId int primary key,
	fechaCreacion date,
	tipoEnvio enum("correo", "mensajero"),
	medioPago enum("tarjeta", "paypal", "transferencia"),
	constraint fkCliente_cesta foreign key(clienteId) references clientes(clienteId)
		on delete cascade on update cascade
)engine=innodb;

Create table itemCesta(
	itemCestaId int auto_increment,
	clienteId int,
	productoId int,
	cantidad int,
	primary key(itemCestaId, clienteId, productoId),
	constraint clientes_fk foreign key(clienteId) references clientes(clienteId)
		on delete cascade on update cascade,
	constraint productos_fk foreign key(productoId) references productos(productoId)
		on delete cascade on update cascade
)engine=innodb;

Create table pedidos(
	pedidoId int auto_increment,
	clienteId int, 
	fechapedido date, 
	medioPago enum("tarjeta", "paypal", "transferencia"),
	tipoEnvio enum("correo", "mensajero"),
	totalPedido double (19,4), 
	totalIva double (19,4), 
	fechaEnvio date,
	primary key (pedidoId),
	constraint pedidos_fk foreign key(clienteId) references clientes(clienteId)
		on delete cascade on update cascade
)engine=innodb;

Create table itemPedido(
	itemPedidoId int auto_increment,
	pedidoId int, 
	productoId int, 
	unidades int, 
	precio double (19,4), 
	iva double (19,4), 
	primary key (itemPedidoId, pedidoId, productoId),
	constraint pedidoId_fk foreign key(pedidoId) references pedidos(pedidoId)
		on delete cascade on update cascade,
	constraint productoId_fk foreign key(productoId) references productos(productoId)
		on delete cascade on update cascade
)engine=innodb;

Create table credenciales(
	clienteId int auto_increment primary key,
	email varchar(50), 
	password varchar(12),
	constraint credencialesId_fk foreign key(clienteId) references clientes(clienteId)
		on delete cascade on update cascade
)engine=innodb;

Insert into productos value (1, "Ositos haribo", "Bolsa que contiene ositos haribo", 1.75, 21, "ositos_haribo.jpg", 248);
Insert into productos value (2, "Regaliz fresa y nata", "Caja que contiene regalices de fresa y nata", 2.00, 21, "regaliz.jpg", 100);
Insert into productos value (3, "Peta zetas", "Caja contiene paquetes de contenido pica pica", 1.5, 21, "peta_zetas.png", 150);
Insert into productos value (4, "Piruletas fiesta", "Caja que contiene piruletas en forma de corazón", 1.75, 21, "piruletas_fiesta.jpg", 200);
Insert into productos value (5, "Regaliz picante", "Caja que contiene regalices de fresa picantes", 2.85, 21, "regaliz_picante.jpg", 250);
Insert into productos value (6, "Chupa-chups fiesta", "Es un chupa-chups con la diferencia que dentro de este contiene chicle", 1.75, 21, "chupachups_fiesta.jpg", 75);
Insert into productos value (7, "Lenguas", "Caja que contiene lenguas clásicas", 1.25, 21, "lenguas.jpg", 60);
Insert into productos value (8, "Ladrillos de regaliz", "Caja que contiene ladrillos de regalices con pica-pica", 2.50, 21, "ladrillos_picantes.jpg", 96);
Insert into productos value (9, "Coca-colas", "Caja que contiene coca-colas", 1.75, 21, "cocacolas.jpg", 175);
Insert into productos value (10, "Sandias", "Caja que contiene sandias de chuherias", 1.50, 21, "sandias.jpg", 84);
Insert into productos value (11, "Sandias chicles", "Caja que contie sandias de chiclees", 1.50, 21, "sandias_chicle.jpg", 21);
Insert into productos value (12, "Nubes", "Caja que contiene nubes", 1.50, 21, "nubes.jpg", 69);
Insert into productos value (13, "Besitos gominolas", "Caja que contiene besitos", 1.95, 21, "besitos.jpg", 125);
Insert into productos value (14, "Moras rojas", "Caja que contiene moras rojas de gominolas", 3.05, 21, "moras.jpg", 264);
Insert into productos value (15, "Moras negras", "Caja que contiene moras negras de gominolas", 1.60, 21, "moras.jpg", 100);
Insert into productos value (16, "Fresas", "Caja que contiene fresas", 1.71, 21, "fresas.jpg", 130);
Insert into productos value (17, "Cerebros gominolas", "Caja que contiene cerebros de gelatina", 4.23, 21, "cerebros.jpg", 98);
Insert into productos value (18, "Huevos", "Caja que contiene huevos", 1.30, 21, "huevos.jpg", 89);
Insert into productos value (19, "Regaliz sandiz", "Caja que contiene regalices sabor sandia", 2.85, 21, "regaliz_sandia.jpg", 85);
Insert into productos value (20, "Cerezas", "Caja que contiene cerezas", 1.45, 21, "cerezas.jpg", 135);