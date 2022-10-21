# Buscaminas servicio web<br>
<h2>Manual de uso</h2>
<h3>Creacion de usuario:</h3><br>
POST de JSON con los datos del usuario<br><br>

>{<br>
>  &emsp;"id" = 777,<br>
>  &emsp;"name" = "username",<br>
>  &emsp;"password" = 777,<br>
>  &emsp;"mail" = mail@example.com<br>
>}<br><br>

<h3>Creacion de tablero:</h3><br>
GET con JSON de los datos de usuario + tamaño y numero de minas en la URI<br><br>

>URI = {ServerIP}:{PORT}/[SIZE]/[MINES]<br><br>
>{<br>
>  &emsp;"id" = 777,<br>
>  &emsp;"name" = "username",<br>
>  &emsp;"password" = 777<br>
>}<br><br>

<h3>Desatapar casilla:</h3><br>
GET con JSON de los datos de usuario + posicion a destapar en la URI<br><br>

>URI = {ServerIP}:{PORT}/[POSITION]<br><br>
>{<br>
>  &emsp;"id" = 777,<br>
>  &emsp;"name" = "username",<br>
>  &emsp;"password" = 777<br>
>}<br><br>

<h3>Eliminar usuario:</h3><br>
DELETE con JSON de los datos de usuario a borrar<br><br>

>{<br>
>  &emsp;"id" = 777,<br>
>  &emsp;"name" = "username",<br>
>  &emsp;"password" = 777<br>
>}<br><br>

<h3>Cambiar password:</h3><br>
PUT con JSON de los datos de usuario y la nueva password + ID del usuario en la URI<br><br>

>URI = {ServerIP}:{PORT}/[ID]<br><br>
>{<br>
>  &emsp;"name" = "username",<br>
>  &emsp;"password" = 777,<br>
>  &emsp;"newpassword" = 888<br>
>}<br><br>

<h6>Es posible que el correo que manda el servicio sea detectado como spam</h6>
