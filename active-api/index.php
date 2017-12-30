<?php

require '../Source/Models/MailChimp.php';

use Source\Models\MailChimp AS Active;

$active = new Active;

$active->getByEmail("claudio@jdyc.com.br");
var_dump($active->getCallback());

$active->addActive("Joao", "Carlos", "Joao Carlos@gmail.com", "ff889e1e2f");
var_dump($active->getCallback());
