<?php

use App\Models\Categorias;

require_once ('..\app\Models\Departamentos.php');




/*
5,ANTIOQUIA,Eje Cafetero - Antioquia,Activo
8,ATLÁNTICO,Caribe,Activo
11,BOGOTÁ. D.C,Centro Oriente,Activo
13,BOLÍVAR,Caribe,Activo
15,BOYACÁ,Centro Oriente,Activo
17,CALDAS,Eje Cafetero - Antioquia,Activo
18,CAQUETÁ,Centro Sur,Activo
19,CAUCA,Pacífico,Activo
20,CESAR,Caribe,Activo
23,CÓRDOBA,Caribe,Activo
25,CUNDINAMARCA,Centro Oriente,Activo
27,CHOCÓ,Pacífico,Activo
41,HUILA,Centro Sur,Activo
44,LA GUAJIRA,Caribe,Activo
47,MAGDALENA,Caribe,Activo
50,META,Llano,Activo
52,NARIÑO,Pacífico,Activo
54,NORTE DE SANTANDER,Centro Oriente,Activo
63,QUINDIO,Eje Cafetero - Antioquia,Activo
66,RISARALDA,Eje Cafetero - Antioquia,Activo
68,SANTANDER,Centro Oriente,Activo
70,SUCRE,Caribe,Activo
73,TOLIMA,Centro Sur,Activo
76,VALLE DEL CAUCA,Pacífico,Activo
81,ARAUCA,Llano,Activo
85,CASANARE,Llano,Activo
86,PUTUMAYO,Centro Sur,Activo
88,"ARCHIPIÉLAGO DE SAN ANDRÉS, PROVIDENCIA Y SANTA CATALINA",Caribe,Activo
91,AMAZONAS,Centro Sur,Activo
94,GUAINÁ,Llano,Activo
95,GUAVIARE,Llano,Activo
97,VAUPÉS,Llano,Activo
99,VICHADA,Llano,Activo
*/



//5,ANTIOQUIA,Eje Cafetero - Antioquia,Activo
$insert = new Categorias([ "id" => 1, "nombre" => "ANTIOQUIA", "region" => "Eje Cafetero - Antioquia", "estado" => "Activo" ]);
$insert->save();

//8,ATLÁNTICO,Caribe,Activo
$insert = new Categorias([ "id" => 2, "nombre" => "ATLÁNTICO", "region" => "Caribe", "estado" => "Activo" ]);
$insert->save();

//11,BOGOTÁ. D.C,Centro Oriente,Activo
$insert = new Categorias([ "id" => 3, "nombre" => "BOGOTÁ. D.C", "region" => "Centro Oriente", "estado" => "Activo" ]);
$insert->save();

//13,BOLÍVAR,Caribe,Activo
$insert = new Categorias([ "id" => 4, "nombre" => "BOLÍVAR", "region" => "Caribe", "estado" => "Activo" ]);
$insert->save();

//15,BOYACÁ,Centro Oriente,Activo
$insert = new Categorias([ "id" => 5, "nombre" => "BOYACÁ", "region" => "Centro Oriente", "estado" => "Activo" ]);
$insert->save();

//17,CALDAS,Eje Cafetero - Antioquia,Activo
$insert = new Categorias([ "id" => 6, "nombre" => "CALDAS", "region" => "Eje Cafetero - Antioquia", "estado" => "Activo" ]);
$insert->save();

//18,CAQUETÁ,Centro Sur,Activo
$insert = new Categorias([ "id" => 7, "nombre" => "CAQUETÁ", "region" => "Centro Sur", "estado" => "Activo" ]);
$insert->save();

//19,CAUCA,Pacífico,Activo
$insert = new Categorias([ "id" => 8, "nombre" => "CAUCA", "region" => "Pacífico", "estado" => "Activo" ]);
$insert->save();

//20,CESAR,Caribe,Activo
$insert = new Categorias([ "id" => 9, "nombre" => "CESAR", "region" => "Caribe", "estado" => "Activo" ]);
$insert->save();

//23,CÓRDOBA,Caribe,Activo
$insert = new Categorias([ "id" => 10, "nombre" => "CÓRDOBA", "region" => "Caribe", "estado" => "Activo" ]);
$insert->save();

//25,CUNDINAMARCA,Centro Oriente,Activo
$insert = new Categorias([ "id" => 11, "nombre" => "CUNDINAMARCA", "region" => "Centro Oriente", "estado" => "Activo" ]);
$insert->save();

//27,CHOCÓ,Pacífico,Activo
$insert = new Categorias([ "id" => 12, "nombre" => "CHOCÓ", "region" => "Pacífico", "estado" => "Activo" ]);
$insert->save();

