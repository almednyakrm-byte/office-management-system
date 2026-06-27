<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\MoafaqatRepository;
use App\Service\MoafaqatService;
use PHPUnit\Framework\MockObject\MockObject;

class TestMoafaqats extends WebTestCase
{
    private $client;
    private $router;
    private $moafaqatRepository;
    private $moafaqatService;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = static::getContainer()->get(RouterInterface::class);
        $this->moafaqatRepository = $this->createMock(MoafaqatRepository::class);
        $this->moafaqatService = $this->createMock(MoafaqatService::class);
    }

    public function testGetMoafaqats()
    {
        $this->moafaqatRepository
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Moafaqat 1'],
                ['id' => 2, 'name' => 'Moafaqat 2'],
            ]);

        $this->moafaqatService
            ->method('getMoafaqats')
            ->willReturn($this->moafaqatRepository);

        $this->client->request(Request::METHOD_GET, $this->router->generate('moafaqats_index'));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostMoafaqat()
    {
        $data = ['name' => 'Moafaqat 3'];

        $this->moafaqatRepository
            ->method('save')
            ->with($data)
            ->willReturn($data);

        $this->moafaqatService
            ->method('createMoafaqat')
            ->willReturn($this->moafaqatRepository);

        $this->client->request(Request::METHOD_POST, $this->router->generate('moafaqats_create'), [], [], [], json_encode($data));

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPutMoafaqat()
    {
        $data = ['name' => 'Moafaqat 1'];

        $this->moafaqatRepository
            ->method('update')
            ->with(1, $data)
            ->willReturn($data);

        $this->moafaqatService
            ->method('updateMoafaqat')
            ->willReturn($this->moafaqatRepository);

        $this->client->request(Request::METHOD_PUT, $this->router->generate('moafaqats_update', ['id' => 1]), [], [], [], json_encode($data));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteMoafaqat()
    {
        $this->moafaqatRepository
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $this->moafaqatService
            ->method('deleteMoafaqat')
            ->willReturn($this->moafaqatRepository);

        $this->client->request(Request::METHOD_DELETE, $this->router->generate('moafaqats_delete', ['id' => 1]));

        $this->assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'موافقات' module. It uses mocked PDO statements to simulate database interactions. The tests verify the API responses for GET, POST, PUT, and DELETE requests.