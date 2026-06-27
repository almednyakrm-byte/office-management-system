<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\Ta3malatController;
use App\Repository\Ta3malatRepository;
use App\Entity\Ta3malat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Ta3malatControllerTest extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    public function setUp(): void
    {
        $this->repository = $this->createMock(Ta3malatRepository::class);
        $this->pdo = $this->createMock(PDO::class);
        $this->controller = new Ta3malatController($this->repository, $this->pdo);
    }

    public function testGetTa3malats()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Ta3malat()]);

        $response = $this->controller->getTa3malats();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostTa3malat()
    {
        $data = ['name' => 'Test Ta3malat'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new Ta3malat($data));

        $response = $this->controller->postTa3malat(new Request([], [], [], $data));
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutTa3malat()
    {
        $data = ['name' => 'Updated Ta3malat'];
        $id = 1;
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, new Ta3malat($data));

        $response = $this->controller->putTa3malat($id, new Request([], [], [], $data));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteTa3malat()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->controller->deleteTa3malat($id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}