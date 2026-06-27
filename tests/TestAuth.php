<?php

namespace App\Tests\Auth;

use App\Auth\AuthService;
use App\Auth\User;
use App\Auth\UserRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\Call;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class TestAuth extends TestCase
{
    private $authService;
    private $userRepository;
    private $session;

    protected function setUp(): void
    {
        $this->session = new Session(new MockArraySessionStorage());
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->authService = new AuthService($this->userRepository, $this->session);
    }

    public function testLoginSuccess()
    {
        $user = new User();
        $user->setId(1);
        $user->setUsername('test_user');
        $user->setPassword('password');

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with('test_user')
            ->willReturn($user);

        $this->authService->login('test_user', 'password');
        $this->assertTrue($this->session->has('user'));
        $this->assertEquals(1, $this->session->get('user')->getId());
    }

    public function testLoginFailure()
    {
        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with('test_user')
            ->willReturn(null);

        $this->authService->login('test_user', 'password');
        $this->assertFalse($this->session->has('user'));
    }

    public function testRegisterSuccess()
    {
        $user = new User();
        $user->setId(1);
        $user->setUsername('test_user');
        $user->setPassword('password');

        $this->userRepository->expects($this->once())
            ->method('saveUser')
            ->with($this->equalTo($user))
            ->willReturn($user);

        $this->authService->register('test_user', 'password');
        $this->assertTrue($this->session->has('user'));
        $this->assertEquals(1, $this->session->get('user')->getId());
    }

    public function testRegisterFailure()
    {
        $this->userRepository->expects($this->once())
            ->method('saveUser')
            ->with($this->isInstanceOf(User::class))
            ->willReturn(null);

        $this->authService->register('test_user', 'password');
        $this->assertFalse($this->session->has('user'));
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that a user can log in successfully when the username and password are correct.
- `testLoginFailure`: Tests that a user cannot log in when the username and password are incorrect.
- `testRegisterSuccess`: Tests that a user can register successfully when the username and password are correct.
- `testRegisterFailure`: Tests that a user cannot register when the username and password are incorrect.

Note that this test file assumes that the `AuthService` class has methods `login` and `register` that handle user authentication and registration. The `UserRepository` class is also assumed to have methods `getUserByUsername` and `saveUser` that handle user retrieval and saving.