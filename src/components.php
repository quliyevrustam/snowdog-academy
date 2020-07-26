<?php

use Snowdog\Academy\Command\CreateAdmin;
use Snowdog\Academy\Command\Migrate;
use Snowdog\Academy\Command\TestDbConnection;
use Snowdog\Academy\Component\Menu;
use Snowdog\Academy\Component\Migrations;
use Snowdog\Academy\Controller\Admin;
use Snowdog\Academy\Controller\Book;
use Snowdog\Academy\Controller\BookList;
use Snowdog\Academy\Controller\Index;
use Snowdog\Academy\Controller\Login;
use Snowdog\Academy\Controller\MyBooksList;
use Snowdog\Academy\Controller\Register;
use Snowdog\Academy\Menu\ActiveUsersMenu;
use Snowdog\Academy\Menu\InactiveUsersMenu;
use Snowdog\Academy\Menu\BorrowedBooksMenu;
use Snowdog\Academy\Menu\LoginMenu;
use Snowdog\Academy\Menu\LogoutMenu;
use Snowdog\Academy\Menu\MyBooksMenu;
use Snowdog\Academy\Menu\RegisterMenu;
use Snowdog\Academy\Repository\CommandRepository;
use Snowdog\Academy\Repository\RouteRepository;

RouteRepository::registerRoute('GET', '/', Index::class, 'index');
RouteRepository::registerRoute('GET', '/login', Login::class, 'index');
RouteRepository::registerRoute('POST', '/login', Login::class, 'login');
RouteRepository::registerRoute('GET', '/logout', Login::class, 'logout');
RouteRepository::registerRoute('GET', '/register', Register::class, 'index');
RouteRepository::registerRoute('POST', '/register', Register::class, 'register');
RouteRepository::registerRoute('GET', '/books', BookList::class, 'index');
RouteRepository::registerRoute('GET', '/books/borrow/{id:\d+}', Book::class, 'borrow');
RouteRepository::registerRoute('GET', '/books/return/{id:\d+}', Book::class, 'return');
RouteRepository::registerRoute('GET', '/my_books', MyBooksList::class, 'index');
RouteRepository::registerRoute('GET', '/admin', Admin\Books::class, 'index');
RouteRepository::registerRoute('GET', '/admin/new_book', Admin\Books::class, 'newBook');
RouteRepository::registerRoute('POST', '/admin/new_book', Admin\Books::class, 'newBookPost');
RouteRepository::registerRoute('GET', '/admin/edit_book/{id:\d+}', Admin\Books::class, 'edit');
RouteRepository::registerRoute('POST', '/admin/edit_book/{id:\d+}', Admin\Books::class, 'editPost');
RouteRepository::registerRoute('GET', '/admin/user/list/{isActive:\d+}', Admin\User::class, 'list');
RouteRepository::registerRoute('GET', '/admin/user/activate/{id:\d+}', Admin\User::class, 'activate');

/* Import Books From CSV File */
// View "Import Books From CSV" Page
RouteRepository::registerRoute('GET', '/admin/import_from_csv', Admin\Books::class, 'importFromCsv');

// Import Books From CSV
RouteRepository::registerRoute('POST', '/admin/import_from_csv', Admin\Books::class, 'importFromCsvPost');

/* Import Books From CSV File */
// View "Borrowed Book List" Page
RouteRepository::registerRoute('GET', '/admin/borrowed_books', Admin\Books::class, 'borrowedBooks');

// Filtered Borrowed Books
RouteRepository::registerRoute('POST', '/admin/borrowed_books', Admin\Books::class, 'borrowedBooksPost');

/* Restore Book Details by ISBN */
RouteRepository::registerRoute('POST', '/admin/restore_by_isbn/{id:\d+}', Admin\Books::class, 'restoreByIsbn');

/* Change User "User Type" */
RouteRepository::registerRoute('GET', '/admin/user/edit/{id:\d+}/user_type/{type:\d+}', Admin\User::class, 'editUserType');

Menu::register(LoginMenu::class, 100);
Menu::register(RegisterMenu::class, 200);
Menu::register(ActiveUsersMenu::class, 300);
Menu::register(InactiveUsersMenu::class, 400);
Menu::register(MyBooksMenu::class, 500);
Menu::register(BorrowedBooksMenu::class, 600);
Menu::register(LogoutMenu::class, 900);

CommandRepository::registerCommand('test_db_connection', TestDbConnection::class, 'Tests database connection');
CommandRepository::registerCommand('migrate_db', Migrate::class, 'Performs database migration');
CommandRepository::registerCommand('create_admin', CreateAdmin::class, 'Create new admin user');

Migrations::registerComponentMigration('Snowdog\\Academy', 5);
