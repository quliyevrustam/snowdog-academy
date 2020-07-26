<?php

namespace Snowdog\Academy\Migration;

use Snowdog\Academy\Core\Database;
use Snowdog\Academy\Model\UserTypeManager;

class Version1
{
    private Database $database;
    private UserTypeManager $userTypeManager;

    public function __construct(Database $database, UserTypeManager $userTypeManager)
    {
        $this->database = $database;
        $this->userTypeManager = $userTypeManager;
    }

    public function __invoke()
    {
        $this->createUserTypeTable();
        $this->addUserTypes();
    }

    private function createUserTypeTable(): void
    {
        $createQuery = <<<SQL
CREATE TABLE `user_type` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

    private function addUserTypes(): void
    {
        $this->userTypeManager->create(1, 'Child');
        $this->userTypeManager->create(2, 'Adult');
    }
}
