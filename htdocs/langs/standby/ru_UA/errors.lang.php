<?php
/* Copyright (C) 2012	Regis Houssin	<regis.houssin@capnetworks.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

$errors = array(
		'CHARSET' => 'UTF-8',
		'ErrorForbidden' => 'Отказано в доступе. <br> Вы пытаетесь получить доступ к странице, района или функцию, не будучи в авторизованной сессии, или которые не разрешают своим пользователем.',
		'ErrorForbidden2' => 'Разрешение на это Логин может быть определена вашим Dolibarr администратора из меню %s-> %s.',
		'ErrorForbidden3' => 'Кажется, что Dolibarr не используется по авторизованной сессии. Взгляните на документацию установки Dolibarr знать, как управлять аутентификацией (htaccess, mod_auth или другие ...).',
		'ErrorNoImagickReadimage' => 'Класс Imagick не найден в этом PHP. Нет предварительного просмотра могут быть недоступны. Администраторы могут отключить эту вкладку из меню Setup - Display.',
		'ErrorRecordAlreadyExists' => 'Запись уже существует',
		'ErrorCantReadFile' => 'Не удалось прочитать файл %s',
		'ErrorCantReadDir' => 'Не удалось прочитать %s каталог',
		'ErrorFailedToFindEntity' => 'Не удалось прочитать %s окружающая среда',
		'ErrorBadLoginPassword' => 'Плохо значение Войти или пароль',
		'ErrorLoginDisabled' => 'Ваша учетная запись отключена',
);
?>