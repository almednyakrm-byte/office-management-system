<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\GestionEchangeController;
use App\Repository\GestionEchangeRepository;
use App\Entity\GestionEchange;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use PHPUnit\Framework\MockObject\MockObject;

class TestGestionEchange extends TestCase
{
    private $controller;
    private $entityManager;
    private $repository;
    private $router;
    private $tokenStorage;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(EntityRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->controller = new GestionEchangeController(
            $this->entityManager,
            $this->repository,
            $this->router,
            $this->tokenStorage
        );
    }

    public function testGetAll(): void
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM gestion_echange')
            ->willReturn($stmt);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([new GestionEchange()]);

        $this->entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($pdo);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([new GestionEchange()], $response->getContent());
    }

    public function testGetOne(): void
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM gestion_echange WHERE id = ?')
            ->willReturn($stmt);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1])
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(new GestionEchange());

        $this->entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($pdo);

        $response = $this->controller->getOne(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(new GestionEchange(), $response->getContent());
    }

    public function testCreate(): void
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO gestion_echange (name, description) VALUES (?, ?)')
            ->willReturn($stmt);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['test', 'test'])
            ->willReturn(true);

        $this->entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($pdo);

        $request = new Request();
        $request->request->set('name', 'test');
        $request->request->set('description', 'test');

        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE gestion_echange SET name = ?, description = ? WHERE id = ?')
            ->willReturn($stmt);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['test', 'test', 1])
            ->willReturn(true);

        $this->entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($pdo);

        $request = new Request();
        $request->request->set('name', 'test');
        $request->request->set('description', 'test');

        $response = $this->controller->update(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM gestion_echange WHERE id = ?')
            ->willReturn($stmt);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1])
            ->willReturn(true);

        $this->entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($pdo);

        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'إدارة الصرف' module. It mocks the PDO statements and the entity manager to isolate the controller's behavior. The tests cover the GET, POST, PUT, and DELETE requests.