<?php
declare(strict_types=1);

use App\Repository\UserRepository;
use App\Repository\UserRepositoryInterface;
use Psr\Container\ContainerInterface;

$container['user_repository'] = static fn (ContainerInterface $container): UserRepositoryInterface => new UserRepository($container->get('db'), new \App\Entity\User());
