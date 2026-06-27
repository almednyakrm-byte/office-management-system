<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\DefaaterController;
use App\Repository\DefaaterRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestDefaater extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(DefaaterRepository::class);
        $this->controller = new DefaaterController($this->repository);
    }

    public function testGetDefaaters(): void
    {
        $this->repository->expects($this->once())
            ->method('getAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Defaater 1'],
                ['id' => 2, 'name' => 'Defaater 2'],
            ]);

        $response = $this->controller->getDefaaters();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'name' => 'Defaater 1'],
            ['id' => 2, 'name' => 'Defaater 2'],
        ], json_decode($response->getBody()->getContents(), true));
    }

    public function testPostDefaater(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO defaaters (name) VALUES (:name)');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with([':name' => 'Defaater 1']);

        $response = $this->controller->postDefaater(['name' => 'Defaater 1']);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPutDefaater(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE defaaters SET name = :name WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with([':name' => 'Defaater 1', ':id' => 1]);

        $response = $this->controller->putDefaater(1, ['name' => 'Defaater 1']);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteDefaater(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM defaaters WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $response = $this->controller->deleteDefaater(1);
        $this->assertEquals(200, $response->getStatusCode());
    }
}



// DefaaterController.php

namespace App\Controller;

use App\Repository\DefaaterRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaaterController
{
    private $repository;

    public function __construct(DefaaterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDefaaters(): JsonResponse
    {
        $defaaters = $this->repository->getAll();
        return new JsonResponse($defaaters, 200);
    }

    public function postDefaater(Request $request): JsonResponse
    {
        $this->pdo->prepare('INSERT INTO defaaters (name) VALUES (:name)')->execute([':name' => $request->get('name')]);
        return new JsonResponse(null, 201);
    }

    public function putDefaater(int $id, Request $request): JsonResponse
    {
        $this->pdo->prepare('UPDATE defaaters SET name = :name WHERE id = :id')->execute([':name' => $request->get('name'), ':id' => $id]);
        return new JsonResponse(null, 200);
    }

    public function deleteDefaater(int $id): JsonResponse
    {
        $this->pdo->prepare('DELETE FROM defaaters WHERE id = :id')->execute([':id' => $id]);
        return new JsonResponse(null, 200);
    }
}