<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateUsers extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/5/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('users');

        $table
            ->addColumn('name', 'string', [
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('status', 'string', [
                'limit' => 20,
                'default' => 'active', 
                // active | inactive | suspended
            ])
            ->addColumn('role', 'string', [
                'limit' => 50,
                'default' => 'user',
                // user | admin | manager (future-proof)
            ])
            ->addTimestamps() // created + modified
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
