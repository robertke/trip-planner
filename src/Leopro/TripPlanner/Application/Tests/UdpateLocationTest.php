<?php

namespace Leopro\TripPlanner\Application\Tests;

use Leopro\TripPlanner\Application\Command\UpdateLocationCommand;
use Leopro\TripPlanner\Application\UseCase\UpdateLocationUseCase;
use Leopro\TripPlanner\Domain\Entity\Trip;
use Leopro\TripPlanner\Domain\ValueObject\TripIdentity;

class UpdateLocationTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateTrip()
    {
        $trip = Trip::createWithFirstRoute(new TripIdentity(1), 'my first planning');
        $route = $trip->getRoute(null);
        $route->addLeg('01-01-2014', -3.386665, 36.736908);

        $tripRepository = $this->getMockBuilder('Leopro\TripPlanner\Domain\Contract\TripRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $tripRepository
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($trip));

        $tripRepository
            ->expects($this->once())
            ->method('add');

        $command = new UpdateLocationCommand(null, '01-01-2014', 'new location name');
        $useCase = new UpdateLocationUseCase($tripRepository);

        $trip = $useCase->run($command);

        $this->assertInstanceOf('Leopro\TripPlanner\Domain\Entity\Trip', $trip);
        $this->assertEquals('new location name', $route->getLegs()->first()->getLocation()->getName());
    }
} 