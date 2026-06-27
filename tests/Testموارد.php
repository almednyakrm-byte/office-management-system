<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaterialsController;
use App\Repository\MaterialsRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestMaterials extends TestCase
{
    private $materialsController;
    private $materialsRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->materialsRepository = $this->createMock(MaterialsRepository::class);
        $this->pdo = $this->createMock(PDO::class);
        $this->materialsController = new MaterialsController($this->materialsRepository, $this->pdo);
    }

    public function testGetMaterials()
    {
        $materials = [
            ['id' => 1, 'name' => 'Material 1'],
            ['id' => 2, 'name' => 'Material 2'],
        ];

        $this->materialsRepository->expects($this->once())
            ->method('getAllMaterials')
            ->willReturn($materials);

        $response = $this->materialsController->getMaterials();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($materials), $response->getBody()->getContents());
    }

    public function testCreateMaterial()
    {
        $material = ['id' => 3, 'name' => 'Material 3'];

        $this->materialsRepository->expects($this->once())
            ->method('createMaterial')
            ->with($material)
            ->willReturn($material);

        $response = $this->materialsController->createMaterial($material);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($material), $response->getBody()->getContents());
    }

    public function testUpdateMaterial()
    {
        $material = ['id' => 1, 'name' => 'Material 1 Updated'];

        $this->materialsRepository->expects($this->once())
            ->method('updateMaterial')
            ->with($material)
            ->willReturn($material);

        $response = $this->materialsController->updateMaterial(1, $material);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($material), $response->getBody()->getContents());
    }

    public function testDeleteMaterial()
    {
        $this->materialsRepository->expects($this->once())
            ->method('deleteMaterial')
            ->with(1)
            ->willReturn(true);

        $response = $this->materialsController->deleteMaterial(1);

        $this->assertEquals(204, $response->getStatusCode());
    }
}