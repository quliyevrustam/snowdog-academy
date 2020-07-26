<?php

namespace Snowdog\Academy\Migration;

use Snowdog\Academy\Core\Database;
use Snowdog\Academy\Model\UserManager;

class Version2
{
    private Database $database;
    private UserManager $userManager;

    public function __construct(Database $database, UserManager $userManager)
    {
        $this->database = $database;
        $this->userManager = $userManager;
    }

    public function __invoke()
    {
        $this->createUsersTable();
        $this->addUsers();
    }

    private function createUsersTable(): void
    {
        $createQuery = <<<SQL
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(128) NOT NULL,
  `is_admin` boolean NOT NULL default 0,
  `is_active` boolean NOT NULL default 0,
  `user_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-child, 2-adult',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `FK_USER_TYPE` (`user_type`),
  CONSTRAINT `FK_USER_TYPE` FOREIGN KEY (`user_type`) REFERENCES `user_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

    private function addUsers(): void
    {
        $this->userManager->create('admin', 'admin', true, true);

        $this->userManager->create('baca', 'zaq12wsx', false, true);
        $this->userManager->create('maca', 'xsw23edc', false, true);
        $this->userManager->create('onuca', 'cde34rfv');
    }
}
