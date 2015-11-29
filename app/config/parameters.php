<?php

// Using ClearDB for mysql database
$db = parse_url($_ENV['CLEARDB_DATABASE_URL']); 
$container->setParameter('database_driver', 'pdo_mysql'); 
$container->setParameter('database_host', $db['host']); 
$container->setParameter('database_port', '~');
$container->setParameter('database_name', trim($db['path'], '/')); 
$container->setParameter('database_user', $db['user']); 
$container->setParameter('database_password', $db['pass']);

// Using Mandrill to send email
$container->setParameter('mailer_transport', 'smtp');
$container->setParameter('mailer_host', null);
$container->setParameter('mailer_user', null);
$container->setParameter('mailer_password', null);

// Other settings
$container->setParameter('locale', 'en');
$container->setParameter('secret', 'b04be0948b8519bbc0a4b205db8b427671293e22');

?>