//41,HUILA,Centro Sur,Activo
$insert = new Categorias([ "id" => 13, "nombre" => "HUILA", "region" => "Centro Sur", "estado" => "Activo" ]);
$insert->save();

//44,LA GUAJIRA,Caribe,Activo
$insert = new Categorias([ "id" => 14, "nombre" => "LA GUAJIRA", "region" => "Caribe", "estado" => "Activo" ]);
$insert->save();

//47,MAGDALENA,Caribe,Activo
$insert = new Categorias([ "id" => 15, "nombre" => "MAGDALENA", "region" => "Caribe", "estado" => "Activo" ]);
$insert->save();

//50,META,Llano,Activo
$insert = new Categorias([ "id" => 16, "nombre" => "META", "region" => "Llano", "estado" => "Activo" ]);
$insert->save();

//52,NARIÑO,Pacífico,Activo
$insert = new Categorias([ "id" => 17, "nombre" => "NARIÑO", "region" => "Pacífico", "estado" => "Activo" ]);
$insert->save();

//54,NORTE DE SANTANDER,Centro Oriente,Activo
$insert = new Categorias([ "id" => 18, "nombre" => "NORTE DE SANTANDER", "region" => "Centro Oriente", "estado" => "Activo" ]);
$insert->save();

//63,QUINDIO,Eje Cafetero - Antioquia,Activo
$insert = new Categorias([ "id" => 19, "nombre" => "QUINDIO", "region" => "Eje Cafetero - Antioquia", "estado" => "Activo" ]);
$insert->save();

//66,RISARALDA,Eje Cafetero - Antioquia,Activo
$insert = new Categorias([ "id" => 20, "nombre" => "RISARALDA", "region" => "Eje Cafetero - Antioquia", "estado" => "Activo" ]);
$insert->save();

//68,SANTANDER,Centro Oriente,Activo
$insert = new Categorias([ "id" => 21, "nombre" => "SANTANDER", "region" => "Centro Oriente", "estado" => "Activo" ]);
$insert->save();

//70,SUCRE,Caribe,Activo
$insert = new Categorias([ "id" => 22, "nombre" => "SUCRE", "region" => "Caribe", "estado" => "Activo" ]);
$insert->save();

//73,TOLIMA,Centro Sur,Activo
$insert = new Categorias([ "id" => 23, "nombre" => "TOLIMA", "region" => "Centro Sur", "estado" => "Activo" ]);
$insert->save();

//76,VALLE DEL CAUCA,Pacífico,Activo
$insert = new Categorias([ "id" => 24, "nombre" => "VALLE DEL CAUCA", "region" => "Pacífico", "estado" => "Activo" ]);
$insert->save();

//81,ARAUCA,Llano,Activo
$insert = new Categorias([ "id" => 25, "nombre" => "ARAUCA", "region" => "Llano", "estado" => "Activo" ]);
$insert->save();

//85,CASANARE,Llano,Activo
$insert = new Categorias([ "id" => 26, "nombre" => "CASANARE", "region" => "Llano", "estado" => "Activo" ]);
$insert->save();

//86,PUTUMAYO,Centro Sur,Activo
$insert = new Categorias([ "id" => 27, "nombre" => "PUTUMAYO", "region" => "Centro Sur", "estado" => "Activo" ]);
$insert->save();

//88,"ARCHIPIÉLAGO DE SAN ANDRÉS, PROVIDENCIA Y SANTA CATALINA",Caribe,Activo
$insert = new Categorias([ "id" => 28, "nombre" => "ARCHIPIÉLAGO DE SAN ANDRÉS, PROVIDENCIA Y SANTA CATALINA", "region" => "Caribe", "estado" => "Activo" ]);
$insert->save();

//91,AMAZONAS,Centro Sur,Activo
$insert = new Categorias([ "id" => 29, "nombre" => "AMAZONAS", "region" => "Centro Sur", "estado" => "Activo" ]);
$insert->save();

//94,GUAINÁ,Llano,Activo
$insert = new Categorias([ "id" => 30, "nombre" => "GUAINÁ", "region" => "Llano", "estado" => "Activo" ]);
$insert->save();

//95,GUAVIARE,Llano,Activo
$insert = new Categorias([ "id" => 31, "nombre" => "GUAVIARE", "region" => "Llano", "estado" => "Activo" ]);
$insert->save();

//97,VAUPÉS,Llano,Activo
$insert = new Categorias([ "id" => 32, "nombre" => "VAUPÉS", "region" => "Llano", "estado" => "Activo" ]);
$insert->save();

//99,VICHADA,Llano,Activo
$insert = new Categorias([ "id" => 33, "nombre" => "VICHADA", "region" => "Llano", "estado" => "Activo" ]);
$insert->save();