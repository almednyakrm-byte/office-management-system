<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\EmpleadoController;
use App\Repository\EmpleadoRepository;
use App\Entity\Empleado;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestEmpleados extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EmpleadoRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->controller = new EmpleadoController($this->entityManager);
    }

    public function testGetEmpleados()
    {
        $this->repository->method('findAll')->willReturn([
            new Empleado(1, 'Juan', 'Perez'),
            new Empleado(2, 'Maria', 'Garcia'),
        ]);

        $response = $this->controller->getEmpleados();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPostEmpleado()
    {
        $request = new Request();
        $request->request->set('nombre', 'Juan');
        $request->request->set('apellido', 'Perez');

        $this->repository->method('save')->willReturn(new Empleado(1, 'Juan', 'Perez'));

        $response = $this->controller->postEmpleado($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutEmpleado()
    {
        $request = new Request();
        $request->request->set('nombre', 'Juan');
        $request->request->set('apellido', 'Perez');

        $this->repository->method('find')->willReturn(new Empleado(1, 'Juan', 'Perez'));
        $this->repository->method('save')->willReturn(new Empleado(1, 'Juan', 'Perez'));

        $response = $this->controller->putEmpleado(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteEmpleado()
    {
        $this->repository->method('find')->willReturn(new Empleado(1, 'Juan', 'Perez'));
        $this->repository->method('remove')->willReturn(null);

        $response = $this->controller->deleteEmpleado(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// EmpleadoController.php
namespace App\Controller;

use App\Repository\EmpleadoRepository;
use App\Entity\Empleado;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmpleadoController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEmpleados()
    {
        $empleados = $this->entityManager->getRepository(Empleado::class)->findAll();
        return new JsonResponse($empleados);
    }

    public function postEmpleado(Request $request)
    {
        $empleado = new Empleado();
        $empleado->setNombre($request->request->get('nombre'));
        $empleado->setApellido($request->request->get('apellido'));
        $this->entityManager->getRepository(Empleado::class)->save($empleado);
        return new JsonResponse($empleado, Response::HTTP_CREATED);
    }

    public function putEmpleado($id, Request $request)
    {
        $empleado = $this->entityManager->getRepository(Empleado::class)->find($id);
        $empleado->setNombre($request->request->get('nombre'));
        $empleado->setApellido($request->request->get('apellido'));
        $this->entityManager->getRepository(Empleado::class)->save($empleado);
        return new JsonResponse($empleado);
    }

    public function deleteEmpleado($id)
    {
        $empleado = $this->entityManager->getRepository(Empleado::class)->find($id);
        $this->entityManager->getRepository(Empleado::class)->remove($empleado);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}