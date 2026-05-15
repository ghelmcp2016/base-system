<?php
declare(strict_types=1);

namespace App\Controller\API;

use App\Model\Entity\User;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function index()
    {
        $this->request->allowMethod(['get']);

        $users = $this->fetchTable('Users')
            ->find()
            ->orderBy(['Users.created' => 'DESC'])
            ->all()
            ->map(fn (User $user) => $this->serializeUser($user))
            ->toList();

        return $this->jsonResponse([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function view(string $id)
    {
        $this->request->allowMethod(['get']);

        $user = $this->fetchTable('Users')->get($id);

        return $this->jsonResponse([
            'success' => true,
            'data' => $this->serializeUser($user),
        ]);
    }

    public function add()
    {
        $this->request->allowMethod(['post']);

        $usersTable = $this->fetchTable('Users');
        $payload = (array)$this->request->getData();
        $payload['status'] = $payload['status'] ?? 'active';
        $payload['role'] = $payload['role'] ?? 'user';

        $user = $usersTable->newEmptyEntity();
        $user = $usersTable->patchEntity($user, $payload);

        if ($user->getErrors()) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Please fix the highlighted validation errors.',
                'errors' => $user->getErrors(),
            ], 422);
        }

        if (!$usersTable->save($user)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Unable to create the user.',
                'errors' => $user->getErrors(),
            ], 422);
        }

        return $this->jsonResponse([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $this->serializeUser($user),
        ], 201);
    }

    public function edit(string $id)
    {
        $this->request->allowMethod(['post', 'put', 'patch']);

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);
        $payload = (array)$this->request->getData();

        if (array_key_exists('password', $payload) && trim((string)$payload['password']) === '') {
            unset($payload['password']);
        }

        $user = $usersTable->patchEntity($user, $payload);

        if ($user->getErrors()) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Please fix the highlighted validation errors.',
                'errors' => $user->getErrors(),
            ], 422);
        }

        if (!$usersTable->save($user)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Unable to update the user.',
                'errors' => $user->getErrors(),
            ], 422);
        }

        return $this->jsonResponse([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $this->serializeUser($user),
        ]);
    }

    public function delete(string $id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        if (!$usersTable->delete($user)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Unable to delete the user.',
            ], 422);
        }

        return $this->jsonResponse([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }

    protected function serializeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'status' => $user->status,
            'role' => $user->role,
            'created' => $user->created?->format('M d, Y'),
            'updated' => $user->updated?->format('M d, Y H:i'),
        ];
    }
}
